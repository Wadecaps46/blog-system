<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\categoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->input('email'))->first();

    if (!$user || !Hash::check($request->password) != $user->password) {
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
ROute::get('/categories', [CategoryController::class, 'getCategory']); 