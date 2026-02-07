<?php

use App\Http\Controllers\Web\BasketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Pages\IndexController;
use App\Http\Controllers\Pages\ContactController;    
use App\Http\Controllers\Pages\ProductController;
use App\Http\Controllers\Pages\ArticleController;
use App\Http\Controllers\Pages\CheckoutController;
use App\Http\Controllers\Pages\CheckoutSummaryController;
use App\Http\Controllers\Pages\SearchController;
use App\Http\Controllers\Pages\LandingController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\EditorUploadTestController;
use App\Http\Controllers\Dashboard\IndexController as DashboardController;
use App\Http\Controllers\Dashboard\AuthController as DashboardAuthController;
use App\Http\Controllers\Dashboard\ModuleController as DashboardModuleController;
use App\Http\Controllers\Dashboard\ModuleDisplayController;
use App\Http\Controllers\Dashboard\RolesPermissionsController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\TransportController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\DistributionController;
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

Route::get('/', [ IndexController::class, 'index'])->name('index');
Route::get('/about', [ IndexController::class, 'about'])->name('about');
Route::get('/contact', [ ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ ContactController::class, 'store'])->name('contact.store');
// Landing pages routes
Route::get('/landing', [ LandingController::class, 'index'])->name('landing.index');
Route::get('/landing/1', [ LandingController::class, 'landing1'])->name('landing.1');
Route::get('/landing/2', [ LandingController::class, 'landing2'])->name('landing.2');
Route::get('/landing/3', [ LandingController::class, 'landing3'])->name('landing.3');
Route::get('/products', [ ProductController::class, 'index'])->name('products');
Route::get('/api/products', [ ProductController::class, 'api'])->name('api.products');
Route::get('/product/{slug}', [ ProductController::class, 'show'])->name('product.show');
Route::get('/articles', [ ArticleController::class, 'index'])->name('articles');
Route::get('/article/{slug}', [ ArticleController::class, 'show'])->name('article.show');
Route::get('/vyhledavani', [ SearchController::class, 'index'])->name('search');
Route::get('/checkout', [ CheckoutController::class, 'index'])->name('checkout');
Route::get('/checkout/summary', [ CheckoutSummaryController::class, 'index'])->name('checkout.summary');

// Testovací stránka pro editor Hugerte
Route::get('/editor-test', function () {
    return Inertia::render('EditorTest');
})->name('editor.test');

// Demo stránka pro PlaceKit Autocomplete
Route::get('/placekit-demo', function () {
    return Inertia::render('PlaceKitDemo');
})->name('placekit.demo');

// Order routes
Route::post('/orders', [ OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/confirmation', [ OrderController::class, 'confirmation'])->name('order.confirmation');

// Test uploadu obrázků z editoru Hugerte
Route::post('/editor/image-upload-test', [ EditorUploadTestController::class, 'store'])
    ->name('editor.image-upload-test');

// Dashboard routes - vyžaduje přihlášení
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/table-example', function () {
        return Inertia::render('Dashboard/TableExample');
    })->name('dashboard.table-example');
});

// Module display routes - dynamická route pro všechny moduly (vyžaduje přihlášení)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/module/{slug}', [ModuleDisplayController::class, 'show'])->name('dashboard.module');
});

// Module management routes (vyžaduje přihlášení)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/modules', [DashboardModuleController::class, 'index'])->name('dashboard.modules.index');
    Route::post('/dashboard/modules', [DashboardModuleController::class, 'store'])->name('dashboard.modules.store');
    Route::put('/dashboard/modules/{module}', [DashboardModuleController::class, 'update'])->name('dashboard.modules.update');
    Route::delete('/dashboard/modules/{module}', [DashboardModuleController::class, 'destroy'])->name('dashboard.modules.destroy');
    Route::post('/dashboard/modules/update-rank', [DashboardModuleController::class, 'updateRank'])->name('dashboard.modules.update-rank');
    Route::post('/dashboard/modules/roles/{role}/assign', [DashboardModuleController::class, 'assignModulesToRole'])->name('dashboard.modules.roles.assign');
});

// Dashboard protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/dashboard/logout', [ DashboardAuthController::class, 'logout'])->name('dashboard.logout');

    // Roles and Permissions routes
    Route::get('/dashboard/roles-permissions', [RolesPermissionsController::class, 'index'])->name('dashboard.roles-permissions.index');
    Route::post('/dashboard/roles-permissions/roles', [RolesPermissionsController::class, 'storeRole'])->name('dashboard.roles-permissions.roles.store');
    Route::put('/dashboard/roles-permissions/roles/{role}', [RolesPermissionsController::class, 'updateRole'])->name('dashboard.roles-permissions.roles.update');
    Route::delete('/dashboard/roles-permissions/roles/{role}', [RolesPermissionsController::class, 'destroyRole'])->name('dashboard.roles-permissions.roles.destroy');
    Route::post('/dashboard/roles-permissions/users/{user}/assign-role', [RolesPermissionsController::class, 'assignRole'])->name('dashboard.roles-permissions.users.assign-role');

    // Permissions routes
    Route::post('/dashboard/permissions', [RolesPermissionsController::class, 'storePermission'])->name('dashboard.permissions.store');
    Route::put('/dashboard/permissions/{permission}', [RolesPermissionsController::class, 'updatePermission'])->name('dashboard.permissions.update');
    Route::delete('/dashboard/permissions/{permission}', [RolesPermissionsController::class, 'destroyPermission'])->name('dashboard.permissions.destroy');

    // Users routes
    Route::get('/dashboard/users', [UsersController::class, 'index'])->name('dashboard.users.index');
    Route::post('/dashboard/users', [UsersController::class, 'store'])->name('dashboard.users.store');
    Route::put('/dashboard/users/{user}', [UsersController::class, 'update'])->name('dashboard.users.update');
    Route::delete('/dashboard/users/{user}', [UsersController::class, 'destroy'])->name('dashboard.users.destroy');

    // Profile routes
    Route::get('/dashboard/profile', [ProfileController::class, 'show'])->name('dashboard.profile.show');
    Route::get('/dashboard/profile/edit', [ProfileController::class, 'edit'])->name('dashboard.profile.edit');
    Route::put('/dashboard/profile', [ProfileController::class, 'update'])->name('dashboard.profile.update');

    // Transport routes
    Route::get('/dashboard/transports', [TransportController::class, 'index'])->name('dashboard.transports.index');
    Route::post('/dashboard/transports', [TransportController::class, 'store'])->name('dashboard.transports.store');
    Route::get('/dashboard/transports/{transport}/edit', [TransportController::class, 'edit'])->name('dashboard.transports.edit');
    Route::put('/dashboard/transports/{transport}', [TransportController::class, 'update'])->name('dashboard.transports.update');
    Route::post('/dashboard/transports/{transport}/payments', [TransportController::class, 'updatePayments'])->name('dashboard.transports.payments.update');
    Route::post('/dashboard/transports/order', [TransportController::class, 'updateOrder'])->name('dashboard.transports.order.update');
    Route::delete('/dashboard/transports/{transport}', [TransportController::class, 'destroy'])->name('dashboard.transports.destroy');

    // Payment routes
    Route::get('/dashboard/payments', [PaymentController::class, 'index'])->name('dashboard.payments.index');
    Route::post('/dashboard/payments', [PaymentController::class, 'store'])->name('dashboard.payments.store');
    Route::put('/dashboard/payments/{payment}', [PaymentController::class, 'update'])->name('dashboard.payments.update');
    Route::post('/dashboard/payments/order', [PaymentController::class, 'updateOrder'])->name('dashboard.payments.order.update');
    Route::delete('/dashboard/payments/{payment}', [PaymentController::class, 'destroy'])->name('dashboard.payments.destroy');

    // Distribution routes
    Route::get('/dashboard/distribution', [DistributionController::class, 'index'])->name('dashboard.distribution.index');
    Route::post('/dashboard/distribution/start', [DistributionController::class, 'start'])->name('dashboard.distribution.start');
    Route::get('/dashboard/distribution/progress/{jobId}', [DistributionController::class, 'getProgress'])->name('dashboard.distribution.progress');
    Route::get('/dashboard/distribution/stream/{jobId}', [DistributionController::class, 'streamProgress'])->name('dashboard.distribution.stream');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/dashboard/login', [ DashboardAuthController::class, 'showLogin'])->name('dashboard.login');
    Route::post('/dashboard/login', [ DashboardAuthController::class, 'login']);
    Route::get('/dashboard/register', [ DashboardAuthController::class, 'showRegister'])->name('dashboard.register');
    Route::post('/dashboard/register', [ DashboardAuthController::class, 'register']);
});

Route::prefix('basket')->group(function () {
    Route::get('/', [BasketController::class, 'index'])->name('basket.index'); // zobrazit košík
    Route::post('/', [BasketController::class, 'store'])->name('basket.store'); // přidat do košíku
    Route::put('/{productId}', [BasketController::class, 'update'])->name('basket.update'); // změnit množství
    Route::delete('/{productId}', [BasketController::class, 'destroy'])->name('basket.destroy'); // odstranit položku
    Route::delete('/', [BasketController::class, 'clear'])->name('basket.clear'); // vyprázdnit košík
});

Route::prefix('auth')->group(function(){
    Route::post('/login', [App\Http\Controllers\Web\AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [App\Http\Controllers\Web\AuthController::class, 'register'])->name('auth.register');
    Route::post('/logout', [App\Http\Controllers\Web\AuthController::class, 'logout'])->name('auth.logout');
});

// Profile routes - vyžaduje přihlášení (klientská část)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\Web\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/info', [App\Http\Controllers\Web\ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::put('/profile/preferences', [App\Http\Controllers\Web\ProfileController::class, 'updatePreferences'])->name('profile.updatePreferences');
    Route::put('/profile/password', [App\Http\Controllers\Web\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
