<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Place;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Place $place)
    {
        $reviews = $place->reviews()->get();

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ], 200);
    }

    public function store(Request $request, Place $place)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $validatedData = $request->validate([
                'comment' => 'required',
                'rating'  => 'required|numeric|min:1|max:10', // Actualizado el rango de rating a 1-10
            ]);
    
            // Obtener el ID del usuario autenticado
            $userId = Auth::id();
    
            $review = new Review([
                'comment' => $request->input('comment'),
                'rating'  => $request->input('rating'),
                'user_id' => $userId,
            ]);
    
            $place->reviews()->save($review);
    
            return response()->json([
                'success' => true,
                'data'    => $review,
            ], 201);
        } else {
            // Si el usuario no está autenticado, retornar un error de no autorizado
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401);
        }
    }

    public function show(Place $place, Review $review)
    {
        return response()->json([
            'success' => true,
            'data'    => $review,
        ], 200);
    }

    public function update(Request $request, Place $place, Review $review)
    {
        $validatedData = $request->validate([
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:10', // Actualizado el rango de rating a 1-10
        ]);

        $review->comment = $request->input('comment');
        $review->rating = $request->input('rating');
        $review->save();

        return response()->json([
            'success' => true,
            'data'    => $review,
        ], 200);
    }

    public function destroy(Place $place, Review $review)
    {
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ], 200);
    }

    public function update_workaround(Request $request, Place $place, Review $review)
    {
        return $this->update($request, $place, $review);
    }
}
