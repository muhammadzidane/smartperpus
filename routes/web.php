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
//     InboxController,
// };

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\{Route, Auth};


Route::get('/', array(HomeController::class, 'index'))->name('home');

// TEST
Route::get('test', abort(404));
Route::post('test', array(TestController::class, 'testPost'))->name('test.post');

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
Route::resource('users', UserController::class)->except('edit');

Route::prefix('validator')->group(function () {
    Route::get('unique', array(ValidatorController::class, 'unique'));
});

// Customer / User Address
Route::post('customers/{customer}/ajax/request/edit-submit-get-data', array(CustomerController::class, 'ajaxEditSubmitGetData'));
Route::get('customers/city-or-district', array(CustomerController::class, 'ajaxCityOrDistrict'));
Route::resource('/customers', CustomerController::class);

Route::resource('authors', AuthorController::class)->except('index', 'edit');
Route::get('authors', array(AuthorController::class, 'index'))->name('authors.index');

// Books
Route::prefix('books')->group(function () {
    Route::get('buy/{book}', array(BookController::class, 'booksBuy'))->name('books.buy');
    Route::post('add-discount/{book}', array(BookController::class, 'addDiscount'))->name('book.add.discount');
    Route::patch('{book}/add-stock', array(BookController::class, 'addStock'));
    Route::patch('{book}/add-discount', array(BookController::class, 'addDiscount'));
    Route::get('search', array(BookController::class, 'search'));
});

Route::get('search/books', array(BookController::class, 'searchBooks'))->name('search.books');
Route::resource('books', BookController::class);

// Cart
Route::resource('carts', CartController::class);
Route::post('carts/{book}/bought-directly', array(CartController::class, 'boughtDirectly'))->name('carts.bought.directly');

Route::patch('book_images/{book_image}/update', array(BookImageController::class, 'update'))->name('book.images.edit');
Route::delete('book_images/{book_image}/delete', array(BookImageController::class, 'destroy'))->name('book.images.destroy');
Route::post('book_images/{book}', array(BookImageController::class, 'store'));
Route::patch('book_images/{book}/update-main', array(BookImageController::class, 'updateMainImage'));

// Ajax search Filter
Route::prefix('search')->group(function () {
    Route::get('book-filter', array(ContentSearchFilterController::class, 'bookFilter'));
});

// Wishlist
Route::prefix('wishlists')->middleware('auth')->group(function () {
    Route::get('/', array(WishlistController::class, 'index'))->name('wishlists.index');
    Route::post('/', array(WishlistController::class, 'store'));
    Route::delete('/{id}', array(WishlistController::class, 'destroy'));
    Route::get('/search', array(WishlistController::class, 'search'));
});

// Book User
Route::resource('book-users', BookUserController::class);

// Income
Route::get('income', array(IncomeController::class, 'income'))->name('income');
Route::get('income/detail', array(IncomeController::class, 'incomeDetail'))->name('income.detail');
Route::get('income/detail/today', array(IncomeController::class, 'incomeDetailToday'))->name('income.detail.today');
Route::get('income/detail/this-month', array(IncomeController::class, 'incomeDetailThisMonth'))->name('income.detail.this.month');

Route::prefix('status')->middleware('auth')->group(function () {
    Route::get('all', array(StatusController::class, 'index'))->name('status.all');
    Route::get('unpaid', array(StatusController::class, 'index'))->name('status.unpaid');
    Route::get('failed', array(StatusController::class, 'index'))->name('status.failed');
    Route::get('on-process', array(StatusController::class, 'index'))->name('status.on.process');
    Route::get('on-delivery', array(StatusController::class, 'index'))->name('status.on.delivery');
    Route::get('completed', array(StatusController::class, 'index'))->name('status.completed');
    Route::get('{invoice}/detail', array(StatusController::class, 'detail'));
    Route::get('uploaded-payment', array(StatusController::class, 'index'))->name('status.uploaded.payment');
    Route::post('/buy-again', array(StatusController::class, 'buyAgain'));
    Route::post('/add-rating', array(StatusController::class, 'addRating'));
});

// Review
Route::middleware('auth')->get('inbox/my-reviews', array(InboxController::class, 'review'));

Route::get('/book-users/search/{keywords}', array(BookUserController::class, 'search'));

// Book Purchase
Route::resource('/book-purchases', BookPurchaseController::class)->except('store', 'show')->parameter('book-purchases', 'book_user');
Route::prefix('book-purchases')->group(function () {
    Route::get('{invoice}', array(BookPurchaseController::class, 'show'))->name('book.purchases.show');
    Route::patch('{invoice}/upload-payment', array(BookPurchaseController::class, 'uploadPayment'))->name('book-purchases.upload');
    Route::post('ajax-payment-deadline', array(BookPurchaseController::class, 'ajaxPaymentDeadline'));
    Route::post('{book}', array(BookPurchaseController::class, 'store'))->name('book-purchases.store');
});

// Checkout
Route::post('checkouts', array(CheckoutController::class, 'checkout'))->name('checkout');
Route::get('checkouts', array(CheckoutController::class, 'index'))->name('checkout.index');
Route::post('checkouts/{user}/payment', array(CheckoutController::class, 'checkoutPayment'))->name('checkout.payment');
Route::patch('checkouts/change-main-address', array(CheckoutController::class, 'changeMainAddress'));
Route::post('checkouts/customer-store', array(CheckoutController::class, 'customerStore'));

// Chat dengan admin
Route::resource('user-chats', UserChatController::class)->except('index', 'edit');
Route::post('user-chats/search', array(UserChatController::class, 'search'));

// Ajax
Route::prefix('/ajax/request')->group(function () {
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
