<?php

use App\Http\Controllers\
{
    AjaxController,BookController, AuthorController, CategoryController,
    HomeController, TestController, AccountController,
};

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\{Route, Auth};

Route::get('/', array(HomeController::class, 'index'))->name('home');

// TEST
Route::get('/test', array(TestController::class, 'test'))->name('test');
Route::get('/pagination', array(TestController::class, 'pagination'));

// Search
Route::get('/search/books', array(BookController::class, 'searchBooks'))->name('search.books');

Route::resource('/authors', AuthorController::class);

// My Account
Route::get('/account', array(AccountController::class, 'index'));
Route::prefix('/account')->group(function() {
    Route::get('/transaction-lists', array(AccountController::class, 'transactionLists'))->name('transaction.lists');
    Route::get('/my-reviews', array(AccountController::class, 'myReviews'))->name('my.reviews');
    Route::get('/waiting-for-payments', array(AccountController::class, 'waitingForPayments'))->name('waiting.for.payment');
    Route::get('/chat', array(AccountController::class, 'chat'))->name('chat');
});

// Books
Route::get('/books/buy/{book}', array(BookController::class, 'booksBuy'))->name('books.buy');
Route::get('/books/payment/{book}', array(BookController::class, 'booksPayment'))->name('books.payment');
Route::get('/books/shopping-cart/', array(BookController::class, 'shoppingCart'))->name('shopping.cart');
Route::get('/books/wishlist/', array(BookController::class, 'wishlist'));
Route::resource('/books', BookController::class);

// Test Ajax
Route::post('/getmsg', array(TestController::class, 'index'))->name('getmsg');

// Ajax
Route::prefix('/ajax/request/')->group(function() {
    Route::post('cek-ongkir', array(AjaxController::class, 'cekOngkir'));
    Route::post('first-load', array(AjaxController::class, 'firstLoad'));
    Route::post('store', array(AjaxController::class, 'ajaxRequestStore'))->name('ajax.request.store');
    Route::post('check-login', array(AjaxController::class, 'checkLogin'));
    Route::post('register', array(AjaxController::class, 'register'));
    Route::post('search', array(AjaxController::class, 'search'));
    Route::post('pagination', array(AjaxController::class, 'pagination'));
    Route::post('pagination-data', array(AjaxController::class, 'paginationData'));
    Route::get('responsive-filters', array(AjaxController::class, 'responsiveFilters'));

    // Filter
    Route::post('filter-search', array(AjaxController::class, 'filterSearch'));
    Route::post('filter-star', array(AjaxController::class, 'filterStar'));
    Route::post('sort-books', array(AjaxController::class, 'sortBooks'));
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
