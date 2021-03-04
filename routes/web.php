<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\{Author, Book, BookCategorys};

// TEST

Route::get('/test', array(TestController::class, 'test'))->middleware('auth');
Route::resource('/books', BookController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
