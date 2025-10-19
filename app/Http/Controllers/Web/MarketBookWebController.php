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
                'Content-Type' => 'application/json',
            ])->post(config('services.gemini.text_endpoint') . '?key=' . config('services.gemini.api_key'), [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'maxOutputTokens' => 200,
                            'temperature' => 0.7,
                        ],
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                $description = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

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
     * Generate AI-powered book cover image using Gemini.
     */
    public function generateBookCover(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate a unique placeholder cover using colors and book details
            // Note: Imagen 3 API integration pending availability
            // For now, we generate a professional-looking placeholder with gradient colors

            $seed = crc32($request->title . $request->author);
            $colors = [
                ['#FF6B6B', '#FFE66D'], // Red to Yellow
                ['#4ECDC4', '#44A08D'], // Teal to Green
                ['#95E1D3', '#F38181'], // Mint to Pink
                ['#AA96DA', '#FCBAD3'], // Purple to Pink
                ['#A8DADC', '#457B9D'], // Light Blue to Navy
                ['#F1FAEE', '#A8DADC'], // Cream to Light Blue
                ['#E63946', '#F77F00'], // Red to Orange
                ['#06A77D', '#EEC258'], // Green to Gold
            ];

            $colorPair = $colors[$seed % count($colors)];

            // Create a unique ID for this cover
            $coverId = uniqid();

            // Generate a data URL with an SVG placeholder
            $svgContent = sprintf('
                <svg width="300" height="400" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="grad%s" x1="0%%" y1="0%%" x2="100%%" y2="100%%">
                            <stop offset="0%%" style="stop-color:%s;stop-opacity:1" />
                            <stop offset="100%%" style="stop-color:%s;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect width="300" height="400" fill="url(#grad%s)" />
                    <text x="150" y="180" font-size="28" font-weight="bold" fill="white" text-anchor="middle" word-spacing="10">
                        %s
                    </text>
                    <text x="150" y="320" font-size="16" fill="rgba(255,255,255,0.8)" text-anchor="middle">
                        by %s
                    </text>
                </svg>
            ',
                $coverId,
                $colorPair[0],
                $colorPair[1],
                $coverId,
                htmlspecialchars(substr($request->title, 0, 30)),
                htmlspecialchars(substr($request->author, 0, 25))
            );

            // Save the SVG as a file
            $filename = 'generated-covers/' . $coverId . '.svg';
            Storage::disk('public')->put($filename, $svgContent);

            return response()->json([
                'success' => true,
                'image_url' => asset('storage/' . $filename),
                'image_path' => $filename,
                'note' => 'This is a placeholder cover design. For AI-generated covers with Imagen 3, please check back soon.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the book cover: ' . $e->getMessage()
            ], 500);
        }
    }
}