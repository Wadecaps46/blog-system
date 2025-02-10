<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Post;

class PostController extends Controller
{
    public function getPostsByCategory($categoryId)
    {
        $category = Category::with('posts')->find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        return response()->json([
            'category' => $category->name,
            'posts' => $category->posts
        ]);
    }

    public function createPost(Request $request)
    {
        // Validar los datos del post con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'content.required' => 'El contenido es obligatorio.',
            'content.string' => 'El contenido debe ser una cadena de texto.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ]);

        if ($validator->fails()) {
            $categories = Category::all(['id', 'name']); // Obtiene todas las categorías para mostrarlas en el formulario
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'categories' => $categories, // Incluye las categorías en la respuesta
                'status' => 400
            ], 400);
        }

        // Crear el post y asociarlo al usuario autenticado
        $post = Post::create([
            'title' => $validator->validated()['title'],
            'content' => $validator->validated()['content'],
            'category_id' => $validator->validated()['category_id'],
            'user_id' => $request->user()->id, // Asigna el post al usuario autenticado
        ]);

        return response()->json([
            'message' => 'Post creado exitosamente',
            'post' => $post
        ], 201);
    }
}
