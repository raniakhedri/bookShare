<?php

namespace App\Http\Controllers\Frontoffice;

use App\Models\Journal;
use App\Models\JournalShare;
use App\Notifications\JournalSharedNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\InteractiveSession;
use Illuminate\Support\Facades\Http;

class JournalController extends Controller
{
    // Affiche tous les journaux
    public function index()
{
    $userId = auth()->id();

    // 1️⃣ Journaux appartenant à l'utilisateur
    $ownJournals = Journal::where('user_id', $userId)->pluck('id');

    // 2️⃣ Journaux partagés avec lui (colonne = user_id)
    $sharedJournals = \App\Models\JournalShare::where('user_id', $userId)
        ->pluck('journal_id');

    // 3️⃣ Fusionner les deux listes sans doublons
    $allJournalIds = $ownJournals->merge($sharedJournals)->unique();

    // 4️⃣ Récupérer les journaux correspondants
    $journals = \App\Models\Journal::whereIn('id', $allJournalIds)
        ->get()
        ->map(function ($journal) use ($userId) {
            $journal->is_owner = $journal->user_id === $userId;
            return $journal;
        });

    return view('frontoffice.journals.index', compact('journals'));
}

    // Affiche un journal précis
   public function show($id)
{
    $journal = Journal::findOrFail($id);

    if ($journal->user_id !== auth()->id() && !$journal->isSharedWith(auth()->user())) {
        abort(403, 'You do not have access to this journal.');
    }

    if ($journal->is_locked) {
        $unlockedJournals = session('unlocked_journals', []);
        if (!in_array($journal->id, $unlockedJournals)) {
            return redirect()->route('journals.unlock.form', $journal->id);
        }
    }

    $books = $journal->books()->wherePivot('archived', false)->get();
    $archivedCount = $journal->books()->wherePivot('archived', true)->count();

    // Charger les notes liées à CE journal et à ces livres
    $notes = \App\Models\BookNote::with('user', 'comments.user')
        ->where('journal_id', $journal->id)
        ->whereIn('book_id', $books->pluck('id'))
        ->get();

    $quizzes = \App\Models\InteractiveSession::where('journal_id', $journal->id)->get();

    // Vérifier si l'utilisateur est collaborateur
    $isCollaborator = $journal->user_id !== auth()->id() && $journal->isSharedWith(auth()->user());

    return view('frontoffice.journals.show', compact('journal', 'books', 'archivedCount', 'notes', 'quizzes', 'isCollaborator'));
}


    // Création de journal
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
            'user_id' => auth()->id(),
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

    public function showBook($journalId, $bookId)
    {
        $journal = Journal::findOrFail($journalId);
        $book = $journal->books()->where('books.id', $bookId)->firstOrFail();

        // Charger les notes pour ce journal + livre spécifique
        $notes = \App\Models\BookNote::with('user', 'comments.user')
            ->where('journal_id', $journalId)
            ->where('book_id', $bookId)
            ->get();

        return view('frontoffice.journals.book_show', compact('journal', 'book', 'notes'));
    }

    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
        $journal->delete();
        return redirect()->route('journals.index')
                         ->with('success', 'Journal "' . $journal->name . '" and all its books have been deleted.');
    }

    public function lock(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:4',
        ]);

        $journal = Journal::findOrFail($id);
        $journal->is_locked = true;
        $journal->password = $request->password;
        $journal->save();

        return back()->with('success', 'Journal "' . $journal->name . '" is now locked.');
    }

    public function unlock($id)
    {
        $journal = Journal::findOrFail($id);
        $journal->is_locked = false;
        $journal->password = null;
        $journal->save();

        return back()->with('success', 'Journal "' . $journal->name . '" is now unlocked.');
    }

    public function showUnlockForm($id)
    {
        $journal = Journal::findOrFail($id);
        if (!$journal->is_locked) {
            return redirect()->route('journals.show', $id);
        }
        return view('frontoffice.journals.unlock', compact('journal'));
    }

    public function unlockAttempt(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $journal = Journal::findOrFail($id);

        if (!$journal->is_locked) {
            return redirect()->route('journals.show', $id);
        }

        if (!Hash::check($request->password, $journal->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
        }

        $unlocked = session('unlocked_journals', []);
        if (!in_array($journal->id, $unlocked)) {
            $unlocked[] = $journal->id;
            session(['unlocked_journals' => $unlocked]);
        }

        return redirect()->route('journals.show', $journal->id);
    }

    public function share(Request $request, $journalId)
{
    $request->validate(['email' => 'required|email|exists:users,email']);
    
    $journal = Journal::findOrFail($journalId);
    $userToShare = User::where('email', $request->email)->first();

    if ($userToShare->id === auth()->id()) {
        return back()->withErrors(['email' => 'You cannot share with yourself.']);
    }

    if ($journal->isSharedWith($userToShare)) {
        return back()->withErrors(['email' => 'This journal is already shared with this user.']);
    }

    $journal->collaborators()->attach($userToShare->id, [
        'shared_by' => auth()->id(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 🔔 Envoyer une notification par email
    $userToShare->notify(new JournalSharedNotification($journal, auth()->user()));

    return back()->with('success', 'Journal shared successfully! An email has been sent to ' . $userToShare->email);
}

    public function unshare($journalId, $userId)
    {
        $journal = Journal::findOrFail($journalId);
        $journal->collaborators()->detach($userId);
        return back()->with('success', 'Access removed.');
    }

    public function leave($journalId)
{
    $journal = Journal::findOrFail($journalId);

    // Vérifier que l'utilisateur est collaborateur (pas propriétaire)
    if ($journal->user_id === auth()->id()) {
        return back()->withErrors(['error' => 'You are the owner. You cannot leave your own journal.']);
    }

    if (!$journal->isSharedWith(auth()->user())) {
        abort(403, 'You are not a collaborator of this journal.');
    }

    $journal->collaborators()->detach(auth()->id());

    return redirect()->route('journals.index')->with('success', 'You have left the journal "' . $journal->name . '".');
}

public function participantQuizzes($id)
{
    $journal = Journal::findOrFail($id);

    // Récupère les quiz associés au journal
    $quizzes = \App\Models\Quiz::where('journal_id', $journal->id)->get();

    return view('frontoffice.journals.participantQuizzes', compact('journal', 'quizzes'));
}


}