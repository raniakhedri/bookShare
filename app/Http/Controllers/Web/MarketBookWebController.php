<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MarketBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MarketBookWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $books = MarketBook::where('owner_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('marketplace.my-books', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontoffice.add-book');
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
            'condition' => 'required|in:New,Good,Fair,Poor',
            'image' => 'nullable|image|max:2048',
            'generated_image_path' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $validated['owner_id'] = Auth::id();

        // Handle image upload (either uploaded file or generated image)
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('market-books', 'public');
        } elseif ($request->filled('generated_image_path')) {
            $validated['image'] = $request->generated_image_path;
        }

        // Remove the generated_image_path from validated data before creating the record
        unset($validated['generated_image_path']);

        MarketBook::create($validated);

        return redirect()->route('marketplace.books.index')
            ->with('success', 'Market book created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketBook $book)
    {
        $book->load(['owner', 'transactions.requester', 'transactions.exchangeRequest.offeredMarketBook']);

        return view('marketplace.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('marketplace.books.index')
                ->with('error', 'You can only edit your own books.');
        }

        return view('marketplace.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('marketplace.books.index')
                ->with('error', 'You can only edit your own books.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'required|in:New,Good,Fair,Poor',
            'image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $validated['image'] = $request->file('image')->store('market-books', 'public');
        }

        $book->update($validated);

        return redirect()->route('marketplace.books.show', $book)
            ->with('success', 'Market book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('marketplace.books.index')
                ->with('error', 'You can only delete your own books.');
        }

        // Delete image if exists
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->route('marketplace.books.index')
            ->with('success', 'Market book deleted successfully!');
    }

    /**
     * Toggle availability of a market book.
     */
    public function toggleAvailability(MarketBook $book)
    {
        // Check if user owns the book
        if ($book->owner_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You can only toggle availability of your own books.');
        }

        $book->update(['is_available' => !$book->is_available]);

        $status = $book->is_available ? 'available' : 'unavailable';
        return redirect()->back()
            ->with('success', "Book marked as {$status} successfully!");
    }

    /**
     * Generate AI-powered description for a book.
     */
    public function generateDescription(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'condition' => 'nullable|string',
        ]);

        try {
            $prompt = "Generate a compelling and informative description for a book marketplace listing. The book is titled '{$request->title}' by {$request->author}";

            if ($request->condition) {
                $prompt .= " and is in {$request->condition} condition";
            }

            $prompt .= ". Write a description that would attract potential readers and book exchangers. Include what makes this book special, its themes, and why someone would want to read it. Keep it between 50-150 words and make it engaging for a book sharing community.";

            $response = Http::withHeaders([
                'api-key' => config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.openai.endpoint') . '?api-version=' . config('services.openai.api_version'), [
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $prompt
                            ]
                        ],
                        'max_tokens' => 200,
                        'temperature' => 0.7,
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                $description = $data['choices'][0]['message']['content'] ?? '';

                return response()->json([
                    'success' => true,
                    'description' => trim($description)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate description. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the description.'
            ], 500);
        }
    }

    /**
     * Generate AI-powered book cover image using DALL-E.
     */
    public function generateBookCover(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $prompt = "Create a professional book cover design for the book titled '{$request->title}' by {$request->author}";

            if ($request->description) {
                $prompt .= ". The book is about: " . substr($request->description, 0, 200);
            }

            $prompt .= ". Design should be eye-catching, professional, and suitable for a book marketplace. Include the title and author name in an attractive typography. The cover should be visually appealing and convey the book's essence.";

            $response = Http::withHeaders([
                'api-key' => config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.openai.dalle_endpoint') . '?api-version=' . config('services.openai.dalle_api_version'), [
                        'prompt' => $prompt,
                        'n' => 1,
                        'size' => '1024x1024',
                        'quality' => 'standard',
                        'style' => 'vivid'
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                $imageUrl = $data['data'][0]['url'] ?? null;

                if ($imageUrl) {
                    // Download the image and store it
                    $imageContent = Http::get($imageUrl)->body();
                    $filename = 'generated-covers/' . uniqid() . '.png';
                    Storage::disk('public')->put($filename, $imageContent);

                    return response()->json([
                        'success' => true,
                        'image_url' => asset('storage/' . $filename),
                        'image_path' => $filename
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to generate image. Please try again.'
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate image. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the book cover.'
            ], 500);
        }
    }
}