<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class PlaceController extends Controller
{
    private bool $_pagination = true;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collectionQuery = Place::orderBy('created_at', 'desc');

        // Filter?
        if ($search = $request->get('search')) {
            $collectionQuery->where('description', 'like', "%{$search}%");
        }
        
        // Pagination
        $places = $this->_pagination 
            ? $collectionQuery->paginate(5)->withQueryString() 
            : $collectionQuery->get();
        
        return view("places.index", [
            "places" => $places,
            "search" => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("places.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar dades del formulari
        $validatedData = $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'upload'      => 'required|mimes:gif,jpeg,jpg,png,mp4|max:2048',
            'latitude'    => 'required',
            'longitude'   => 'required',
        ]);
        
        // Obtenir dades del formulari
        $name        = $request->get('name');
        $description = $request->get('description');
        $upload      = $request->file('upload');
        $latitude    = $request->get('latitude');
        $longitude   = $request->get('longitude');

        // Desar fitxer al disc i inserir dades a BD
        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            // Desar dades a BD
            Log::debug("Saving place at DB...");
            $place = Place::create([
                'name'        => $name,
                'description' => $description,
                'file_id'     => $file->id,
                'latitude'    => $latitude,
                'longitude'   => $longitude,
                'author_id'   => auth()->user()->id,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
                ->with('success', __('Place successfully saved'));
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        $user = Auth::user();
        $place->loadCount('favorited');
    
        // Obtener la lista de usuarios que han dado like al post
        $usersWhoFavorited = $place->favorited;
    
        return view("places.show", [
            'place'  => $place,
            'file'   => $place->file,
            'author' => $place->user,
            'userFavoritedPlace' => $user && $usersWhoFavorited->contains('id', $user->id),
            'usersWhoFavorited' => $usersWhoFavorited,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        return view("places.edit", [
            'place'  => $place,
            'file'   => $place->file,
            'author' => $place->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        // Validar dades del formulari
        $validatedData = $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'upload'      => 'nullable|mimes:gif,jpeg,jpg,png,mp4|max:2048',
            'latitude'    => 'required',
            'longitude'   => 'required',
        ]);
        
        // Obtenir dades del formulari
        $name        = $request->get('name');
        $description = $request->get('description');
        $upload      = $request->file('upload');
        $latitude    = $request->get('latitude');
        $longitude   = $request->get('longitude');

        // Desar fitxer (opcional)
        if (is_null($upload) || $place->file->diskSave($upload)) {
            // Actualitzar dades a BD
            Log::debug("Updating DB...");
            $place->name        = $name;
            $place->description = $description;
            $place->latitude    = $latitude;
            $place->longitude   = $longitude;
            $place->save();
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
                ->with('success', __('Place successfully saved'));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        // Eliminar place de BD
        $place->delete();
        // Eliminar fitxer associat del disc i BD
        $place->file->diskDelete();
        // Patró PRG amb missatge d'èxit
        return redirect()->route("places.index")
            ->with('success', 'Place successfully deleted');
    }

    /**
     * Confirm specified resource deletion from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function delete(Place $place)
    {
        return view("places.delete", [
            'place' => $place
        ]);
    }

    // public function favorite(Place $place)
    // {
    //     $fav = new Favorite(['user_id' => auth()->user()->id]);
    //     $place->favorites()->save($fav);

    //     return response()->json(['message' => 'Place favorito exitosamente']);
    // }

    // public function unfavorite(Place $place)
    // {
    //     $place->favorites()->where('user_id', auth()->user()->id)->delete();

    //     return response()->json(['message' => 'Place quitado de favorito exitosamente']);
    // }

    public function favorite(Place $place)
    {
        if (auth()->check()) {
            $existingFavorite = Favorite::where('user_id', auth()->user()->id)
                                ->where('place_id', $place->id)
                                ->first();

            if (!$existingFavorite) {
                Favorite::create([
                    'user_id' => auth()->user()->id,
                    'place_id' => $place->id,
                ]);
                return redirect()->route('places.show', $place)
                    ->with('success', __('Place favorited successfully'));            }
            else {
                return response()->json(['error' => 'User already favorited the place.'], 400);
            }
        } else {
            return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
        }
    }

    public function unfavorite(Place $place)
    {
        if (auth()->check()) {
            $favorite = Favorite::where('user_id', auth()->user()->id)
                        ->where('place_id', $place->id)
                        ->first();

            if ($favorite) {
                $favorite->delete();
                return redirect()->route('places.show', $place)
                    ->with('success', __('Place unfavorited successfully'));            }
            else {
                return response()->json(['error' => 'User did not favorite the place.'], 400);
            }
        } else {
            return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
        }
    }
}
