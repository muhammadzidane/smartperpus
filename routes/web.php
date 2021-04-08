<?php

use App\Http\Controllers\
{
    AjaxController,
    BookController, AuthorController, CategoryController, HomeController, TestController
};

use Illuminate\Support\Facades\{Route, Auth};
use App\Models\{Author, Book, Category, BookCategorys};
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;

Route::get('/', array(HomeController::class, 'index'))->name('home');

// TEST
Route::get('/test', array(TestController::class, 'test'));
Route::get('/pagination', array(TestController::class, 'pagination'));

Route::resource('/authors', AuthorController::class);
Route::resource('/books', BookController::class);

// Test Ajax
Route::post('/getmsg', array(TestController::class, 'index'))->name('getmsg');

// Ajax
Route::prefix('ajax')->group(function() {
    Route::post('request/check-login', array(AjaxController::class, 'checkLogin'));
    Route::post('request/store', array(AjaxController::class, 'ajaxRequestStore'))->name('ajax.request.store');
});

Auth::routes();

Route::fallback(function($wkwk) {
    $faker = \Faker\Factory::create('id_ID');

    dump($faker->lastName);
});

// Categories Route
Route::get('/categories/{category}', array(CategoryController::class, 'index'))->name('categories');
