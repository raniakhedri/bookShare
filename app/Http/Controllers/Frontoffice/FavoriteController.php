<?php

namespace App\Http\Controllers\Frontoffice;

use App\Models\Favorite;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the user's favorites.
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('book')->latest()->get();
        return view('frontoffice.favorites.index', compact('favorites'));
    }

    /**
     * Toggle favorite status of a book.
     */
    public function toggle(Book $book): JsonResponse
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
     * Remove a book from favorites.
     */
    public function destroy(Book $book): JsonResponse
    {
        auth()->user()->favorites()->where('book_id', $book->id)->delete();
        
        return response()->json([
            'message' => 'Book removed from favorites',
            'count' => auth()->user()->favorites()->count()
        ]);
    }
}