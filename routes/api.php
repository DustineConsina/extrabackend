<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController;


Route::get('/books', [BookController::class, 'index']);
Route::post('/books/{id}/borrow', [BookController::class, 'borrow']);
Route::post('/books/{id}/return', [BookController::class, 'return']);

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/books', [BookController::class, 'index']);       // List all books
    Route::get('/books/{id}', [BookController::class, 'show']);   // View single book
    Route::post('/books', [BookController::class, 'store']);      // Create a book
    Route::put('/books/{id}', [BookController::class, 'update']); // Update a book
    Route::delete('/books/{id}', [BookController::class, 'destroy']); // Delete
});



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::apiResource('books', BookController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->middleware('admin'); // Apply admin middleware to all book routes
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
