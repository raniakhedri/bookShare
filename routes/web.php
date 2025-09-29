<?php

use App\Http\Controllers\Backoffice\ChangePasswordController;
use App\Http\Controllers\Backoffice\InfoUserController;
use App\Http\Controllers\Backoffice\RegisterController;
use App\Http\Controllers\Backoffice\ResetController;
use App\Http\Controllers\Backoffice\SessionsController;
use App\Http\Controllers\Web\MarketplaceController;
use App\Http\Controllers\Web\MarketBookWebController;
use App\Http\Controllers\Web\TransactionWebController;
use App\Http\Controllers\Web\AdminMarketplaceController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
	return redirect()->route('admin.login');
});

// MARKETPLACE ROUTES
// Main marketplace index route (integrated with existing structure)  
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');

Route::middleware('auth')->prefix('marketplace')->group(function () {
	// Other marketplace pages
	Route::get('/browse', [MarketplaceController::class, 'browse'])->name('marketplace.browse');
	Route::get('/my-books', [MarketplaceController::class, 'myBooks'])->name('marketplace.my-books');
	Route::get('/my-requests', [MarketplaceController::class, 'myRequests'])->name('marketplace.my-requests');
	Route::get('/received-requests', [MarketplaceController::class, 'receivedRequests'])->name('marketplace.received-requests');

	// Market book management
	Route::resource('books', MarketBookWebController::class)->names([
		'index' => 'marketplace.books.index',
		'create' => 'marketplace.books.create',
		'store' => 'marketplace.books.store',
		'show' => 'marketplace.books.show',
		'edit' => 'marketplace.books.edit',
		'update' => 'marketplace.books.update',
		'destroy' => 'marketplace.books.destroy',
	]);
	Route::patch('books/{book}/toggle-availability', [MarketBookWebController::class, 'toggleAvailability'])->name('marketplace.books.toggle-availability');

	// Transaction management
	Route::get('books/{book}/request', [TransactionWebController::class, 'create'])->name('marketplace.transactions.create');
	Route::post('transactions', [TransactionWebController::class, 'store'])->name('marketplace.transactions.store');
	Route::get('transactions/{transaction}', [TransactionWebController::class, 'show'])->name('marketplace.transactions.show');
	Route::patch('transactions/{transaction}/respond', [TransactionWebController::class, 'respond'])->name('marketplace.transactions.respond');
	Route::patch('transactions/{transaction}/complete', [TransactionWebController::class, 'complete'])->name('marketplace.transactions.complete');
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
	$recentMembers = $group->users->sortByDesc(function ($user) {
		return $user->pivot->created_at ?? $user->created_at;
	})->take(8);
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
		'content' => 'nullable|string|max:1000',
		'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,pdf|max:8192',
	]);
	$group = \App\Models\Group::findOrFail($id);
	$user = auth()->user();
	if (!$user) {
		return redirect()->route('login');
	}
	$data = [
		'group_id' => $group->id,
		'user_id' => $user->id,
		'content' => $request->input('content') ?? '',
	];
	if ($request->hasFile('file')) {
		$data['file'] = $request->file('file')->store('posts', 'public');
	}
	\App\Models\Post::create($data);
	return redirect()->route('frontoffice.group.wall', $id)->with('success', 'Post created successfully!');
})->name('frontoffice.group.wall.post');
Route::get('/notes', function () {
	return view('frontoffice.notes');
})->name('notes');

Route::get('/groups', function () {
	$groups = \App\Models\Group::with('users')->get();
	return view('frontoffice.groups', compact('groups'));
})->name('groups');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');

// Marketplace functionality routes (integrated with existing structure)
Route::middleware('auth')->prefix('marketplace')->name('marketplace.')->group(function () {
	Route::get('/browse', [MarketplaceController::class, 'browse'])->name('browse');
	Route::get('/my-books', [MarketplaceController::class, 'myBooks'])->name('my-books');
	Route::get('/my-requests', [MarketplaceController::class, 'myRequests'])->name('my-requests');
	Route::get('/received-requests', [MarketplaceController::class, 'receivedRequests'])->name('received-requests');

	// Book management
	Route::resource('books', MarketBookWebController::class);
	Route::patch('books/{book}/toggle-availability', [MarketBookWebController::class, 'toggleAvailability'])->name('books.toggle-availability');

	// Transaction management
	Route::get('books/{book}/request', [TransactionWebController::class, 'create'])->name('transactions.create');
	Route::post('transactions', [TransactionWebController::class, 'store'])->name('transactions.store');
	Route::get('transactions/{transaction}', [TransactionWebController::class, 'show'])->name('transactions.show');
	Route::patch('transactions/{transaction}/respond', [TransactionWebController::class, 'respond'])->name('transactions.respond');
	Route::patch('transactions/{transaction}/complete', [TransactionWebController::class, 'complete'])->name('transactions.complete');
});

Route::get('/blog', function () {
	return view('frontoffice.blog');
})->name('blog');

Route::get('/community', function () {
	return view('frontoffice.community');
})->name('community');

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
		'content' => 'nullable|string|max:1000',
		'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
	]);
	if (!$request->filled('content') && !$request->hasFile('file')) {
		return redirect()->back()->withErrors(['content' => 'Vous devez écrire un commentaire ou joindre un fichier.']);
	}
	$user = auth()->user();
	if (!$user) {
		return redirect()->route('login');
	}
	$comment = new \App\Models\Comment();
	$comment->post_id = $postId;
	$comment->user_id = $user->id;
	$comment->content = $request->input('content') ?? '';
	if ($request->hasFile('file')) {
		$comment->file = $request->file('file')->store('comments', 'public');
	}
	$comment->save();
	return redirect()->back()->with('success', 'Comment added!');
})->name('frontoffice.group.comment');


// Home page for client role
Route::get('/home', function () {
	return view('frontoffice.home');
})->name('home');
// BACKOFFICE ROUTES (Admin Panel - Authentication Required)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
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

	Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

	// Admin marketplace management routes
	Route::prefix('marketplace')->name('marketplace.')->group(function () {
		Route::get('dashboard', [AdminController::class, 'marketplaceDashboard'])->name('admin.dashboard');
		Route::resource('users', UserController::class);
	});

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

	Route::get('marketplace', [AdminMarketplaceController::class, 'index'])->name('admin.marketplace');
	Route::delete('marketplace/book/{id}', [AdminMarketplaceController::class, 'destroy'])->name('admin.marketplace.book.delete');

	// Debug route to test admin access
	Route::get('debug-auth', function () {
		$user = auth()->user();
		return response()->json([
			'authenticated' => auth()->check(),
			'user' => $user ? [
				'id' => $user->id,
				'name' => $user->name,
				'email' => $user->email,
				'role' => $user->role,
				'isAdmin' => $user->isAdmin(),
			] : null,
		]);
	})->name('admin.debug-auth');

	// Moved to admin marketplace group
	// Route::get('marketplace', function () {
	// 	return view('backoffice.frontoffice.marketplace');
	// })->name('admin.marketplace');

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

