<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home', [HomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/filter', [ProductController::class, 'filter']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/product', [ProductController::class, 'search']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/product', [ProductController::class, 'store']);
    Route::delete('/product/{productId}', [ProductController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/billing', [BillingController::class, 'index']);
    Route::post('/upsert-billing', [BillingController::class, 'upsert']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::post('/order-status/{id}', [OrderController::class, 'updateStatus']);
    Route::get('/all-order', [OrderController::class, 'allOrder']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('upsert-profile', [ProfileController::class, 'upsert']);
});
Route::post('/upload', [UploadController::class, 'upload']);
