<?php

use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('home');
})->name('home');

// New simplified navigation pages
Route::get('/book', function () {
    return view('book');
})->name('book');

Route::get('/notes', function () {
    return view('notes');
})->name('notes');

Route::get('/groups', function () {
    return view('groups');
})->name('groups');

Route::get('/marketplace', function () {
    return view('marketplace');
})->name('marketplace');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

Route::get('/community', function () {
    return view('community');
})->name('community');
