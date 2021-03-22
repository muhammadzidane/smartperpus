<?php

use App\Http\Controllers\
{
    BookController, AuthorController, CategoryController, HomeController, TestController
};

use Illuminate\Support\Facades\{Route, Auth};
use App\Models\{Author, Book, Category, BookCategorys};

Route::get('/', array(HomeController::class, 'index'));

// TEST
Route::get('/test', array(TestController::class, 'test'));

Route::resource('/authors', AuthorController::class);
Route::resource('/books', BookController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::fallback(function($wkwk) {
    $faker = \Faker\Factory::create('id_ID');

    dump($faker->lastName);
});

// Categories Route
Route::get('/categories/{category}', array(CategoryController::class, 'index'))->name('categories');
