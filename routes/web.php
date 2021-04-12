<?php

use App\Http\Controllers\
{
    AjaxController,BookController, AuthorController, CategoryController,
    HomeController, TestController,
};

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\{Route, Auth};

Route::get('/', array(HomeController::class, 'index'))->name('home');

// TEST
Route::get('/test', array(TestController::class, 'test'));
Route::get('/pagination', array(TestController::class, 'pagination'));

// Search
Route::get('/search/books', array(BookController::class, 'searchBooks'))->name('search.books');

Route::resource('/authors', AuthorController::class);
Route::resource('/books', BookController::class);

// Test Ajax
Route::post('/getmsg', array(TestController::class, 'index'))->name('getmsg');

// Ajax
Route::prefix('/ajax/request/')->group(function() {
    Route::post('store', array(AjaxController::class, 'ajaxRequestStore'))->name('ajax.request.store');
    Route::post('check-login', array(AjaxController::class, 'checkLogin'));
    Route::post('register', array(AjaxController::class, 'register'));
    Route::post('min-max-price', array(AjaxController::class, 'minMaxPrice'));
});

Auth::routes();

Route::fallback(function($wkwk) {
    $faker = \Faker\Factory::create('id_ID');

    dump($faker->lastName);
});

// Categories Route
Route::get('/categories/{category}', array(CategoryController::class, 'index'))->name('categories');

// Reset Password
Route::get('/forgot-password', array(ForgotPasswordController::class, 'showLinkRequestForm'))->name('forgot.password');
