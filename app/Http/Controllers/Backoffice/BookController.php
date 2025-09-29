<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Journal;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = \App\Models\Book::all();
        $categories = \App\Models\Category::all();
        return view('backoffice.frontoffice.book', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('backoffice.books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|string',
            'availability' => 'required|boolean',
            'publication_year' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'file' => 'nullable|mimes:pdf,mp3,wav,ogg|max:20480',
        ]);

        $data = $validated;
        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $data['image'] = $imagePath;
        }
        // Gérer l'upload du fichier PDF ou audio
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('books/files', 'public');
            $data['file'] = $filePath;
            $mime = $file->getMimeType();
            if (str_contains($mime, 'pdf')) {
                $data['type'] = 'pdf';
            } elseif (str_contains($mime, 'audio')) {
                $data['type'] = 'audio';
            } else {
                $data['type'] = null;
            }
        }

        // Convertir la date en timestamp si présente
        if (!empty($data['publication_year'])) {
            $data['publication_year'] = date('Y-m-d H:i:s', strtotime($data['publication_year']));
        }

        // Associer l'utilisateur connecté
        $data['user_id'] = auth()->id() ?? 1; // Remplacer 1 par l'ID de l'utilisateur connecté si auth obligatoire

        \App\Models\Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Livre ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = \App\Models\Book::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('backoffice.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = \App\Models\Book::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|string',
            'availability' => 'required|boolean',
            'publication_year' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $data['image'] = $imagePath;
        }

        // Convertir la date en timestamp si présente
        if (!empty($data['publication_year'])) {
            $data['publication_year'] = date('Y-m-d H:i:s', strtotime($data['publication_year']));
        }

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Livre modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = \App\Models\Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès.');
    }

       // Formulaire pour ajouter un livre à un journal
    public function addToJournalForm($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Tous les journaux publics (pas besoin d'auth)
        $journals = Journal::all();

        return view('backoffice.books.add-to-journal', compact('book', 'journals'));
    }

    // Stocker le livre dans le journal
    public function storeInJournal(Request $request, $bookId)
    {
        $request->validate([
            'journal_id' => 'required|exists:journals,id',
        ]);

        $journal = Journal::findOrFail($request->journal_id);
        $book = Book::findOrFail($bookId);

        // Vérifier si le livre est déjà dans le journal
        if (!$journal->books()->where('book_id', $bookId)->exists()) {
            $journal->books()->attach($bookId, ['archived' => false]);
        }

        return redirect()->route('journals.show', $journal->id)
                         ->with('success', 'Livre ajouté au journal !');
    }

    // Afficher un livre dans un journal (sans auth)
    public function show($journalId, $bookId)
    {
        $journal = Journal::findOrFail($journalId);
        $book = Book::findOrFail($bookId);

        // Vérifier si le livre appartient au journal
        if (!$journal->books()->where('book_id', $bookId)->exists()) {
            abort(403, 'Ce livre n’appartient pas à ce journal.');
        }

        $notes = $journal->notes()->where('book_id', $bookId)->orderBy('created_at', 'desc')->get();

        return view('backoffice.books.show', compact('journal', 'book', 'notes'));
    }
}
