<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Book;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        if (!$user) return;

        $books = Book::all();
        $reviews = [
            [
                'review_text' => 'Absolutely loved this book! Highly recommended.',
                'sentiment' => 'positive',
                'overall_rating' => 5,
            ],
            [
                'review_text' => 'Not my cup of tea, but well written.',
                'sentiment' => 'neutral',
                'overall_rating' => 3,
            ],
            [
                'review_text' => 'Terrible, I could not finish it.',
                'sentiment' => 'negative',
                'overall_rating' => 1,
            ],
        ];
        $i = 0;
        foreach ($books as $book) {
            $data = $reviews[$i % count($reviews)];
            Review::firstOrCreate([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ], [
                'review_text' => $data['review_text'],
                'sentiment' => $data['sentiment'],
                'overall_rating' => $data['overall_rating'],
                'content_rating' => $data['overall_rating'],
                'condition_rating' => $data['overall_rating'],
                'recommendation_level' => $data['overall_rating'],
                'difficulty_level' => 3,
                'review_title' => $book->title . ' Review',
                'reading_context' => 'General',
                'is_spoiler' => false,
                'content_warnings' => null,
                'photo_urls' => [],
            ]);
            $i++;
        }
    }
}
