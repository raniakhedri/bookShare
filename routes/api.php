<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketBookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\BookAvailabilityController;
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

// Public API Routes for Browser Extension (No Authentication Required)
Route::prefix('public')->group(function () {
    // Check book availability in marketplace
    Route::get('books/check-availability', [BookAvailabilityController::class, 'checkAvailability']);
    // Get book details
    Route::get('books/{id}', [BookAvailabilityController::class, 'getBook']);
});

// Marketplace API Routes
Route::middleware('auth:sanctum')->group(function () {

    // Market Books Routes
    Route::apiResource('marketbooks', MarketBookController::class);
    Route::get('marketbooks/my/books', [MarketBookController::class, 'myBooks']);
    Route::patch('marketbooks/{marketBook}/toggle-availability', [MarketBookController::class, 'toggleAvailability']);

    // Transactions Routes
    Route::apiResource('transactions', TransactionController::class)->except(['destroy']);
    Route::get('transactions/my/requests', [TransactionController::class, 'myRequests']);
    Route::get('transactions/my/received', [TransactionController::class, 'requestsForMyBooks']);
    Route::patch('transactions/{transaction}/complete', [TransactionController::class, 'markCompleted']);

    // Exchange Requests Routes
    Route::apiResource('exchange-requests', ExchangeRequestController::class)->except(['store']);
    Route::get('exchange-requests/my/offered', [ExchangeRequestController::class, 'myOfferedBooks']);
    Route::get('exchange-requests/my/requests', [ExchangeRequestController::class, 'myExchangeRequests']);

});

// Admin Routes (require admin role)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Admin Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::get('system-health', [AdminController::class, 'systemHealth']);

    // Admin User Management
    Route::apiResource('users', AdminUserController::class);
    Route::get('users/statistics', [AdminUserController::class, 'statistics']);
    Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus']);

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

// Audio Book API Routes
Route::middleware(['auth:web'])->prefix('audiobook')->group(function () {
    // Extraction de texte PDF pour lecture audio
    Route::get('books/{book}/extract-text', [App\Http\Controllers\Api\AudioBookController::class, 'extractText']);

    // Informations PDF
    Route::get('books/{book}/pdf-info', [App\Http\Controllers\Api\AudioBookController::class, 'getPdfInfo']);

    // Gestion des positions de lecture
    Route::post('books/{book}/reading-position', [App\Http\Controllers\Api\AudioBookController::class, 'saveReadingPosition']);
    Route::get('books/{book}/reading-position', [App\Http\Controllers\Api\AudioBookController::class, 'getReadingPosition']);
});

// Routes publiques pour l'audio (sans authentification)
Route::prefix('public/audiobook')->group(function () {
    // Extraction de texte pour les livres publics
    Route::get('books/{book}/extract-text', [App\Http\Controllers\Api\AudioBookController::class, 'extractText'])
        ->where('book', '[0-9]+');

    // Informations PDF publiques
    Route::get('books/{book}/pdf-info', [App\Http\Controllers\Api\AudioBookController::class, 'getPdfInfo'])
        ->where('book', '[0-9]+');
});

// AI Recommendation API Routes
Route::middleware('auth:web')->prefix('ai')->group(function () {
    // Obtenir les recommandations personnalisées
    Route::get('recommendations', [App\Http\Controllers\AIRecommendationController::class, 'getRecommendations']);

    // Recommandations basées sur description textuelle
    Route::post('book-recommendations', [App\Http\Controllers\AIRecommendationController::class, 'getBookRecommendations']);

    // Statistiques de recherche IA
    Route::get('search-stats', [App\Http\Controllers\AIRecommendationController::class, 'getSearchStats']);

    // Enregistrer une interaction utilisateur
    Route::post('interaction', [App\Http\Controllers\AIRecommendationController::class, 'recordInteraction']);

    // Feedback sur une recommandation
    Route::post('feedback', [App\Http\Controllers\AIRecommendationController::class, 'feedbackRecommendation']);

    // Gestion des préférences utilisateur
    Route::get('preferences', [App\Http\Controllers\AIRecommendationController::class, 'getUserPreferences']);
    Route::post('preferences', [App\Http\Controllers\AIRecommendationController::class, 'updatePreference']);

    // Statistiques d'interaction
    Route::get('stats', [App\Http\Controllers\AIRecommendationController::class, 'getInteractionStats']);
});