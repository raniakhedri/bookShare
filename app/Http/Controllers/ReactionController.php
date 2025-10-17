<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\PostReaction;
use App\Models\CommentReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    /**
     * Réagir à un post
     */
    public function reactToPost(Request $request, Post $post)
    {
        $request->validate([
            'reaction_type' => 'required|in:like,love,laugh,wow,sad,angry,celebrate'
        ]);

        $userId = Auth::id();
        $reactionType = $request->reaction_type;

        // Vérifier si l'utilisateur a déjà réagi
        $existingReaction = PostReaction::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->reaction_type === $reactionType) {
                // Supprimer la réaction si c'est la même
                $existingReaction->delete();
                $action = 'removed';
            } else {
                // Changer le type de réaction
                $existingReaction->update(['reaction_type' => $reactionType]);
                $action = 'updated';
            }
        } else {
            // Créer une nouvelle réaction
            PostReaction::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'reaction_type' => $reactionType
            ]);
            $action = 'added';
        }

        // Récupérer les statistiques mises à jour
        $reactions = $this->getPostReactionStats($post);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'reactions' => $reactions,
                'user_reaction' => $action === 'removed' ? null : $reactionType
            ]);
        }

        return back()->with('success', 'Réaction ajoutée avec succès!');
    }

    /**
     * Réagir à un commentaire
     */
    public function reactToComment(Request $request, Comment $comment)
    {
        $request->validate([
            'reaction_type' => 'required|in:like,love,laugh,wow,sad,angry,celebrate'
        ]);

        $userId = Auth::id();
        $reactionType = $request->reaction_type;

        // Vérifier si l'utilisateur a déjà réagi
        $existingReaction = CommentReaction::where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->reaction_type === $reactionType) {
                // Supprimer la réaction si c'est la même
                $existingReaction->delete();
                $action = 'removed';
            } else {
                // Changer le type de réaction
                $existingReaction->update(['reaction_type' => $reactionType]);
                $action = 'updated';
            }
        } else {
            // Créer une nouvelle réaction
            CommentReaction::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'reaction_type' => $reactionType
            ]);
            $action = 'added';
        }

        // Récupérer les statistiques mises à jour
        $reactions = $this->getCommentReactionStats($comment);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'reactions' => $reactions,
                'user_reaction' => $action === 'removed' ? null : $reactionType
            ]);
        }

        return back()->with('success', 'Réaction ajoutée avec succès!');
    }

    /**
     * Obtenir les statistiques de réactions d'un post
     */
    public function getPostReactions(Post $post)
    {
        $reactions = $this->getPostReactionStats($post);
        
        return response()->json([
            'success' => true,
            'reactions' => $reactions
        ]);
    }

    /**
     * Obtenir les statistiques de réactions d'un commentaire
     */
    public function getCommentReactions(Comment $comment)
    {
        $reactions = $this->getCommentReactionStats($comment);
        
        return response()->json([
            'success' => true,
            'reactions' => $reactions
        ]);
    }

    /**
     * Calculer les statistiques de réactions d'un post
     */
    private function getPostReactionStats(Post $post)
    {
        $reactionCounts = PostReaction::where('post_id', $post->id)
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();

        $totalReactions = array_sum($reactionCounts);
        
        $reactions = [];
        foreach (PostReaction::REACTION_TYPES as $type => $details) {
            $count = $reactionCounts[$type] ?? 0;
            $reactions[$type] = [
                'count' => $count,
                'percentage' => $totalReactions > 0 ? round(($count / $totalReactions) * 100, 1) : 0,
                'emoji' => $details['emoji'],
                'label' => $details['label'],
                'color' => $details['color']
            ];
        }

        return [
            'total' => $totalReactions,
            'types' => $reactions,
            'top_reactions' => collect($reactions)
                ->where('count', '>', 0)
                ->sortByDesc('count')
                ->take(3)
                ->keys()
        ];
    }

    /**
     * Calculer les statistiques de réactions d'un commentaire
     */
    private function getCommentReactionStats(Comment $comment)
    {
        $reactionCounts = CommentReaction::where('comment_id', $comment->id)
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();

        $totalReactions = array_sum($reactionCounts);
        
        $reactions = [];
        foreach (CommentReaction::REACTION_TYPES as $type => $details) {
            $count = $reactionCounts[$type] ?? 0;
            $reactions[$type] = [
                'count' => $count,
                'percentage' => $totalReactions > 0 ? round(($count / $totalReactions) * 100, 1) : 0,
                'emoji' => $details['emoji'],
                'label' => $details['label'],
                'color' => $details['color']
            ];
        }

        return [
            'total' => $totalReactions,
            'types' => $reactions,
            'top_reactions' => collect($reactions)
                ->where('count', '>', 0)
                ->sortByDesc('count')
                ->take(3)
                ->keys()
        ];
    }

    /**
     * Obtenir les utilisateurs qui ont réagi à un post
     */
    public function getPostReactors(Post $post, $reactionType = null)
    {
        $query = PostReaction::where('post_id', $post->id)
            ->with('user');

        if ($reactionType) {
            $query->where('reaction_type', $reactionType);
        }

        $reactions = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'reactions' => $reactions,
            'total' => $reactions->total()
        ]);
    }

    /**
     * Obtenir les posts les plus réactifs d'un groupe
     */
    public function getMostReactedPosts($groupId)
    {
        $posts = Post::where('group_id', $groupId)
            ->withCount('reactions')
            ->orderByDesc('reactions_count')
            ->with(['user', 'reactions.user'])
            ->paginate(10);

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }
}