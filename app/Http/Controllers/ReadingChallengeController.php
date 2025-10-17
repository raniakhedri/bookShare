<?php

namespace App\Http\Controllers;

use App\Models\ReadingChallenge;
use App\Models\ChallengeParticipant;
use App\Models\Group;
use App\Services\AIReadingChallengeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReadingChallengeController extends Controller
{
    protected $aiGenerator;

    public function __construct(AIReadingChallengeGenerator $aiGenerator)
    {
        $this->middleware('auth');
        $this->aiGenerator = $aiGenerator;
    }

    /**
     * Afficher les défis d'un groupe
     */
    public function index(Group $group)
    {
        $challenges = ReadingChallenge::where('group_id', $group->id)
            ->with(['participants.user', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $userParticipations = [];
        if (Auth::check()) {
            $userParticipations = ChallengeParticipant::where('user_id', Auth::id())
                ->whereIn('challenge_id', $challenges->pluck('id'))
                ->get()
                ->keyBy('challenge_id');
        }

        return view('challenges.index', compact('group', 'challenges', 'userParticipations'));
    }

    /**
     * Afficher un défi spécifique
     */
    public function show(Group $group, ReadingChallenge $challenge)
    {
        $challenge->load([
            'participants.user',
            'creator',
            'category'
        ]);

        $userParticipation = null;
        if (Auth::check()) {
            $userParticipation = ChallengeParticipant::where([
                'challenge_id' => $challenge->id,
                'user_id' => Auth::id()
            ])->first();
        }

        // Statistiques du défi
        $stats = [
            'total_participants' => $challenge->participants->count(),
            'active_participants' => $challenge->participants->where('status', 'active')->count(),
            'completed_participants' => $challenge->participants->where('status', 'completed')->count(),
            'average_progress' => round($challenge->participants->avg('progress_percentage') ?? 0, 1),
            'days_remaining' => now()->diffInDays($challenge->end_date, false),
            'leaderboard' => $challenge->participants()
                ->with('user')
                ->where('progress_percentage', '>', 0)
                ->orderBy('progress_percentage', 'desc')
                ->limit(10)
                ->get()
        ];

        return view('challenges.show', compact('group', 'challenge', 'userParticipation', 'stats'));
    }

    /**
     * Participer à un défi
     */
    public function join(Request $request, Group $group, ReadingChallenge $challenge)
    {
        // Vérifications
        if (!$challenge->canParticipate(Auth::user())) {
            return back()->with('error', 'Vous ne pouvez pas participer à ce défi.');
        }

        $existingParticipation = ChallengeParticipant::where([
            'challenge_id' => $challenge->id,
            'user_id' => Auth::id()
        ])->first();

        if ($existingParticipation) {
            return back()->with('info', 'Vous participez déjà à ce défi !');
        }

        // Créer la participation
        ChallengeParticipant::create([
            'challenge_id' => $challenge->id,
            'user_id' => Auth::id(),
            'joined_at' => now(),
            'status' => 'active',
            'progress_data' => [],
            'progress_percentage' => 0
        ]);

        // Incrémenter le compteur de participants
        $challenge->increment('current_participants');

        return back()->with('success', 'Vous participez maintenant au défi "' . $challenge->title . '" !');
    }

    /**
     * Quitter un défi
     */
    public function leave(Request $request, Group $group, ReadingChallenge $challenge)
    {
        $participation = ChallengeParticipant::where([
            'challenge_id' => $challenge->id,
            'user_id' => Auth::id()
        ])->first();

        if (!$participation) {
            return back()->with('error', 'Vous ne participez pas à ce défi.');
        }

        if ($participation->status === 'completed') {
            return back()->with('error', 'Vous ne pouvez pas quitter un défi que vous avez terminé.');
        }

        $participation->delete();
        $challenge->decrement('current_participants');

        return back()->with('success', 'Vous avez quitté le défi.');
    }

    /**
     * Mettre à jour la progression
     */
    public function updateProgress(Request $request, Group $group, ReadingChallenge $challenge)
    {
        $request->validate([
            'progress_type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'book_title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'pages_read' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $participation = ChallengeParticipant::where([
            'challenge_id' => $challenge->id,
            'user_id' => Auth::id()
        ])->first();

        if (!$participation) {
            return back()->with('error', 'Vous ne participez pas à ce défi.');
        }

        // Mettre à jour les données de progression
        $progressData = $participation->progress_data ?? [];
        
        $newEntry = [
            'date' => now()->toDateTimeString(),
            'type' => $request->progress_type,
            'value' => $request->value,
            'book_title' => $request->book_title,
            'author' => $request->author,
            'pages_read' => $request->pages_read,
            'notes' => $request->notes
        ];

        $progressData[] = $newEntry;

        // Calculer le nouveau pourcentage de progression
        $newPercentage = $participation->calculateProgress($progressData, $challenge);

        $participation->update([
            'progress_data' => $progressData,
            'progress_percentage' => $newPercentage,
            'last_update' => now()
        ]);

        // Vérifier si le défi est terminé
        if ($newPercentage >= 100 && $participation->status !== 'completed') {
            $participation->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return back()->with('success', '🎉 Félicitations ! Vous avez terminé le défi "' . $challenge->title . '" !');
        }

        return back()->with('success', 'Progression mise à jour avec succès !');
    }

    /**
     * Générer un nouveau défi AI pour un groupe
     */
    public function generate(Request $request, Group $group)
    {
        // Vérifications d'autorisation
        if (!$group->members->contains(Auth::user()) || !Auth::user()->can('create-challenges')) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à générer des défis pour ce groupe.');
        }

        $request->validate([
            'challenge_type' => 'nullable|string|in:monthly_genre,author_focus,cultural_discovery,page_challenge,speed_reading,classic_revival',
            'difficulty' => 'nullable|string|in:easy,medium,hard'
        ]);

        try {
            // Vérifier s'il y a déjà un défi actif
            $hasActiveChallenge = ReadingChallenge::where('group_id', $group->id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->exists();

            if ($hasActiveChallenge) {
                return back()->with('error', 'Ce groupe a déjà un défi actif. Attendez qu\'il se termine pour en générer un nouveau.');
            }

            $options = array_filter([
                'type' => $request->challenge_type,
                'difficulty' => $request->difficulty
            ]);

            $challenge = $this->aiGenerator->generateChallenge($group, $options);

            // Créer un post automatique sur le mur du groupe
            $this->createChallengePost($group, $challenge);

            return redirect()->route('challenges.show', [$group, $challenge])
                ->with('success', '🚀 Nouveau défi généré : "' . $challenge->title . '" !');

        } catch (\Exception $e) {
            \Log::error('Erreur génération défi AI: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du défi. Veuillez réessayer.');
        }
    }

    /**
     * Créer un post automatique pour annoncer le défi
     */
    private function createChallengePost(Group $group, ReadingChallenge $challenge)
    {
        $content = "🎯 **Nouveau Défi de Lecture !**\n\n";
        $content .= "**" . $challenge->title . "**\n\n";
        $content .= $challenge->description . "\n\n";
        $content .= "📅 **Durée :** " . $challenge->start_date->format('d/m/Y') . " → " . $challenge->end_date->format('d/m/Y') . "\n";
        $content .= "🏆 **Difficulté :** " . ucfirst($challenge->difficulty_level) . "\n";
        
        if ($challenge->objectives) {
            $content .= "🎯 **Objectifs :**\n";
            foreach ($challenge->objectives as $key => $value) {
                if ($key === 'target_books') {
                    $content .= "• Lire {$value} livre(s)\n";
                } elseif ($key === 'target_pages') {
                    $content .= "• Lire {$value} pages\n";
                }
            }
        }
        
        $content .= "\nRejoignez le défi et partagez vos lectures ! 📚✨";

        // Créer le post (vous devrez adapter selon votre modèle Post)
        try {
            DB::table('posts')->insert([
                'user_id' => 1, // User AI system
                'group_id' => $group->id,
                'content' => $content,
                'type' => 'challenge_announcement',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la génération du défi
            \Log::error('Erreur création post défi: ' . $e->getMessage());
        }
    }

    /**
     * Tableau de bord des défis pour l'utilisateur
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $activeParticipations = ChallengeParticipant::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['challenge.group'])
            ->get();

        $completedParticipations = ChallengeParticipant::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['challenge.group'])
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_challenges' => ChallengeParticipant::where('user_id', $user->id)->count(),
            'completed_challenges' => $completedParticipations->count(),
            'active_challenges' => $activeParticipations->count(),
            'total_points' => $completedParticipations->sum(function($p) {
                return $p->challenge->rewards['points'] ?? 0;
            }),
            'completion_rate' => $activeParticipations->count() > 0 
                ? round($activeParticipations->avg('progress_percentage'), 1) 
                : 0
        ];

        return view('challenges.dashboard', compact('activeParticipations', 'completedParticipations', 'stats'));
    }

    /**
     * API : Générer des défis pour tous les groupes (cron job)
     */
    public function generateForAllGroups()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $results = $this->aiGenerator->generateChallengesForAllGroups();
        
        return response()->json([
            'success' => true,
            'message' => 'Génération de défis terminée',
            'results' => $results
        ]);
    }
}
