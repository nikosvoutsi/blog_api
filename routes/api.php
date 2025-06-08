<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoriesController;

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

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

//Return a list of all posts with their tags, categories and author
Route::middleware('auth:sanctum')->get('/get-all-posts', [PostController::class, 'index']);
//create a post
Route::middleware('auth:sanctum')->post('/create-post', [PostController::class, 'store']);
//update a post
Route::middleware('auth:sanctum')->post('/update-post/{post_id}', [PostController::class, 'update']);
//delete a post
Route::middleware('auth:sanctum')->post('/delete-post/{post_id}', [PostController::class, 'delete']);
//get a single post
Route::middleware('auth:sanctum')->get('/get-post/{id}', [PostController::class, 'getPost']);
//create comment
Route::middleware('auth:sanctum')->post('/create-comment', [CommentController::class, 'store']);
//update comment
Route::middleware('auth:sanctum')->post('/update-comment/{comment_id}', [CommentController::class, 'update']);
//delete comment
Route::middleware('auth:sanctum')->post('/delete-comment/{comment_id}', [CommentController::class, 'delete']);
//get all posts of a user
Route::middleware('auth:sanctum')->get('/posts/{user_id}', [PostController::class, 'getUserPosts']);
//get all comments of a user
Route::middleware('auth:sanctum')->get('/comments/{user_id}', [CommentController::class, 'getUserComments']);
//get all categories
Route::middleware('auth:sanctum')->get('/get-all-categories', [CategoriesController::class, 'getAllCategories']);
