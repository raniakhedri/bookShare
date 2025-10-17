<?php

namespace App\Http\Controllers\Frontoffice;

use App\Models\Note;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; 


class NoteController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'journal_id' => 'required|exists:journals,id,user_id,' . Auth::id(),
        'book_id' => 'required|exists:books,id',
        'excerpt' => 'required|string|max:1000',
        'content' => 'required|string|max:2000',
    ]);

    $journal = Journal::findOrFail($request->journal_id);
    if (!$journal->books()->where('book_id', $request->book_id)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Ce livre n’appartient pas à ce journal.'
        ], 400);
    }

    Note::create([
        'user_id' => Auth::id(),
        'journal_id' => $request->journal_id,
        'book_id' => $request->book_id,
        'excerpt' => $request->excerpt,
        'content' => $request->content,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Note ajoutée avec succès !'
    ]);
}

    public function destroy(Note $note)
    {
        // Vérifier que la note appartient à l'utilisateur courant
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        return back()->with('success', 'Note supprimée.');
    }
}