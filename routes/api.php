<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Models\User;
use App\Models\Post;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Register
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

// Login
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

// Creacion de posts despues del login y obteción de token
Route::middleware(['auth:sanctum', 'ability:create-post'])->post('/posts', [PostController::class, 'createPost']);

// Actualizar post 
Route::middleware(['auth:sanctum', 'ability:update-post'])->put('/posts/{id}', [PostController::class, 'updatePost']);

// Eliminar post
Route::middleware(['auth:sanctum', 'ability:delete-post'])->delete('/posts/{id}', [PostController::class, 'deletePost']);

// Obtener todos los posts por categoría
Route::get('/posts/{categoryId}', [PostController::class, 'getPostsByCategory']);


// Creacion de rutas con permisos de post para test
Route::middleware(['auth:sanctum', 'ability:create-post'])->get('/post/create', function (Request $request) {
    return [
        'id' => $request->id,
        'title' => $request->title,
        'content' => $request->content,
    ];
});


Route::post('/c_category', [CategoryController::class, 'createCategory']);
Route::get('/categories', [CategoryController::class, 'getCategory']);