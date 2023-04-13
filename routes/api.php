<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::get('/home', [HomeController::class, 'index']);


Route::get('/products', [ProductController::class, 'index']);
Route::get('/filter-data', [ProductController::class, 'filter']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/product', [ProductController::class, 'search']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/data', [DashboardController::class, 'getData']);
    Route::get('/me', [AuthController::class, 'authUser']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::patch('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{productId}', [ProductController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/billing', [BillingController::class, 'index']);
    Route::post('/upsert-billing', [BillingController::class, 'upsert']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::get('/my-order/{status}', [OrderController::class, 'myOrder']);
    Route::post('/order-status/{id}', [OrderController::class, 'updateStatus']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('upsert-profile', [ProfileController::class, 'upsert']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);
    Route::get('/all-order/{status}', [OrderController::class, 'allOrder']);
});


Route::post('/upload', [UploadController::class, 'upload']);

// Route::middleware(['admin'])->group(function () {
//     // your admin-only routes here
// });