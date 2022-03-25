<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\DisputeController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\PaymentCredentialController;
use Illuminate\Support\Facades\Route;

// Get Payment API Credentials
Route::post('payment/{payment_method}/credential', [
  PaymentCredentialController::class, 'credential'
]);

// Homepage
Route::get('announcement', [HomeController::class, 'announcement']);
Route::get('sliders', [HomeController::class, 'sliders']);
Route::get('banners', [HomeController::class, 'banners']);
Route::get('page/{slug}', [HomeController::class, 'page']);
Route::get('currencies', [HomeController::class, 'currencies']);
Route::get('system_configs', [HomeController::class, 'system_configs']);

// Plugins
Route::get('plugin/{slug}', [PackageController::class, 'isLoaded']);

// Blogs
Route::get('blogs', [BlogController::class, 'index']);
Route::get('blog/{slug}', [BlogController::class, 'show']);

// Categories
Route::get('featured-categories', [CategoryController::class, 'featuredCategories']);
Route::get('trending-categories', [CategoryController::class, 'trendingCategories']);
Route::get('category-grps', [CategoryController::class, 'categoryGroup']);
Route::get('category-subgrps/{group?}', [CategoryController::class, 'categorySubGroup']);
Route::get('categories/{sub_group?}', [CategoryController::class, 'index']);

// Shops
Route::get('shops', [HomeController::class, 'allShops']);
Route::get('shop/{slug}', [HomeController::class, 'shop']);
Route::get('shop/{slug}/listings', [ListingController::class, 'shop']);
Route::get('shop/{slug}/feedbacks', [FeedbackController::class, 'show_shop_feedbacks']);
Route::post('shop/{order}/feedback', [FeedbackController::class, 'save_shop_feedbacks']);
Route::get('shop/{shop}/contact', [ConversationController::class, 'conversation']);
Route::post('shop/{shop}/contact', [ConversationController::class, 'save_conversation']);

// Brands
Route::get('brands', [HomeController::class, 'allBrands']);
Route::get('brands/featured', [HomeController::class, 'featuredBrands']);
Route::get('brand/{slug}', [HomeController::class, 'brand']);
Route::get('brand/{slug}/listings', [ListingController::class, 'brand']);

// Listings
Route::get('search/{term}', [ListingController::class, 'search']);
Route::get('offers/{slug}', [ListingController::class, 'offers']);
Route::get('listings/{list?}', [ListingController::class, 'index']);
Route::get('listing/{slug}', [ListingController::class, 'item']);
Route::post('variant/{slug}', [ListingController::class, 'variant']);
Route::get('listing/category/{slug}', [ListingController::class, 'category']);
Route::get('listing/category-subgrp/{slug}', [ListingController::class, 'categorySubGroup']);
Route::get('listing/category-grp/{slug}', [ListingController::class, 'categoryGroup']);
Route::post('listing/{item}/shipTo', [ListingController::class, 'shipTo']);
Route::get('listing/{slug}/feedbacks', [FeedbackController::class, 'show_item_feedbacks']);
Route::get('recently_viewed_items', [ListingController::class, 'recently_viewed']);

// Deals
Route::prefix('deals')->group(function () {
  Route::get('flash-deals', [DealController::class, 'flashDeals']);
  Route::get('under-the-price', [DealController::class, 'underPrice']);
  Route::get('deal-of-the-day', [DealController::class, 'dealOfTheDay']);
  Route::get('tagline', [DealController::class, 'tagline']);
});

// CART
Route::post('addToCart/{slug}', [CartController::class, 'addToCart']);
Route::delete('cart/removeItem', [CartController::class, 'remove']);
Route::get('carts', [CartController::class, 'index']);
Route::get('cart/{cart}', [CartController::class, 'show']);
Route::put('cart/{cart}/update', [CartController::class, 'update']);
Route::get('cart/{cart}/shipping', [CartController::class, 'shipping']);
Route::post('cart/{cart}/checkout', [CheckoutController::class, 'checkout']);
Route::get('cart/{cart}/paymentOptions', [CheckoutController::class, 'paymentOptions']);

// Stripe
Route::post('cart/{cart}/stripePaymentIntent', [CheckoutController::class, 'stripePaymentIntent']);

// Shipping and packaging
Route::get('packaging/{shop}', [HomeController::class, 'packaging']);
Route::post('shipping/{shop}', [HomeController::class, 'shipping']);
Route::get('paymentOptions/{shop}', [HomeController::class, 'paymentOptions']);
Route::get('countries', [HomeController::class, 'countries']);
Route::get('states/{country}', [HomeController::class, 'states']);

// Route::get('cart/{expressId?}', [CartController::class, 'index'])->name('cart.index');
// Route::get('checkout/{slug}', [CheckoutController::class, 'directCheckout']);

// Auth
Route::prefix('auth')->group(function () {
  Route::post('register', [AuthController::class, 'register']);
  Route::post('login', [AuthController::class, 'login']);
  Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:api']);
  Route::post('forgot', [AuthController::class, 'forgot']);
  Route::get('reset/{token}', [AuthController::class, 'token']);
  Route::post('reset', [AuthController::class, 'reset']);
  Route::post('social/{provider}', [AuthSocialController::class, 'socialLgin']);
});

// Customer
Route::middleware('auth:api')->group(function () {
  Route::get('dashboard', [AccountController::class, 'index']);
  Route::get('account/update', [AccountController::class, 'edit']);
  Route::put('account/update', [AccountController::class, 'update']);
  Route::put('password/update', [AccountController::class, 'password_update']);
  Route::get('conversations', [ConversationController::class, 'conversations']);

  // Address
  Route::get('addresses', [AddressController::class, 'index']);
  Route::get('address/create', [AddressController::class, 'create']);
  Route::post('address/store', [AddressController::class, 'store']);
  Route::get('address/{address}', [AddressController::class, 'edit']);
  Route::put('address/{address}', [AddressController::class, 'update']);
  Route::delete('address/{address}', [AddressController::class, 'delete']);

  // Coupons
  Route::get('coupons', [AccountController::class, 'coupons']);
  Route::post('cart/{cart}/applyCoupon', [CartController::class, 'validateCoupon']);

  // Wishlist
  Route::get('wishlist', [WishlistController::class, 'index']);
  Route::get('wishlist/{slug}/add', [WishlistController::class, 'add']);
  Route::delete('wishlist/{wishlist}/remove', [WishlistController::class, 'remove']);

  // Orders
  Route::get('orders', [OrderController::class, 'index']);
  Route::get('order/{order}', [OrderController::class, 'show']);
  Route::get('order/{order}/conversation', [OrderController::class, 'conversation']);
  Route::post('order/{order}/conversation', [OrderController::class, 'save_conversation']);
  Route::get('order/{order}/track', [OrderController::class, 'track']);
  Route::post('order/{order}/feedback', [FeedbackController::class, 'save_product_feedbacks']);
  Route::post('order/{order}/goodsReceived', [OrderController::class, 'goods_received']);

  //invoice
  Route::get('download/invoice/{order}', [OrderController::class, 'invoice']);

  // Disputes
  Route::get('disputes', [DisputeController::class, 'index']);
  Route::get('order/{order}/dispute', [DisputeController::class, 'create']);
  Route::post('order/{order}/dispute', [DisputeController::class, 'store']);
  Route::get('dispute/{dispute}', [DisputeController::class, 'show']);
  Route::get('dispute/{dispute}/response', [DisputeController::class, 'response_form']);
  Route::post('dispute/{dispute}/response', [DisputeController::class, 'response']);
  Route::post('dispute/{dispute}/appeal', [DisputeController::class, 'appeal']);
  Route::put('dispute/{dispute}/solved', [DisputeController::class, 'mark_as_solved']);
  Route::get('attachment/{attachment}/download', [AttachmentController::class, 'download']);
});
