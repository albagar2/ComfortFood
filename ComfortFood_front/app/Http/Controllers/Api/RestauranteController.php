<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    /**
     * Display a listing of restaurants.
     */
    public function index()
    {
        $restaurantes = Restaurante::with(['user'])->whereHas('user', function ($q) {
            $q->where('es_activo', true);
        })->get();

        return response()->json([
            'success' => true,
            'data' => $restaurantes
        ]);
    }

    /**
     * Display the specified restaurant.
     */
    public function show($id)
    {
        $restaurante = Restaurante::with(['user', 'menus', 'horarios', 'resenas'])->find($id);

        if (!$restaurante) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurante no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $restaurante
        ]);
    }
}
