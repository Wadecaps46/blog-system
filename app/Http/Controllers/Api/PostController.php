<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


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
}
