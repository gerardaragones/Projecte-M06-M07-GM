<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Review;

class ReviewController extends Controller
{
    // Listar todas las reseñas de un lugar específico
    public function index(Request $request, Place $place)
    {
        // Obtener las revisiones asociadas al lugar
        $reviews = $place->reviews();

        // Aplicar búsqueda si hay un término de búsqueda
        $search = $request->input('search');
        if ($search) {
            $reviews->where('content', 'like', '%' . $search . '%');
        }

        // Paginar los resultados
        $reviews = $reviews->paginate(10);

        // Definir la variable $content
        $content = "Contenido de ejemplo";

        // Pasar las revisiones y la variable de lugar a la vista
        return view('reviews.index', compact('reviews', 'place', 'search', 'content'));
    }

    // Mostrar el formulario para crear una nueva reseña
    public function create(Place $place)
    {
        return view('reviews.create', compact('place'));
    }

    // Almacenar una nueva reseña
    public function store(Request $request, Place $place)
    {
        $request->validate([
            'content' => 'required|string',
            // Aquí puedes agregar más validaciones según tus necesidades
        ]);

        $place->reviews()->create($request->all());

        return redirect()->route('places.reviews.index', $place)
            ->with('success', 'Review created successfully');
    }

    // Mostrar una reseña específica
    public function show(Place $place, Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    // Mostrar el formulario para editar una reseña
    public function edit(Place $place, Review $review)
    {
        return view('reviews.edit', compact('review'));
    }

    // Actualizar una reseña existente
    public function update(Request $request, Place $place, Review $review)
    {
        $request->validate([
            'content' => 'required|string',
            // Aquí puedes agregar más validaciones según tus necesidades
        ]);

        $review->update($request->all());

        return redirect()->route('places.reviews.index', $place)
            ->with('success', 'Review updated successfully');
    }

    // Eliminar una reseña
    public function destroy(Place $place, Review $review)
    {
        $review->delete();

        return redirect()->route('places.reviews.index', $place)
            ->with('success', 'Review deleted successfully');
    }
}
