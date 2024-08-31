<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
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




Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/verify', [VerificationController::class, 'verify']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tags', [TagsController::class, 'index']);          // View all tags
    Route::post('/tags', [TagsController::class, 'store']);         // Store new tag
    Route::put('/tags/{tag}', [TagsController::class, 'update']);   // Update a single tag
    Route::delete('/tags/{tag}', [TagsController::class, 'destroy']);// Delete a single tag
});




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);            // View only user's posts
    Route::post('/posts', [PostController::class, 'store']);           // Store a new post
    Route::get('/posts/{post}', [PostController::class, 'show']);      // View a single post
    Route::put('/posts/{post}', [PostController::class, 'update']);    // Update a single post
    Route::delete('/posts/{post}', [PostController::class, 'destroy']); // Soft delete a post
    Route::get('/deleted-posts', [PostController::class, 'deleted']);  // View deleted posts
    Route::post('/restore-post/{id}', [PostController::class, 'restore']); // Restore a deleted post
});

Route::middleware('auth:sanctum')->get('/stats', [StatsController::class, 'stats']);

