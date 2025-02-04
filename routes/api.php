<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use App\Models\Post;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'ability:create-post'])->post('/posts', function (Request $request) {
    // Validar los datos del post
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
    ]);

    // Crear el post y asociarlo al usuario autenticado
    $post = Post::create([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'category_id' => $validated['category_id'],
        'user_id' => $request->user()->id, // Asigna el post al usuario autenticado
    ]);

    return response()->json([
        'message' => 'Post creado exitosamente',
        'post' => $post
    ], 201);
});



// Creacion de rutas con permisos de post para test
Route::middleware(['auth:sanctum', 'ability:create-post'])->get('/post/create', function (Request $request) {
    return [
        'id' => $request->id,
        'title' => $request->title,
        'content' => $request->content,
    ];
});

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->input('email'))->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    return response()->json([
        'user' => [
            'name' => $user->name,
            'email' => $user->email,
        ],
        'token' => $user->createToken('api')->plainTextToken,
    ]);
});

Route::post('/c_category', [CategoryController::class, 'createCategory']);
Route::get('/categories', [CategoryController::class, 'getCategory']);