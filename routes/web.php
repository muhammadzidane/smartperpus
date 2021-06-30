<?php

use App\Http\Controllers\
{
    AjaxController,BookController, AuthorController, CategoryController,
    HomeController, TestController, BookPurchaseController,
    CityController, CustomerController, UserController, ProvinceController,
    UserChatController,
};

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\{Route, Auth};

Route::get('/', array(HomeController::class, 'index'))->name('home');

// TEST
Route::get('/test', array(TestController::class, 'test'))->name('test');
Route::post('/test', array(TestController::class, 'testPost'))->name('test.post');
Route::get('/pagination', array(TestController::class, 'pagination'));

// User
Route::prefix('/users')->group( function() {
    Route::post('{user}/destroyPhotoProfile', array(UserController::class, 'destroyPhotoProfile'))->name('users.destroy.photo.profile');
    Route::post('add-photo-profile', array(UserController::class, 'photoUpdateOrInsert'))->name('users.add.photo.profile');
    Route::post('{user}/block', array(UserController::class, 'softDelete'))->name('users.block');
    Route::post('{user}/restore', array(UserController::class, 'restore'))->name('users.restore');
    Route::get('{user}/change-password', array(UserController::class, 'showChangePassword'))->name('users.show.change.password');
    Route::post('{user}/change-password', array(UserController::class, 'updateChangePassword'))->name('users.update.change.password');
    Route::post('{user}/change-address', array(UserController::class, 'changeAddress'))->name('users.update.address');
});
Route::resource('/users', UserController::class);

// Customer / User Address
Route::post('/customers/{customer}/ajax/request/edit-submit-get-data', array(CustomerController::class, 'ajaxEditSubmitGetData'));
Route::resource('/customers', CustomerController::class);

// Search
Route::get('/search/books', array(BookController::class, 'searchBooks'))->name('search.books');

Route::resource('/authors', AuthorController::class);

// My Account
// Route::get('/account', array(AccountController::class, 'index'));
// Route::prefix('/account')->group(function() {
    // Route::get('/transaction-lists', array(AccountController::class, 'transactionLists'))->name('transaction.lists');
//     Route::get('/my-reviews', array(AccountController::class, 'myReviews'))->name('my.reviews');
//     Route::get('/chat', array(AccountController::class, 'chat'))->name('chat');
//     Route::get('/add-new-account', array(AccountController::class, 'addNewAccount'))->name('add.new.account');
// });

// Books
Route::prefix('/books')->group(function() {
    Route::get('buy/{book}', array(BookController::class, 'booksBuy'))->name('books.buy');
    Route::get('shopping-cart/', array(BookController::class, 'shoppingCart'))->name('shopping.cart');
    Route::post('add-discount/{book}', array(BookController::class, 'addDiscount'))->name('book.add.discount');
    Route::get('wishlist/', array(BookController::class, 'wishlist'));
    Route::get('/waiting-for-payments', array(BookController::class, 'waitingForPayments'))->name('waiting.for.payment');
});
Route::resource('/books', BookController::class);

// Book Purchase
Route::prefix('/book-purchases')->group(function() {
    Route::post('ajax-payment-deadline', array(BookPurchaseController::class, 'ajaxPaymentDeadline'));
    Route::post('{book_user}/ajax-payment-deadline-text', array(BookPurchaseController::class, 'ajaxPaymentDeadlineText'));
    Route::post('{book}', array(BookPurchaseController::class, 'store'))->name('book-purchases.store');
});

Route::resource('/book-purchases', BookPurchaseController::class)->except('store')->parameter('book-purchases', 'book_user')->middleware('book_user');

// Chat dengan admin
Route::resource('/user-chats', UserChatController::class);

// Test Ajax
Route::post('/getmsg', array(TestController::class, 'index'))->name('getmsg');


// Ajax
Route::prefix('/ajax/request')->group(function() {
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

    // Provinsi
    Route::post('change-province', array(ProvinceController::class, 'ajaxChangeProvince'));

    // Kota / Kabupaten
    Route::post('change-city', array(CityController::class, 'ajaxChangeCity'));
});


Route::fallback(function($wkwk) {
    return 'gk ada halaman ini';
});

// Categories Route
Route::get('/categories/{category}', array(CategoryController::class, 'index'))->name('categories');

// Reset Password
Route::get('/forgot-password', array(ForgotPasswordController::class, 'showLinkRequestForm'))->name('forgot.password');

Auth::routes();
