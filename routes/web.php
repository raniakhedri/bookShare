

<?php

use App\Http\Controllers\Backoffice\ChangePasswordController;
use App\Http\Controllers\Backoffice\InfoUserController;
use App\Http\Controllers\Backoffice\RegisterController;
use App\Http\Controllers\Backoffice\ResetController;
use App\Http\Controllers\Backoffice\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\BookController;
use App\Http\Controllers\Frontoffice\JournalController;
use App\Http\Controllers\Frontoffice\NoteController;
use App\Http\Controllers\Frontoffice\ReviewController;
use App\Http\Controllers\Frontoffice\ReviewInteractionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// FRONTOFFICE ROUTES (BookShare - Public User Interface)
// Redirect root to admin login
Route::get('/', function () {
	return redirect()->route('admin.login');
});


Route::get('/book', function () {
	$books = \App\Models\Book::with('category')->paginate(12);
	$categories = \App\Models\Category::all();
	return view('frontoffice.book', compact('books', 'categories'));
})->name('book');

// Détail d'un livre avec lecture PDF
Route::get('/livre/{id}', function ($id) {
	$book = \App\Models\Book::with('category')->findOrFail($id);
	return view('frontoffice.book_show', compact('book'));
})->name('frontoffice.book.show');

Route::get('/groups/{id}/wall', function ($id) {
	$group = \App\Models\Group::with(['users', 'creator', 'posts.user', 'posts.comments.user'])->findOrFail($id);
	$user = auth()->user();
	$memberCount = $group->users->count();
	$recentMembers = $group->users->sortByDesc(function($user) { return $user->pivot->created_at ?? $user->created_at; })->take(8);
	$posts = $group->posts()->latest()->get();
	return view('frontoffice.group_wall', compact('group', 'memberCount', 'recentMembers', 'posts'));
})->name('frontoffice.group.wall');

// // Accept or refuse group member and redirect to groups page
// Route::get('/admin/groups/accept/{groupId}/{userId}', function ($groupId, $userId) {
// 	$group = \App\Models\Group::findOrFail($groupId);
// 	$user = \App\Models\User::findOrFail($userId);
// 	$group->users()->updateExistingPivot($userId, ['status' => 'accepted']);
// 	return redirect()->route('admin.groups')->with('success', 'User accepted into group!');
// });


// POST routes for accepting/refusing group members
Route::post('/admin/groups/accept/{groupId}/{userId}', function ($groupId, $userId) {
	$group = \App\Models\Group::findOrFail($groupId);
	$user = \App\Models\User::findOrFail($userId);
	$group->users()->updateExistingPivot($userId, ['status' => 'accepted']);
	return redirect()->route('admin.groups')->with('success', 'User accepted into group!');
});

Route::post('/admin/groups/refuse/{groupId}/{userId}', function ($groupId, $userId) {
	$group = \App\Models\Group::findOrFail($groupId);
	$user = \App\Models\User::findOrFail($userId);
	$group->users()->updateExistingPivot($userId, ['status' => 'refused']);
	return redirect()->route('admin.groups')->with('success', 'User refused from group!');
});

Route::get('/admin/groups/refuse/{groupId}/{userId}', function ($groupId, $userId) {
	$group = \App\Models\Group::findOrFail($groupId);
	$user = \App\Models\User::findOrFail($userId);
	$group->users()->updateExistingPivot($userId, ['status' => 'refused']);
	return redirect()->route('admin.groups')->with('success', 'User refused from group!');
});

Route::post('/groups/{id}/join', function ($id) {
	// Example logic: attach user to group with 'pending' status
	$group = \App\Models\Group::findOrFail($id);
	if (auth()->check()) {
		$group->users()->syncWithoutDetaching([
			auth()->id() => ['status' => 'pending']
		]);
		return redirect()->back()->with('success', 'Join request sent!');
	}
	return redirect()->route('login');
})->name('groups.join');

Route::post('/groups/{id}/wall', function ($id, Request $request) {
	// Handle post creation logic here
	$request->validate([
		'content' => 'required|string|max:1000',
	]);
	$group = \App\Models\Group::findOrFail($id);
	$user = auth()->user();
	if (!$user) {
		return redirect()->route('login');
	}
	\App\Models\Post::create([
		'group_id' => $group->id,
		'user_id' => $user->id,
		'content' => $request->input('content'),
	]);
	return redirect()->route('frontoffice.group.wall', $id)->with('success', 'Post created successfully!');
})->name('frontoffice.group.wall.post');
Route::get('/notes', function () {
	return view('frontoffice.notes');
})->name('notes');

Route::get('/groups', function () {
	$groups = \App\Models\Group::with('users')->get();
	return view('frontoffice.groups', compact('groups'));
})->name('groups');

Route::get('/marketplace', function () {
	return view('frontoffice.marketplace');
})->name('marketplace');

Route::get('/blog', function () {
	return view('frontoffice.blog');
})->name('blog');

Route::get('/community', function () {
	return view('frontoffice.community');
})->name('community');

// Livres
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}/add-to-journal', [BookController::class, 'addToJournalForm'])->name('books.add-to-journal');
Route::post('/books/{book}/store-in-journal', [BookController::class, 'storeInJournal'])->name('books.store-in-journal');
Route::get('/journals/{journal}/books/{book}', [BookController::class, 'show'])->name('books.show');

// Journaux
Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
Route::get('/journals/create', [JournalController::class, 'create'])->name('journals.create');
Route::post('/journals', [JournalController::class, 'store'])->name('journals.store');
Route::get('/journals/{journal}', [JournalController::class, 'show'])->name('journals.show');
Route::get('/journals/{journal}/edit', [JournalController::class, 'edit'])->name('journals.edit');
Route::put('/journals/{journal}', [JournalController::class, 'update'])->name('journals.update');
Route::get('/journals/{journal}/archived', [JournalController::class, 'showArchived'])->name('journals.archived');
Route::patch('/journals/{journal}/books/{book}/unarchive', [JournalController::class, 'unarchiveBook'])->name('journals.unarchive-book');
Route::delete('/journals/{journal}/books/{book}/detach', [JournalController::class, 'detachBook'])->name('journals.detach-book');
Route::patch('/journals/{journal}/books/{book}/archive', [JournalController::class, 'archiveBook'])->name('journals.archive-book'); 

// Notes
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');



// Admin session routes (without prefix for backward compatibility)
Route::group(['middleware' => 'guest'], function () {
	Route::get('/session', [SessionsController::class, 'create'])->name('session');
	Route::post('/session', function (Illuminate\Http\Request $request) {
		// Auth logic here
		$credentials = $request->only('email', 'password');
		if (auth()->attempt($credentials)) {
			$user = auth()->user();
			if ($user->role === 'admin') {
				return redirect()->route('admin.dashboard');
			} else {
				return redirect()->route('home');
			}
		}
		return back()->withErrors(['email' => 'Invalid credentials']);
	})->name('session.store');
	Route::get('/login', [SessionsController::class, 'create'])->name('login');
	Route::get('/register', [RegisterController::class, 'create'])->name('register');
	Route::post('/register', function (Illuminate\Http\Request $request) {
		// Registration logic here
		$data = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:6|confirmed',
			'role' => 'required|string',
		]);
		$user = \App\Models\User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'role' => $data['role'],
		]);
		auth()->login($user);
		if ($user->role === 'admin') {
			return redirect()->route('admin.dashboard');
		} else {
			return redirect()->route('welcome');
		}
	})->name('register.store');
});

// Route for posting a comment on a group wall post
Route::post('/groups/{group}/wall/{post}/comment', function ($groupId, $postId, Illuminate\Http\Request $request) {
	$request->validate([
		'content' => 'required|string|max:1000',
	]);
	$user = auth()->user();
	if (!$user) {
		return redirect()->route('login');
	}
	$comment = new \App\Models\Comment();
	$comment->post_id = $postId;
	$comment->user_id = $user->id;
	$comment->content = $request->input('content');
	// Optionally handle file upload here
	$comment->save();
	return redirect()->back()->with('success', 'Comment added!');
})->name('frontoffice.group.comment');


// Home page for client role
Route::get('/home', function () {
	return view('frontoffice.home');
})->name('home');
// BACKOFFICE ROUTES (Admin Panel - Authentication Required)
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
	// Update group (Backoffice)
Route::put('groups/update/{id}', function ($id, Illuminate\Http\Request $request) {
	$group = \App\Models\Group::findOrFail($id);
	$data = $request->validate([
		'name' => 'required|string|max:255',
		'theme' => 'required|string|max:255',
		'description' => 'nullable|string',
		'image' => 'nullable|image|max:2048',
	]);

	if ($request->hasFile('image')) {
		$data['image'] = $request->file('image')->store('groups', 'public');
	}

	$group->update($data);
	return redirect()->route('admin.groups')->with('success', 'Group updated successfully!');
})->name('admin.groups.update');
	// Edit group (Backoffice)
	Route::get('groups/editGroup/{id}', function ($id) {
		$group = \App\Models\Group::findOrFail($id);
		return view('backoffice.groups.editGroup', compact('group'));
	})->name('admin.groups.edit');
	// Store group (Backoffice)
	Route::post('groups', function (Illuminate\Http\Request $request) {
		$data = $request->validate([
			'name' => 'required|string|max:255',
			'theme' => 'required|string|max:255',
			'description' => 'nullable|string',
			'image' => 'nullable|image|max:2048',
		]);

		if ($request->hasFile('image')) {
			$data['image'] = $request->file('image')->store('groups', 'public');
		}

		$data['creator_id'] = auth()->id();
        
		\App\Models\Group::create($data);
	return redirect()->route('admin.groups')->with('success', 'Group created successfully!');
	})->name('admin.groups.store');
	// Delete group (Backoffice)
	Route::delete('groups/{group}', function ($groupId) {
		$group = \App\Models\Group::findOrFail($groupId);
		$group->delete();
	return redirect()->route('admin.groups')->with('success', 'Group deleted successfully!');
	})->name('admin.groups.destroy');
	// CRUD Books (Backoffice)
	Route::resource('books', App\Http\Controllers\Backoffice\BookController::class);
	// CRUD Categories (Backoffice)
	Route::resource('categories', App\Http\Controllers\Backoffice\CategoryController::class);

	Route::get('dashboard', function () {
		return view('backoffice.dashboard');
	})->name('admin.dashboard');

	// Frontoffice pages in admin area
	Route::get('book', function () {
		$books = \App\Models\Book::all();
		$categories = \App\Models\Category::all();
		return view('backoffice.frontoffice.book', compact('books', 'categories'));
	})->name('admin.book');
Route::get('groups/create', function () {
	return view('backoffice.groups.create');
})->name('admin.groups.create');
	Route::get('notes', function () {
		return view('backoffice.frontoffice.notes');
	})->name('admin.notes');

	Route::get('groups', function () {
		$groups = \App\Models\Group::withCount('users')->get();
		$totalMembers = $groups->sum('users_count');
		$averageMembers = $groups->count() > 0 ? round($totalMembers / $groups->count(), 1) : 0;
		$themes = $groups->pluck('theme')->unique()->values();
		return view('backoffice.frontoffice.groups', compact('groups', 'totalMembers', 'averageMembers', 'themes'));
	})->name('admin.groups');


	Route::get('marketplace', function () {
		return view('backoffice.frontoffice.marketplace');
	})->name('admin.marketplace');

	Route::get('blog', function () {
		return view('backoffice.frontoffice.blog');
	})->name('admin.blog');

	Route::get('community', function () {
		return view('backoffice.frontoffice.community');
	})->name('admin.community');

	Route::get('billing', function () {
		return view('backoffice.billing');
	})->name('admin.billing');

	Route::get('profile', function () {
		return view('backoffice.profile');
	})->name('admin.profile');

	Route::get('rtl', function () {
		return view('backoffice.rtl');
	})->name('admin.rtl');

	Route::get('user-management', function () {
		return view('laravel-examples.user-management');
	})->name('admin.user-management');

	Route::get('tables', function () {
		return view('backoffice.tables');
	})->name('admin.tables');

	Route::get('virtual-reality', function () {
		return view('backoffice.virtual-reality');
	})->name('admin.virtual-reality');

	Route::get('static-sign-in', function () {
		return view('backoffice.static-sign-in');
	})->name('admin.sign-in');

	Route::get('static-sign-up', function () {
		return view('backoffice.static-sign-up');
	})->name('admin.sign-up');

// Global logout route for all users (frontoffice and backoffice)
Route::get('/logout', [App\Http\Controllers\Backoffice\SessionsController::class, 'destroy'])->name('logout');
	Route::post('/user-profile', [InfoUserController::class, 'store']);
});

// Global POST logout route for all users (frontoffice and backoffice)
Route::post('/logout', [App\Http\Controllers\Backoffice\SessionsController::class, 'destroy'])->name('logout.post');
// BACKOFFICE AUTHENTICATION ROUTES (Guest users)
Route::group(['prefix' => 'admin', 'middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create'])->name('admin.login');
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Review routes
// Public routes - Anyone can view
Route::get('/books/{book}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
Route::get('/reviews/{review}/vote-stats', [ReviewInteractionController::class, 'voteStats'])->name('interactions.vote-stats');

// Protected routes - Require authentication
Route::middleware('auth')->group(function () {
    // Review management
    Route::get('/books/{book}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my-reviews');
    
    // Interaction management - GET routes first
    Route::get('/reviews/{review}/discussions', [ReviewInteractionController::class, 'discussions'])->name('interactions.discussions');
    Route::get('/my-interactions', [ReviewInteractionController::class, 'myInteractions'])->name('interactions.my-interactions');
    Route::get('/my-bookmarks', [ReviewInteractionController::class, 'bookmarks'])->name('interactions.bookmarks');
    
    // Interaction CRUD - POST/PUT/DELETE routes
    Route::post('/reviews/{review}/interactions', [ReviewInteractionController::class, 'store'])->name('interactions.store');
    Route::put('/interactions/{interaction}', [ReviewInteractionController::class, 'update'])->name('interactions.update');
    Route::delete('/interactions/{interaction}', [ReviewInteractionController::class, 'destroy'])->name('interactions.destroy');
    Route::post('/interactions/{interaction}/report', [ReviewInteractionController::class, 'report'])->name('interactions.report');
});