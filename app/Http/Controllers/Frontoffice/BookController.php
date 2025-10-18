<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Display a listing of the books with search and filter capabilities.
     */
    public function index(Request $request)
    {
        $query = Book::query()->with('category');

        // Recherche par titre, auteur ou catégorie
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par catégorie
        if ($categoryId = $request->get('category')) {
            $query->where('category_id', $categoryId);
        }

        // Filtre par condition
        if ($condition = $request->get('condition')) {
            $query->where('condition', $condition);
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        if ($request->ajax()) {
            $booksData = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'image' => $book->image,
                    'availability' => $book->availability,
                    'category' => $book->category ? [
                        'id' => $book->category->id,
                        'name' => $book->category->name
                    ] : null,
                    'type' => $book->type,
                    'file' => $book->file,
                ];
            });

            return response()->json([
                'success' => true,
                'books' => $booksData,
                'pagination' => [
                    'total' => $books->total(),
                    'currentPage' => $books->currentPage(),
                    'lastPage' => $books->lastPage()
                ]
            ]);
        }

        return view('frontoffice.book', compact('books', 'categories'));
    }

    /**
     * Display the specified book.
     */
    public function show($id)
    {
        $book = Book::with(['category', 'reviews.user'])->findOrFail($id);
        return view('frontoffice.book_show', compact('book'));
    }

    /**
     * Toggle favorite status of a book.
     */
    public function toggleFavorite(Book $book): JsonResponse
    {
        $user = auth()->user();
        $favorite = $user->favorites()->where('book_id', $book->id)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Book removed from favorites';
            $status = false;
        } else {
            $user->favorites()->create(['book_id' => $book->id]);
            $message = 'Book added to favorites';
            $status = true;
        }

        return response()->json([
            'message' => $message,
            'status' => $status,
            'count' => $user->favorites()->count()
        ]);
    }

    /**
     * Display user's favorite books.
     */
    public function favorites()
    {
        $favorites = auth()->user()->favorites()->with('book.category')->latest()->get();
        return view('frontoffice.favorites.index', compact('favorites'));
    }

    /**
     * Remove a book from favorites.
     */
    public function removeFavorite(Book $book): JsonResponse
    {
        auth()->user()->favorites()->where('book_id', $book->id)->delete();
        
        return response()->json([
            'message' => 'Book removed from favorites',
            'count' => auth()->user()->favorites()->count()
        ]);
    }
}
