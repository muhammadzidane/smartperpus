<?php

// use App\Http\Controllers\{
//     AjaxController,
//     BookController,
//     AuthorController,
//     BookImageController,
//     CategoryController,
//     HomeController,
//     TestController,
//     BookPurchaseController,
//     BookUserController,
//     CartController,
//     CityController,
//     CustomerController,
//     ContentSearchFilterController,
//     UserController,
//     ProvinceController,
//     UserChatController,
//     StatusController,
//     WishlistController,
//     ValidatorController,
//     CheckoutController,
//     IncomeController,
// };

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\{Route, Auth};

Route::get('/', array(HomeController::class, 'index'))->name('home');

// TEST
Route::get('/test', array(TestController::class, 'test'))->name('test');
Route::post('/test', array(TestController::class, 'testPost'))->name('test.post');
Route::get('/pagination', array(TestController::class, 'pagination'));

// User
Route::prefix('users')->group(function () {
    Route::patch('{user}/destroy-photo', array(UserController::class, 'destroyPhoto'))->name('users.destroy.photo');
    Route::patch('{user}/add-photo-profile', array(UserController::class, 'photoUpdateOrInsert'))->name('users.add.photo.profile');
    Route::post('{user}/block', array(UserController::class, 'softDelete'))->name('users.block');
    Route::post('{user}/restore', array(UserController::class, 'restore'))->name('users.restore');
    Route::patch('{user}/change-password', array(UserController::class, 'changePassword'));
    Route::patch('{user}/change-biodata', array(UserController::class, 'changeBiodata'));
    Route::patch('{user}/change-email', array(UserController::class, 'changeEmail'));
});
Route::resource('users', UserController::class);

Route::prefix('validator')->group(function () {
    Route::get('unique', array(ValidatorController::class, 'unique'));
});

// Customer / User Address
Route::post('customers/{customer}/ajax/request/edit-submit-get-data', array(CustomerController::class, 'ajaxEditSubmitGetData'));
Route::get('customers/city-or-district', array(CustomerController::class, 'ajaxCityOrDistrict'));
Route::resource('/customers', CustomerController::class);

// Search
Route::get('/search/books', array(BookController::class, 'searchBooks'))->name('search.books');

Route::resource('authors', AuthorController::class)->except('index', 'edit');
Route::get('authors', array(AuthorController::class, 'index'))->name('authors.index');

// My Account
// Route::get('/account', array(AccountController::class, 'index'));
// Route::prefix('/account')->group(function() {
// Route::get('/transaction-lists', array(AccountController::class, 'transactionLists'))->name('transaction.lists');
//     Route::get('/my-reviews', array(AccountController::class, 'myReviews'))->name('my.reviews');
//     Route::get('/chat', array(AccountController::class, 'chat'))->name('chat');
//     Route::get('/add-new-account', array(AccountController::class, 'addNewAccount'))->name('add.new.account');
// });

// Books
Route::prefix('books')->group(function () {
    Route::get('buy/{book}', array(BookController::class, 'booksBuy'))->name('books.buy');
    Route::post('add-discount/{book}', array(BookController::class, 'addDiscount'))->name('book.add.discount');
    Route::patch('{book}/add-stock', array(BookController::class, 'addStock'))->middleware('auth.admin.only');
    Route::get('search', array(BookController::class, 'search'));
    Route::post('add-book-images/{book}', array(BookController::class, 'addBookImages'))->name('add.book.images');
});

// Cart
Route::resource('carts', CartController::class);
Route::post('carts/{book}/bought-directly', array(CartController::class, 'boughtDirectly'))->name('carts.bought.directly');

Route::patch('book_images/{book_image}/edit', array(BookImageController::class, 'edit'))->name('book.images.edit');
Route::delete('book_images/{book_image}/delete', array(BookImageController::class, 'destroy'))->name('book.images.destroy');

// Ajax search Filter
Route::prefix('search')->group(function () {
    Route::get('book-filter', array(ContentSearchFilterController::class, 'bookFilter'));
});

Route::resource('/books', BookController::class);

// Wishlist
Route::prefix('wishlists')->middleware('auth')->group(function () {
    Route::get('/', array(WishlistController::class, 'index'))->name('wishlists.index');
    Route::post('/', array(WishlistController::class, 'store'));
    Route::delete('/{id}', array(WishlistController::class, 'destroy'));
    Route::get('/search', array(WishlistController::class, 'search'));
});

// Book User
Route::resource('book-users', BookUserController::class);
Route::prefix('book-users/status')->group(function () {
    Route::get('uploaded-payment', array(BookUserController::class, 'uploadedPayments'))->name('uploaded.payments');
    Route::get('confirmed-orders', array(BookUserController::class, 'confirmedOrders'))->name('confirmed.orders');
    Route::get('on-delivery', array(BookUserController::class, 'onDelivery'))->name('on.delivery');
    Route::get('success', array(BookUserController::class, 'arrived'))->name('book.users.status.arrived');

    // Ajax request
    Route::get('ajax/income-detail', array(BookUserController::class, 'incomeDetail'));

    // Lacak paket
    Route::get('/tracking-packages', array(BookUserController::class, 'trackingPackages'));
});

// Income
Route::get('income', array(BookUserController::class, 'income'))->name('book.users.status.income');
Route::get('income/detail', array(IncomeController::class, 'incomeDetail'))->name('income.detail');
Route::get('income/detail/today', array(IncomeController::class, 'incomeDetailToday'))->name('income.detail.today');
Route::get('income/detail/this-month', array(IncomeController::class, 'incomeDetailThisMonth'))->name('income.detail.this.month');

Route::prefix('status')->group(function () {
    Route::get('all', array(StatusController::class, 'index'))->name('status.all');
    Route::get('unpaid', array(StatusController::class, 'index'))->name('status.unpaid');
    Route::get('failed', array(StatusController::class, 'index'))->name('status.failed');
    Route::get('on-process', array(StatusController::class, 'index'))->name('status.on.process');
    Route::get('on-delivery', array(StatusController::class, 'index'))->name('status.on.delivery');
    Route::get('completed', array(StatusController::class, 'index'))->name('status.completed');
    Route::get('{invoice}/detail', array(StatusController::class, 'detail'));
    Route::get('uploaded-payment', array(StatusController::class, 'index'))->name('status.uploaded.payment');

    // Update
    Route::patch('{invoice}', array(StatusController::class, 'update'));
});

Route::get('/book-users/search/{keywords}', array(BookUserController::class, 'search'));

// Book Purchase
Route::resource('/book-purchases', BookPurchaseController::class)->except('store', 'show')->parameter('book-purchases', 'book_user');
Route::prefix('book-purchases')->group(function () {
    Route::get('{invoice}', array(BookPurchaseController::class, 'show'))->name('book.purchases.show');
    Route::patch('{invoice}/upload-payment', array(BookPurchaseController::class, 'uploadPayment'))->name('book-purchases.upload');
    Route::post('ajax-payment-deadline', array(BookPurchaseController::class, 'ajaxPaymentDeadline'));
    Route::post('{book_user}/ajax-payment-deadline-text', array(BookPurchaseController::class, 'ajaxPaymentDeadlineText'));
    Route::post('{book}', array(BookPurchaseController::class, 'store'))->name('book-purchases.store');
});

// Checkout
Route::post('checkouts', array(CheckoutController::class, 'checkout'))->name('checkout');
Route::get('checkouts', array(CheckoutController::class, 'index'))->name('checkout.index');
Route::post('checkouts/{user}/payment', array(CheckoutController::class, 'checkoutPayment'))->name('checkout.payment');

// Chat dengan admin
Route::resource('/user-chats', UserChatController::class)->except('index', 'edit');
Route::post('/user-chats/search', array(UserChatController::class, 'search'));
Route::post('/user-chats/{user_chat}', array(UserChatController::class, 'destroyy'))->name('user-chats.destroy');

// Test Ajax
Route::post('/getmsg', array(TestController::class, 'index'))->name('getmsg');

// Ajax
Route::prefix('/ajax/request')->group(function () {
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

// Categories Route
Route::get('/categories/{category}', array(CategoryController::class, 'index'))->name('categories');

// Reset Password
Route::get('/forgot-password', array(ForgotPasswordController::class, 'showLinkRequestForm'))->name('forgot.password');

Auth::routes();
