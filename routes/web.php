<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\AuthController;

// Users / Public
use App\Http\Controllers\Users\HomeController;
use App\Http\Controllers\Users\ExploreController;
use App\Http\Controllers\Users\CartController;
use App\Http\Controllers\Users\CheckoutController;
use App\Http\Controllers\Users\OrderTrackingController;
use App\Http\Controllers\Users\SubscriptionController;
use App\Http\Controllers\Users\ProfileController as UserProfileController;

// Creators
use App\Http\Controllers\Creators\CreatorHubController;

// Store
use App\Http\Controllers\Store\DashboardController as StoreDashboardController;
use App\Http\Controllers\Store\OrderController as StoreOrderController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\ProfileController as StoreProfileController;

// Riders
use App\Http\Controllers\Riders\DashboardController as RiderDashboardController;
use App\Http\Controllers\Riders\OrderDeliveryController as RiderOrderController;
use App\Http\Controllers\Riders\WalletPayoutController as RiderWalletController;

// SuperAdmin
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\StoreApprovalController as SuperAdminStoreController;
use App\Http\Controllers\SuperAdmin\RiderApprovalController as SuperAdminRiderController;
use App\Http\Controllers\SuperAdmin\RiderPayoutController as SuperAdminPayoutController;
use App\Http\Controllers\SuperAdmin\CreatorManagerController as SuperAdminCreatorController;
use App\Http\Controllers\SuperAdmin\SubscriptionManagerController as SuperAdminSubscriptionController;
use App\Http\Controllers\SuperAdmin\SystemSettingsController as SuperAdminSettingsController;

/*
|--------------------------------------------------------------------------
| Public / Customer Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('docs.guide');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register/customer', [AuthController::class, 'registerCustomer'])->name('register.customer');
Route::post('/register/store', [AuthController::class, 'registerStore'])->name('register.store');
Route::post('/register/rider', [AuthController::class, 'registerRider'])->name('register.rider');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick.login');

// Explore & Marketplace
Route::get('/explore', [ExploreController::class, 'index'])->name('users.explore');
Route::get('/explore/store/{slug}', [ExploreController::class, 'showStore'])->name('users.stores.show');
Route::get('/explore/product/{id}', [ExploreController::class, 'showProduct'])->name('users.products.modal');
Route::get('/api/calculate-fee', [ExploreController::class, 'apiCalculateFee'])->name('api.calculate.fee');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('users.cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('users.cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('users.cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('users.cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('users.cart.clear');

// Creator & Cooking Vlogs Hub
Route::get('/creators', [CreatorHubController::class, 'index'])->name('creators.index');
Route::get('/creators/recipe/{slug}', [CreatorHubController::class, 'show'])->name('creators.show');
Route::post('/creators/recipe/{id}/order-ingredients', [CreatorHubController::class, 'addRecipeIngredientsToCart'])->name('creators.order.ingredients');

// VIP Subscriptions
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('users.subscriptions.index');
Route::post('/subscriptions/subscribe/{planId}', [SubscriptionController::class, 'subscribe'])->middleware('auth')->name('users.subscriptions.subscribe');

// Authenticated Customer Actions
Route::middleware(['auth'])->prefix('my')->name('users.')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders & Tracking
    Route::get('/orders', [OrderTrackingController::class, 'index'])->name('orders.index');
    Route::get('/orders/track/{orderNumber}', [OrderTrackingController::class, 'track'])->name('orders.track');
    Route::get('/orders/{orderNumber}/status-poll', [OrderTrackingController::class, 'statusPoll'])->name('orders.status.poll');
    Route::post('/orders/{orderNumber}/review', [OrderTrackingController::class, 'submitReview'])->name('orders.review.submit');
    Route::post('/orders/{orderNumber}/cancel', [OrderTrackingController::class, 'cancel'])->name('orders.cancel');
    Route::delete('/orders/{orderNumber}/cancel', [OrderTrackingController::class, 'cancel'])->name('orders.cancel.delete');

    // Profile Management
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
});

// Backward compatibility alias routes
Route::get('/orders/track/{orderNumber}', [OrderTrackingController::class, 'track'])->name('orders.track');
Route::get('/orders/{orderNumber}/status-poll', [OrderTrackingController::class, 'statusPoll'])->name('orders.status.poll');
Route::post('/orders/{orderNumber}/cancel', [OrderTrackingController::class, 'cancel'])->name('orders.cancel.alias');

/*
|--------------------------------------------------------------------------
| Store Portal Routes (Role: store)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:store'])->prefix('store')->name('store.')->group(function () {
    Route::get('/dashboard', [StoreDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-status', [StoreDashboardController::class, 'toggleStatus'])->name('toggle.status');

    // Store Orders & GCash Proof Verification
    Route::get('/orders', [StoreOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [StoreOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/accept', [StoreOrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{id}/decline', [StoreOrderController::class, 'decline'])->name('orders.decline');
    Route::post('/orders/{id}/ready', [StoreOrderController::class, 'markReady'])->name('orders.ready');

    // Product & Nutrition Management
    Route::get('/products', [StoreProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [StoreProductController::class, 'create'])->name('products.create');
    Route::post('/products', [StoreProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [StoreProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [StoreProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [StoreProductController::class, 'destroy'])->name('products.destroy');

    // Profile & GCash Settings
    Route::get('/profile', [StoreProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [StoreProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Delivery Rider Portal Routes (Role: rider)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:rider'])->prefix('riders')->name('riders.')->group(function () {
    Route::get('/dashboard', [RiderDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-duty', [RiderDashboardController::class, 'toggleDuty'])->name('toggle.duty');
    Route::post('/update-location', [RiderDashboardController::class, 'updateLocation'])->name('update.location');

    // Trip & Navigation Actions
    Route::post('/orders/{id}/accept', [RiderOrderController::class, 'accept'])->name('orders.accept');
    Route::get('/orders/{id}/active', [RiderOrderController::class, 'showActive'])->name('orders.active');
    Route::post('/orders/{id}/picked-up', [RiderOrderController::class, 'markPickedUp'])->name('orders.pickedup');
    Route::post('/orders/{id}/on-the-way', [RiderOrderController::class, 'markOnTheWay'])->name('orders.ontheway');
    Route::post('/orders/{id}/delivered', [RiderOrderController::class, 'markDelivered'])->name('orders.delivered');

    // Rider Wallet & GCash Payouts
    Route::get('/wallet', [RiderWalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/payout', [RiderWalletController::class, 'requestPayout'])->name('wallet.payout');
});

/*
|--------------------------------------------------------------------------
| Super Admin Portal Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Store Vetting & Approval
    Route::get('/stores', [SuperAdminStoreController::class, 'index'])->name('stores.index');
    Route::post('/stores/{id}/approve', [SuperAdminStoreController::class, 'approve'])->name('stores.approve');
    Route::post('/stores/{id}/reject', [SuperAdminStoreController::class, 'reject'])->name('stores.reject');

    // Rider Vetting & Approval
    Route::get('/riders', [SuperAdminRiderController::class, 'index'])->name('riders.index');
    Route::post('/riders/{id}/approve', [SuperAdminRiderController::class, 'approve'])->name('riders.approve');
    Route::post('/riders/{id}/reject', [SuperAdminRiderController::class, 'reject'])->name('riders.reject');

    // Rider Payout Queue
    Route::get('/payouts', [SuperAdminPayoutController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/{id}/process', [SuperAdminPayoutController::class, 'process'])->name('payouts.process');
    Route::post('/payouts/{id}/reject', [SuperAdminPayoutController::class, 'reject'])->name('payouts.reject');

    // Creator & Vlog Management
    Route::get('/creators', [SuperAdminCreatorController::class, 'index'])->name('creators.index');
    Route::post('/creators', [SuperAdminCreatorController::class, 'storeCreator'])->name('creators.store');
    Route::delete('/creators/{id}', [SuperAdminCreatorController::class, 'destroyCreator'])->name('creators.destroy');
    Route::post('/creators/recipe', [SuperAdminCreatorController::class, 'storeRecipe'])->name('creators.recipe.store');
    Route::delete('/creators/recipe/{id}', [SuperAdminCreatorController::class, 'destroyRecipe'])->name('creators.recipe.destroy');

    // Subscription Plans & Members
    Route::get('/subscriptions', [SuperAdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/plan', [SuperAdminSubscriptionController::class, 'storePlan'])->name('subscriptions.plan.store');
    Route::delete('/subscriptions/plan/{id}', [SuperAdminSubscriptionController::class, 'destroyPlan'])->name('subscriptions.plan.destroy');
    Route::delete('/subscriptions/{id}', [SuperAdminSubscriptionController::class, 'destroySubscription'])->name('subscriptions.destroy');

    // System Settings & Distance Rate Pricing
    Route::get('/settings', [SuperAdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SuperAdminSettingsController::class, 'update'])->name('settings.update');
});
