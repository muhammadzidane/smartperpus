<?php

use App\Http\Controllers\
{
    BookController, AuthorController, TestController
};

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\{Author, Book, BookCategorys};

// TEST

Route::get('/test', array(TestController::class, 'test'));

Route::resource('/authors', AuthorController::class);
Route::resource('/books', BookController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::fallback(function($wkwk) {
    return view('not-found-route');
});
