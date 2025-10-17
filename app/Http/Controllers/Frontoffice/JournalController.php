<?php

namespace App\Http\Controllers\Frontoffice;

use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class JournalController extends Controller
{
    // Affiche tous les journaux
    public function index()
    {
        $journals = Journal::all(); // Tous les journaux publics
        return view('frontoffice.journals.index', compact('journals'));
    }

    // Affiche un journal précis
    public function show($id)
    {
        $journal = Journal::findOrFail($id); // Pas besoin de auth()->user()
        $books = $journal->books()->wherePivot('archived', false)->get();
        $archivedCount = $journal->books()->wherePivot('archived', true)->count();

        return view('frontoffice.journals.show', compact('journal', 'books', 'archivedCount'));
    }

    // Création de journal accessible publiquement
    public function create()
    {
        return view('frontoffice.journals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $journal = Journal::create([
            'name' => $request->name,
            'user_id' => auth()->id(), // <- ajoute l'ID de l'utilisateur connecté

        ]);

        return redirect()->route('journals.index')
                         ->with('success', 'Journal créé avec succès !');
    }

    public function edit($id)
    {
        $journal = Journal::findOrFail($id);
        return view('frontoffice.journals.edit', compact('journal'));
    }

    public function update(Request $request, $id)
    {
        $journal = Journal::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $journal->update(['name' => $request->name]);

        return redirect()->route('journals.show', $journal->id)
                         ->with('success', 'Nom du journal mis à jour.');
    }

    // Gestion des livres dans le journal
    public function detachBook($journalId, $bookId)
    {
        $journal = Journal::findOrFail($journalId);
        $journal->books()->detach($bookId);

        return back()->with('success', 'Livre supprimé du journal.');
    }

    public function archiveBook($journalId, $bookId)
    {
        $journal = Journal::findOrFail($journalId);
        $journal->books()->updateExistingPivot($bookId, ['archived' => true]);

        return back()->with('success', 'Livre archivé.');
    }

    public function showArchived($id)
    {
        $journal = Journal::findOrFail($id);
        $archivedBooks = $journal->books()->wherePivot('archived', true)->get();

        return view('frontoffice.journals.archived', compact('journal', 'archivedBooks'));
    }

    public function unarchiveBook($journalId, $bookId)
    {
        $journal = Journal::findOrFail($journalId);
        $journal->books()->updateExistingPivot($bookId, ['archived' => false]);

        return back()->with('success', 'Livre désarchivé et remis dans le journal.');
    }
}
