<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketBook;
use Illuminate\Http\Request;

class BookAvailabilityController extends Controller
{
    /**
     * Check if a book is available in the marketplace
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'author' => 'nullable|string',
            'isbn' => 'nullable|string',
        ]);

        $query = MarketBook::where('is_available', true);

        // Search by title OR author (at least one should match)
        if ($request->filled('title') || $request->filled('author')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('title')) {
                    $q->where('title', 'LIKE', '%' . $request->title . '%');
                }
                if ($request->filled('author')) {
                    $q->orWhere('author', 'LIKE', '%' . $request->author . '%');
                }
            });
        }

        $books = $query->get();

        if ($books->isEmpty()) {
            return response()->json([
                'available' => false,
                'message' => 'Book not found in marketplace',
                'books' => []
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Book(s) found in marketplace',
            'count' => $books->count(),
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'condition' => $book->condition,
                    'price' => $book->price,
                    'owner' => [
                        'name' => $book->owner->name ?? 'Unknown',
                        'id' => $book->owner_id,
                    ],
                    'image' => $book->image ? asset('storage/' . $book->image) : null,
                    'marketplace_url' => route('marketplace.books.show', $book->id),
                ];
            })
        ]);
    }

    /**
     * Get book details by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBook($id)
    {
        $book = MarketBook::with('owner')->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'condition' => $book->condition,
                'price' => $book->price,
                'is_available' => $book->is_available,
                'owner' => [
                    'name' => $book->owner->name ?? 'Unknown',
                    'id' => $book->owner_id,
                ],
                'image' => $book->image ? asset('storage/' . $book->image) : null,
                'marketplace_url' => route('marketplace.books.show', $book->id),
            ]
        ]);
    }
}
