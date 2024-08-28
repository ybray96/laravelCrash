<?php

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\PostController;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::get('/posts', [PostController::class, 'index']);
//     Route::post('/posts', [PostController::class, 'store']);
//     Route::get('/posts/{post}', [PostController::class, 'show']);
//     Route::put('/posts/{post}', [PostController::class, 'update']);
//     Route::delete('/posts/{post}', [PostController::class, 'destroy']);
// });

// Route::middleware('guest')->group(function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
// });

// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
