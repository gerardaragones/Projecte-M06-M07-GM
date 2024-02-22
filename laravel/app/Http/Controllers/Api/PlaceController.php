<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\File;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Review;

class PlaceController extends Controller
{

    public function index()
    {
        $places = Place::all();

        if ($places) {
            return response()->json([
                'success' => true,
                'data'    => $places,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar archivos',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'upload'      => 'required|mimes:gif,jpeg,jpg,png|max:2048',
            'longitude'   => 'required',
            'latitude'    => 'required',
        ]);

        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        $file = new File();
        $ok = $file->diskSave($upload);
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
           'uploads',
           $uploadName ,
           'public'
        );

        if ($ok) {
            $file_id = File::where('filepath',$filePath)->where('filesize',$fileSize)->first();
            if ($file_id){
                $place = Place::create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'file_id' => $file_id->id,
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'author_id' => auth()->user()->id,
                ]);
                return response()->json([
                    'success' => true,
                    'data'    => $place
                ], 201);
            } else {
                return response()->json([
                    'success'  => false,
                    'message' => 'Error creating place'
                ], 500);
            }

        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error uploading file'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $place = Place::find($id);
        if ($place) {
            return response()->json([
                'success' => true,
                'data' => $place,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error showing a file',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $place = Place::find($id);
        if ($place) {
            $oldfilePath = $place->file->filepath;
            
            $validatedData = $request->validate([
            'upload' => 'nullable|mimes:gif,jpeg,jpg,png|max:1024'
        ]);
            if ($request->hasFile('upload')){
            $upload = $request->file('upload');
            $fileName = $upload->getClientOriginalName();
            $fileSize = $upload->getSize();
            $uploadName = time() . '_' . $fileName;
            $filePath = $upload->storeAs(
                'uploads',      // Path
                $uploadName ,   // Filename
                'public'        // Disk
            );
            
            if (\Storage::disk('public')->exists($filePath)) {
                $fullPath = \Storage::disk('public')->path($filePath);
                $place->file->update([
                'filepath' => $filePath,
                'filesize' => $fileSize,
                ]);
                \Storage::disk('public')->delete($oldfilePath);
                $file_id = File::where('filepath',$filePath)->where('filesize',$fileSize)->first();
                if ($file_id){
                $place->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'file_id' => $file_id->id,
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                ]);
                return response()->json([
                    'success' => true,
                    'data' => $place,
                ], 200);
            } else {
                return response()->json([
                    'success'  => false,
                    'message' => 'Error creating file'
                ], 404);
            }
            }
        }else {
            $file_id = File::where('filepath',$place->file->filepath)->where('filesize',$place->file->filesize)->first();
            if ($file_id){
                $place->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'file_id' => $file_id->id,
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                ]);
                return response()->json([
                    'success' => true,
                    'data' => $place,
                ], 200);
            } else {
                return response()->json([
                    'success'  => false,
                    'message' => 'Error editing place'
                ], 404);
            }
        }
        }else {
            return response()->json([
                'success'  => false,
                'message' => 'Error finding place'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $place = Place::find($id);

        if ($place) {
            $file = File::find($place->file_id);
            $place->delete();
            $file->diskDelete();
            $file->delete();

            return response()->json([
                'success' => true,
                'data' => $place,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        }
    }

    public function favorite(Request $request, string $id)
    {
        $place = Place::where('id',$id)->first();
        if ($place){
            $author = User::where('id', $request->user()->id)->first();
            if ($author){
                $favorite = Favorite::where('place_id',$id)->where('user_id', $request->user()->id)->first();
                if ($favorite){
                    try{
                        $favorite->delete();
                        return response()->json([
                            'success' => true,
                            'data' => 'favorite eliminado' . $favorite,
                        ], 200);
                    } catch (Exception $e){
                            return response()->json([
                                'success' => false,
                                'message' => 'Error al eliminar el favorite ' . $e,
                            ], 404);

                        }
                } else{
                    $favorite = Favorite::create([
                        'user_id' => $request->user()->id,
                        'place_id' => $id,
                    ]);
                    if ($favorite){
                        return response()->json([
                            'success' => true,
                            'data' => 'favorite creado' . $favorite,
                        ], 200);
                    }
                    else{
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al crear el favorite',
                        ], 404);
                    }
                }
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Author no encontrado',
                ], 404);

            }
        } else{
            return response()->json([
                'success' => false,
                'message' => 'Place no encontrado',
            ], 404);
        }
        
    }

    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }
}
