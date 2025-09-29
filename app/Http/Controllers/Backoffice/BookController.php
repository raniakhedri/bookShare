<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
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
    public function show(string $id)
    {
        //
    }

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
}
