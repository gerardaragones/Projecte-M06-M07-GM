<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Place;
use App\Models\Review;

class ReviewController extends Controller
{
    // Mostrar el formulario para crear una nueva reseña
    public function create(Place $place)
    {
        return view('reviews.create', compact('place'));
    }

    // Almacenar una nueva reseña
    public function store(Request $request, Place $place)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $request->validate([
                'comment' => 'required|string',
                'rating' => 'required|numeric|min:1|max:10', // Actualizado el rango de rating a 1-10
                // Puedes agregar más validaciones según tus necesidades
            ]);
    
            // Obtener el ID del usuario autenticado
            $userId = Auth::id();
    
            // Crear la revisión con el user_id
            $place->reviews()->create([
                'comment' => $request->input('comment'),
                'rating'  => $request->input('rating'),
                'user_id' => $userId,
            ]);
    
            return redirect()->route('places.show', $place)
                ->with('success', 'Review created successfully');
        } else {
            // Si el usuario no está autenticado, redirigir o mostrar un mensaje de error
            return redirect()->route('login')->with('error', 'You must be logged in to create a review');
        }
    }

    // Eliminar una reseña
    public function destroy(Place $place, Review $review)
    {
        // Verificar si el usuario autenticado es el propietario de la revisión
        if ($review->user_id === Auth::id()) {
            $review->delete();
            return redirect()->route('places.show', $place)
                ->with('success', 'Review deleted successfully');
        } else {
            // Si el usuario no es el propietario de la revisión, redirigir o mostrar un mensaje de error
            return redirect()->route('places.show', $place)
                ->with('error', 'You are not authorized to delete this review');
        }
    }
}
