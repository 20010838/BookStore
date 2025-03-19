<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BookController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\GalleryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Auth::routes();

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

// Book Routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books-new', function() {
    return redirect()->route('books.index');
})->name('books.index_new');
Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/category/{slug}', [BookController::class, 'byCategory'])->name('books.by_category');
Route::get('/author/{slug}', [BookController::class, 'byAuthor'])->name('books.by_author');

// Gallery Routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

// Newsletter Routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Order Routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [OrderController::class, 'place'])->name('orders.place');
    Route::get('/order-success/{id}', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    
    // Review Routes
    Route::post('/reviews/{bookId}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // User Profile Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('user.update_profile');
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change_password');
    Route::patch('/change-password', [UserController::class, 'changePassword'])->name('user.update_password');
    Route::get('/my-reviews', [UserController::class, 'reviews'])->name('user.reviews');
});

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}/products', [ProductController::class, 'byCategory'])->name('products.by_category');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Reports
    Route::get('/sales-report', [AdminReportController::class, 'sales'])->name('reports.sales');
    Route::get('/inventory-report', [AdminReportController::class, 'inventory'])->name('reports.inventory');
    
    // Books Management
    Route::resource('books', AdminBookController::class);
    
    // Categories Management
    Route::resource('categories', AdminCategoryController::class);
    
    // Authors Management
    Route::resource('authors', AdminAuthorController::class);
    
    // Orders Management
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
    Route::patch('/orders/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update_payment');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders-export', [AdminOrderController::class, 'export'])->name('orders.export');
    
    // Users Management
    Route::resource('users', AdminUserController::class);
    
    // Reviews Management
    Route::resource('reviews', AdminReviewController::class)->except(['create', 'store']);
    Route::patch('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{id}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    
    // Product management routes
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
});
