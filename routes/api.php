<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController;

// 📌 Public Routes (No Auth)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/books', [BookController::class, 'index']); // List books for users

// Public route for user dashboard
Route::get('/user/books', [BookController::class, 'userBooks']);

// 📌 User Routes (Requires Auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/books/{id}/borrow', [BookController::class, 'borrow']);
    Route::post('/books/{id}/return', [BookController::class, 'return']);
});

// 📌 Admin Routes (Requires Auth + Admin)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Admin Book Management
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    // Admin User Management
    Route::get('/users', [UserController::class, 'index']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
});
