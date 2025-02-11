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

    public function updatePost(Request $request, $id)
    {

        //Se busca la publicación por su ID
        $post = Post::find($id);

        // Verificamos si la publicación existe
        if (!$post) {
            return response()->json(['message' => 'Post no encontrado'], 404);
        }

        // Verificar si el usuario autenticado es el propietario del post
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para actualizar este post'], 403);
        }        

        // Validamos los datos de la solicitud
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'content' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id', // Validar que la categoría exista
        ]);
     
        // Si la validación falla, retorna errores junto con los datos actuales de la publicación
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'current_data' => [ // Devuelve los datos actuales de la publicación
                    'title' => $post->title,
                    'content' => $post->content,
                    'category_id' => $post->category_id,
                ],
                'status' => 400,
            ], 400);
        }

        if (empty($request->all())) {
            return response()->json([
                'message' => 'No se proporcionaron datos para actualizar',
                'current_data' => [
                    'title' => $post->title,
                    'content' => $post->content,
                    'category_id' => $post->category_id,
                ],
                'status' => 200,
            ], 200);
        }
        
        // Si todo sale bien, se actualizara la publicación
        $post->update($request->only(['title', 'content', 'category_id']));

        // Respuesta si se hizo exitosamente
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ], 200);

    }

    public function deletePost(Request $request, $id)
    {
        $post = Post::find($id);
    
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    
        // Verifica que el usuario autenticado sea el dueño de la publicación
        if ($post->user_id !== $request->user()->id)  {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $post->delete();
    
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
