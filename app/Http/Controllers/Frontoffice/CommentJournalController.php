<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\BookNote;
use App\Models\CommentsJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentJournalController extends Controller
{
    /**
     * Ajouter un commentaire à une note
     */
    public function store(Request $request, $noteId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $note = BookNote::findOrFail($noteId);

        // Vérifier que l'utilisateur a accès au journal de la note
        $journal = $note->journal;
        if ($journal->user_id !== Auth::id() && !$journal->isSharedWith(Auth::user())) {
            abort(403, 'You do not have access to this journal.');
        }

        // Créer le commentaire
        CommentsJournal::create([
            'book_note_id' => $noteId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment added.');
    }

    /**
     * Supprimer un commentaire
     */
    public function destroy($commentId)
    {
        $comment = CommentsJournal::findOrFail($commentId);

        // Seul l'auteur ou le propriétaire du journal peut supprimer
        $journal = $comment->note->journal;
        if ($comment->user_id !== Auth::id() && $journal->user_id !== Auth::id()) {
            abort(403, 'You cannot delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}