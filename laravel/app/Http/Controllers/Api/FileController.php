<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::all();

        return response()->json([
            'success' => true,
            'data'    => $files
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:2048'
        ]);
        // Desar fitxer al disc i inserir dades a BD
        $upload = $request->file('upload');
        $file = new File();
        $ok = $file->diskSave($upload); // ⚠️ Mètode solució profe!!! ⚠️


        if ($ok) {
            return response()->json([
                'success' => true,
                'data'    => $file
            ], 201);
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
        $file = File::find($id);

        if ($file) {
            return response()->json([
                'success' => true,
                'data'    => $file
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar datos de entrada
        $validatedData = $request->validate([
            'upload' => 'mimes:gif,jpeg,jpg,png|max:2048' // Puedes ajustar las reglas según tus necesidades
        ]);

        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        // Actualizar información del archivo si se proporciona un nuevo archivo
        if ($request->hasFile('upload')) {
            $newUpload = $request->file('upload');
            $ok = $file->diskSave($newUpload); // ⚠️ Puedes ajustar esta lógica según tus necesidades ⚠️

            if (!$ok) {
                return response()->json([
                    'success'  => false,
                    'message' => 'Error updating file'
                ], 500);
            }
        }

        // Puedes agregar lógica adicional para actualizar otros campos del archivo si es necesario

        return response()->json([
            'success' => true,
            'data'    => $file,
            'message' => 'File updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
            'data'    => $file
        ], 200);
    }
}
