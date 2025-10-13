<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\BookNote;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Ajouter une note à un livre dans un journal
     */
    public function store(Request $request, $journalId, $bookId)
    {
        // Valider les données
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Vérifier que le journal existe et que l'utilisateur y a accès
        $journal = Journal::findOrFail($journalId);
        
        if ($journal->user_id !== Auth::id() && !$journal->isSharedWith(Auth::user())) {
            abort(403, 'You do not have permission to add notes to this journal.');
        }

        // Créer la note
        BookNote::create([
            'journal_id' => $journalId,
            'book_id' => $bookId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    /**
     * Mettre à jour une note (optionnel)
     */
    public function update(Request $request, $noteId)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $note = BookNote::findOrFail($noteId);

        // Seul l'auteur peut modifier
        if ($note->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own notes.');
        }

        $note->update(['content' => $request->content]);

        return back()->with('success', 'Note updated.');
    }

    /**
     * Supprimer une note
     */
    public function destroy($noteId)
    {
        $note = BookNote::findOrFail($noteId);

        // Seul l'auteur ou le propriétaire du journal peut supprimer
        $journal = $note->journal;
        if ($note->user_id !== Auth::id() && $journal->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to delete this note.');
        }

        $note->delete();

        return back()->with('success', 'Note deleted.');
    }
}