<?php

use Illuminate\Support\Facades\Auth;
use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SupplieController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NewsletterControler;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperMarketController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\SuperMarketCategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// CACHE CLEAR ROUTE
Route::get('cache-clear', function () {
    Artisan::call('optimize:clear');
    request()->session()->flash('success', 'Le cache a été vidé avec succès.');
    return redirect()->back();
})->name('cache.clear');

// STORAGE LINKED ROUTE
Route::get('storage-link', [AdminController::class, 'storageLink'])->name('storage.link');

// AUTHENTIFICATION

// Register
Auth::routes(['register' => false]);
Route::get('user/register', [LoginController::class, 'register'])->name('register.form');
Route::post('user/register', [LoginController::class, 'registerSubmit'])->name('register.submit');

//  login
Route::get('user/login', [LoginController::class, 'login'])->name('login.form');
Route::post('user/login', [LoginController::class, 'loginSubmit'])->name('login.submit');

// logout
Route::get('user/logout', [LoginController::class, 'logout'])->name('user.logout');

// Reset password
Route::GET('password-reset', [LoginController::class, 'showResetForm'])->name('password.reset');
// Socialite

Route::get('login/{provider}/', [LoginController::class, 'redirect'])->name('login.redirect');
Route::get('login/{provider}/callback/', [LoginController::class, 'Callback'])->name('login.callback');

Route::get('/', [FrontendController::class, 'home'])->name('home');

// Frontend Routes
Route::get('/home', [FrontendController::class, 'index']);
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/message', [MessageController::class, 'store'])->name('contact.store');
Route::get('product-detail/{slug}', [FrontendController::class, 'productDetail'])->name('product-detail');
Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search');
Route::get('/product-cat/{slug}', [FrontendController::class, 'productCat'])->name('product-cat');
Route::get('/product-shop/{slug}', [FrontendController::class, 'productShop'])->name('product-shop');

Route::get('/shops/{slug}', [FrontendController::class, 'productCategory'])->name('shops');

Route::get('/product-sub-cat/{slug}/{sub_slug}', [FrontendController::class, 'productSubCat'])->name('product-sub-cat');
Route::get('/product-brand/{slug}', [FrontendController::class, 'productBrand'])->name('product-brand');
// Cart section
Route::get('/add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete');
Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update');


Route::get('/cart', function () {
    return view('frontend.pages.cart');
})->name('cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('user');

Route::get('/markets', [SuperMarketController::class, 'markets'])->name('markets');
// Wishlist
Route::get('/wishlist', function () {
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}', [WishlistController::class, 'wishlist'])->name('add-to-wishlist')->middleware('user');
Route::get('wishlist-delete/{id}', [WishlistController::class, 'wishlistDelete'])->name('wishlist-delete');
Route::post('cart/order',  [OrderController::class, 'store'])->name('cart.order');
Route::get('order/pdf/{id}',  [OrderController::class, 'pdf'])->name('order.pdf');
Route::get('/income', [OrderController::class, 'incomeChart'])->name('product.order.income');
// Route::get('/user/chart','AdminController@userPieChart')->name('user.piechart');
Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids');
Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists');
Route::match(['get', 'post'], '/filter', [FrontendController::class, 'productFilter'])->name('shop.filter');
// Order Track
Route::get('/product/track', [OrderController::class, 'orderTrack'])->name('order.track');
Route::post('product/track/order', [OrderController::class, 'productTrackOrder'])->name('product.track.order');
// Blog
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog-detail/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.detail');
Route::get('/blog/search', [FrontendController::class, 'blogSearch'])->name('blog.search');
Route::post('/blog/filter', [FrontendController::class, 'blogFilter'])->name('blog.filter');
Route::get('blog-cat/{slug}', [FrontendController::class, 'blogByCategory'])->name('blog.category');
Route::get('blog-tag/{slug}', [FrontendController::class, 'blogByTag'])->name('blog.tag');

// NewsLetter
Route::post('/subscribe', [NewsletterControler::class, 'subscribe'])->name('subscribe');

// Product Review
Route::resource('/review', 'ProductReviewController');
Route::post('product/{slug}/review', [ProductReviewController::class, 'store'])->name('review.store');

// Post Comment
Route::post('post/{slug}/comment', 'PostCommentController@store')->name('post-comment.store');
Route::resource('/comment', 'PostCommentController');
// Coupon
Route::post('/coupon-store', 'CouponController@couponStore')->name('coupon-store');
// Payment
Route::get('payment', 'PayPalController@payment')->name('payment');
Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
Route::get('payment/success', 'PayPalController@success')->name('payment.success');

// Backend section start

Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'user-role:admin']], function () {
    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/file-manager', function () {
        return view('backend.layouts.file-manager');
    })->name('file-manager');
    // user route
    Route::resource('users', 'UsersController');
    // Banner
    Route::resource('banner', 'BannerController');
   
    // Profile
    Route::get('/profile', 'AdminController@profile')->name('admin.profile');
    Route::post('/profile/{id}', 'AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::resource('/category', 'CategoryController');
    // Product
    Route::resource('/product', 'ProductController');
    // Ajax for sub category
    Route::post('/category/{id}/child', 'CategoryController@getChildByParent');
    // POST category
    Route::resource('/post-category', 'PostCategoryController');
    // Post tag
    Route::resource('/post-tag', 'PostTagController');
    // Post
    Route::resource('/post', 'PostController');
    // Message
    Route::resource('/message', 'MessageController');
    Route::get('/message/five', 'MessageController@messageFive')->name('messages.five');
    // Settings
    Route::get('settings', 'AdminController@settings')->name('settings');
    Route::post('setting/update', 'AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}', 'NotificationController@show')->name('admin.notification');
    // Route::get('/notifications', 'NotificationController@index')->name('all.notification');
    // Route::delete('/notification/{id}', 'NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('admin.change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');
    // Fournisseurs
    Route::resource('/supplie', 'SupplieController');
    // Order
    Route::get('/order', 'OrderController@index')->name('order.index.admin');
    Route::get('/order/show/{id}', "OrderController@show")->name('order.show.admin');
    Route::resource('/supermarket', SuperMarketController::class);
    Route::resource('/superMarketCategory', SuperMarketCategoryController::class);
});

// Fournisseur section start

Route::group(['prefix' => '/supplie', 'middleware' => ['auth', 'user-role:fournisseur']], function () {
    Route::get('/', 'SupplieController@index')->name('fournisseur');
    Route::get('/file-manager', function () {
        return view('fournisseur.layouts.file-manager');
    })->name('file-manager');
    // user route
    // Route::resource('brand', 'BrandController');
    // Profile
    Route::get('/profile', 'AdminController@profile')->name('fournisseur.profile');
    Route::post('/profile/{id}', 'AdminController@profileUpdate')->name('profile-update');

    // Notification
    Route::get('/notification/{id}', 'NotificationController@show')->name('fournisseur.notification');
    Route::get('/notifications', 'NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}', 'NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('fournisseur.change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');
    // Edit Fournisseur
    Route::get('/supplie', [SupplieController::class, 'edit'])->name('supplie.edit');
    Route::patch('/supplie', [SupplieController::class, 'update'])->name('supplie.update');

     // Order
    Route::resource('/order', 'OrderController');
});

// User section start
Route::group(['prefix' => '/user', 'middleware' => ['user-role:user']], function () {
    Route::get('/', 'HomeController@index')->name('user');
    // Profile
    Route::get('/profile', 'HomeController@profile')->name('user-profile');
    Route::post('/profile/{id}', 'HomeController@profileUpdate')->name('user-profile-update');
    //  Order
    Route::get('/order', "HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}', "HomeController@orderShow")->name('user.order.show');
    Route::delete('/order/delete/{id}', 'HomeController@userOrderDelete')->name('user.order.delete');
    // Product Review
    Route::get('/user-review', 'HomeController@productReviewIndex')->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}', 'HomeController@productReviewDelete')->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}', 'HomeController@productReviewEdit')->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}', 'HomeController@productReviewUpdate')->name('user.productreview.update');

    // Post comment
    Route::get('user-post/comment', 'HomeController@userComment')->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}', 'HomeController@userCommentDelete')->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}', 'HomeController@userCommentEdit')->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}', 'HomeController@userCommentUpdate')->name('user.post-comment.update');

    // Password Change
    Route::get('change-password', 'HomeController@changePassword')->name('user.change.password.form');
    Route::post('change-password', 'HomeController@changPasswordStore')->name('change.password');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    Lfm::routes();
});

Route::group(['middleware' => ['web', 'auth']], function () {
    // Category
    Route::resource('/category', 'CategoryController');
    // Product
    Route::resource('/product', 'ProductController');

    // Coupon
    Route::resource('/coupon', 'CouponController');

    // Shipping
    Route::resource('/shipping', 'ShippingController');

     // Brand
    Route::resource('brand', 'BrandController');
});
