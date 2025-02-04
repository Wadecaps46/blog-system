<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);


Route::middleware(['auth:sanctum', 'ability:create-post'])->get('/posts/create', function (Request $request) {
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