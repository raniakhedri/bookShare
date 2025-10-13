<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontoffice\ReviewController;
use App\Http\Controllers\Frontoffice\ReviewInteractionController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// API routes for reviews and interactions
Route::middleware(['auth:sanctum'])->group(function () {
    // Review API routes
    Route::apiResource('reviews', ReviewController::class);
    Route::get('books/{book}/reviews', [ReviewController::class, 'bookReviews']);
    Route::get('my-reviews', [ReviewController::class, 'myReviews']);
    
    // Interaction API routes
    Route::post('reviews/{review}/interactions', [ReviewInteractionController::class, 'store']);
    Route::put('interactions/{interaction}', [ReviewInteractionController::class, 'update']);
    Route::delete('interactions/{interaction}', [ReviewInteractionController::class, 'destroy']);
    Route::get('reviews/{review}/discussions', [ReviewInteractionController::class, 'discussions']);
    Route::get('reviews/{review}/vote-stats', [ReviewInteractionController::class, 'voteStats']);
    Route::post('interactions/{interaction}/report', [ReviewInteractionController::class, 'report']);
    Route::get('my-interactions', [ReviewInteractionController::class, 'myInteractions']);
    Route::get('my-bookmarks', [ReviewInteractionController::class, 'bookmarks']);
});