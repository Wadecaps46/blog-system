<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getCategory()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No hay categorias', 'status' => 200]);
        }

        return response()->json($categories, 200);
    }

    public function createCategory(Request $request)
    {
        {
//             // Validar los datos de la solicitud
//             $request->validate([
//                 'name' => 'required|string|max:255',
//                 'description' => 'nullable|string', // La descripción es opcional
//             ]);
    
//             // Crear una nueva instancia del modelo Category
//             $category = new Category();
    
//             // Asignar los valores de la solicitud a los atributos del modelo
//             $category->name = $request->input('name');
//             $category->description = $request->input('description');
    
//             // Guardar el nuevo registro en la base de datos
//             $category->save();
    
//             // Devolver una respuesta con el código de estado 201 (Created)
//             return response()->json(['message' => 'Categoría creada exitosamente'], 201);
//         }
//     }    
//  }   
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validación',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }

            $category = Category::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if (!$category) {
                return response()->json(['message' => 'Error al crear la categoria', 'status' => 500]);
            }

            $data = [
                'message' => 'Categoria creada correctamente',
                'status' => 201
            ];

            return response()->json($data, 201);
        }

    }
}