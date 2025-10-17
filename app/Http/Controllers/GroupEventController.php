<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupEvent;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupEventController extends Controller
{
    /**
     * Afficher tous les événements d'un groupe
     */
    public function index(Group $group)
    {
        // Charger la relation members
        $group->load('members');
        
        // Vérifier que l'utilisateur est membre du groupe
        if (!$this->isGroupMember($group)) {
            abort(403, 'Accès réservé aux membres du groupe');
        }

        $upcomingEvents = $group->upcomingEvents()->with(['creator', 'participants.user'])->get();
        $pastEvents = $group->pastEvents()->with(['creator', 'participants.user'])->limit(10)->get();
        $eventTypes = GroupEvent::EVENT_TYPES;

        // Ajouter le statut d'inscription pour l'utilisateur connecté
        foreach ($upcomingEvents as $event) {
            $event->user_registration_status = $event->getUserRegistrationStatus(Auth::id());
        }

        return view('events.index', compact(
            'group', 
            'upcomingEvents', 
            'pastEvents', 
            'eventTypes'
        ));
    }

    /**
     * Afficher le formulaire de création d'événement
     */
    public function create(Group $group)
    {
        // Charger la relation members
        $group->load('members');
        
        // Vérifier que l'utilisateur est membre du groupe
        if (!$this->isGroupMember($group)) {
            abort(403, 'Accès réservé aux membres du groupe');
        }

        $eventTypes = GroupEvent::EVENT_TYPES;
        return view('events.create', compact('group', 'eventTypes'));
    }

    /**
     * Créer un nouvel événement
     */
    public function store(Request $request, Group $group)
    {
        // Vérifier que l'utilisateur est membre du groupe
        if (!$this->isGroupMember($group)) {
            abort(403, 'Accès réservé aux membres du groupe');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:' . implode(',', array_keys(GroupEvent::EVENT_TYPES)),
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'max_participants' => 'nullable|integer|min:1|max:1000',
            'requires_approval' => 'boolean',
            'requirements' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resources' => 'nullable|array',
            'resources.*' => 'string|max:255'
        ]);

        $data = $request->all();
        $data['group_id'] = $group->id;
        $data['creator_id'] = Auth::id();
        $data['is_virtual'] = !empty($request->meeting_link);
        $data['status'] = 'published'; // Publier directement
        
        // Upload de l'image de couverture
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event = GroupEvent::create($data);

        return redirect()->route('groups.events.show', [$group->id, $event->id])
                        ->with('success', 'Événement créé avec succès !');
    }

    /**
     * Afficher un événement spécifique
     */
    public function show(Group $group, GroupEvent $event)
    {
        // Charger la relation members
        $group->load('members');
        
        // Vérifier que l'utilisateur est membre du groupe
        if (!$this->isGroupMember($group) || $event->group_id !== $group->id) {
            abort(403, 'Accès réservé aux membres du groupe');
        }

        $event->load(['creator', 'approvedParticipants.user', 'pendingParticipants.user']);
        $userRegistration = $event->participants()->where('user_id', Auth::id())->first();
        $canRegister = $event->canUserRegister(Auth::id());

        return view('events.show', compact(
            'group', 
            'event', 
            'userRegistration', 
            'canRegister'
        ));
    }

    /**
     * S'inscrire à un événement
     */
    public function register(Request $request, Group $group, GroupEvent $event)
    {
        // Vérifier que l'utilisateur est membre du groupe
        if (!$this->isGroupMember($group) || $event->group_id !== $group->id) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        if (!$event->canUserRegister(Auth::id())) {
            return redirect()->back()->with('error', 'Inscription impossible');
        }

        $request->validate([
            'registration_message' => 'nullable|string|max:500',
            'additional_info' => 'nullable|string|max:1000'
        ]);

        $success = $event->registerUser(
            Auth::id(),
            $request->registration_message,
            $request->additional_info
        );

        if ($success) {
            $status = $event->requires_approval ? 'En attente d\'approbation' : 'Inscription confirmée';
            return redirect()->route('groups.events.show', [$group, $event])
                ->with('success', $status);
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'inscription');
    }

    /**
     * Se désinscrire d'un événement
     */
    public function unregister(Group $group, GroupEvent $event)
    {
        // Vérifier que l'utilisateur est membre du groupe
        if (!$this->isGroupMember($group) || $event->group_id !== $group->id) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $participant = $event->participants()->where('user_id', Auth::id())->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas inscrit à cet événement');
        }

        // Empêcher la désinscription si l'événement a commencé
        if ($event->has_started) {
            return redirect()->back()->with('error', 'Impossible de se désinscrire, l\'événement a déjà commencé');
        }

        $participant->delete();

        return redirect()->route('groups.events.show', [$group, $event])
            ->with('success', 'Désinscription effectuée avec succès');
    }

    /**
     * Approuver un participant (créateur ou admin uniquement)
     */
    public function approveParticipant(Group $group, GroupEvent $event, EventParticipant $participant)
    {
        // Vérifier les permissions
        if (!$this->canManageEvent($group, $event)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($event->approveParticipant($participant->id)) {
            return response()->json([
                'success' => true,
                'message' => 'Participant approuvé avec succès'
            ]);
        }

        return response()->json(['error' => 'Erreur lors de l\'approbation'], 500);
    }

    /**
     * Rejeter un participant (créateur ou admin uniquement)
     */
    public function rejectParticipant(Request $request, Group $group, GroupEvent $event, EventParticipant $participant)
    {
        // Vérifier les permissions
        if (!$this->canManageEvent($group, $event)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:255'
        ]);

        if ($event->rejectParticipant($participant->id, $request->reason)) {
            return response()->json([
                'success' => true,
                'message' => 'Participant rejeté'
            ]);
        }

        return response()->json(['error' => 'Erreur lors du rejet'], 500);
    }

    /**
     * Modifier un événement
     */
    public function edit(Group $group, GroupEvent $event)
    {
        // Charger la relation members
        $group->load('members');
        
        // Vérifier les permissions
        if (!$this->canManageEvent($group, $event)) {
            abort(403, 'Non autorisé');
        }

        $eventTypes = GroupEvent::EVENT_TYPES;
        return view('events.edit', compact('group', 'event', 'eventTypes'));
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Group $group, GroupEvent $event)
    {
        // Vérifier les permissions
        if (!$this->canManageEvent($group, $event)) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:' . implode(',', array_keys(GroupEvent::EVENT_TYPES)),
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'max_participants' => 'nullable|integer|min:1|max:1000',
            'requires_approval' => 'boolean',
            'requirements' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resources' => 'nullable|array'
        ]);

        $data = $request->all();
        $data['is_virtual'] = !empty($request->meeting_link);

        // Upload de l'image de couverture
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('groups.events.show', [$group->id, $event->id])
                        ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Supprimer un événement
     */
    public function destroy(Group $group, GroupEvent $event)
    {
        // Vérifier les permissions
        if (!$this->canManageEvent($group, $event)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Supprimer l'image de couverture
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Événement supprimé avec succès'
        ]);
    }

    /**
     * Marquer la présence des participants
     */
    public function markAttendance(Request $request, Group $group, GroupEvent $event)
    {
        // Vérifier les permissions
        if (!$this->canManageEvent($group, $event)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'participant_ids' => 'required|array',
            'participant_ids.*' => 'integer|exists:event_participants,id'
        ]);

        $event->markAttendance($request->participant_ids);

        return response()->json([
            'success' => true,
            'message' => 'Présence marquée avec succès'
        ]);
    }

    /**
     * Vérifier si l'utilisateur est membre du groupe
     */
    private function isGroupMember(Group $group)
    {
        return $group->users()
                   ->where('users.id', Auth::id())
                   ->wherePivot('status', 'accepted')
                   ->exists();
    }

    /**
     * Vérifier si l'utilisateur peut gérer l'événement
     */
    private function canManageEvent(Group $group, GroupEvent $event)
    {
        return $event->creator_id === Auth::id() || 
               $group->creator_id === Auth::id() || 
               Auth::user()->role === 'admin';
    }
}
