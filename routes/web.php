<?php

use App\Http\Controllers\Backoffice\ChangePasswordController;
use App\Http\Controllers\Backoffice\InfoUserController;
use App\Http\Controllers\Backoffice\RegisterController;
use App\Http\Controllers\Backoffice\ResetController;
use App\Http\Controllers\Backoffice\SessionsController;
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
	return view('frontoffice.home');
})->name('home');

Route::get('/book', function () {
	return view('frontoffice.book');
})->name('book');

Route::get('/notes', function () {
	return view('frontoffice.notes');
})->name('notes');

Route::get('/groups', function () {
	return view('frontoffice.groups');
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

// Admin session routes (without prefix for backward compatibility)
Route::group(['middleware' => 'guest'], function () {
	Route::get('/session', [SessionsController::class, 'create'])->name('session');
	Route::post('/session', [SessionsController::class, 'store'])->name('session.store');
	Route::get('/login', [SessionsController::class, 'create'])->name('login');
	Route::get('/register', [RegisterController::class, 'create'])->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// BACKOFFICE ROUTES (Admin Panel - Authentication Required)
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

	Route::get('dashboard', function () {
		return view('backoffice.dashboard');
	})->name('admin.dashboard');

	// Frontoffice pages in admin area
	Route::get('book', function () {
		return view('backoffice.frontoffice.book');
	})->name('admin.book');

	Route::get('notes', function () {
		return view('backoffice.frontoffice.notes');
	})->name('admin.notes');

	Route::get('groups', function () {
		return view('backoffice.frontoffice.groups');
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

	Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
});

// BACKOFFICE AUTHENTICATION ROUTES (Guest users)
Route::group(['prefix' => 'admin', 'middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/admin/login', function () {
	return view('backoffice.session.login-session');
})->name('admin.login');