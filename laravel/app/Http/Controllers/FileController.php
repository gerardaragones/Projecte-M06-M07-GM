<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;



class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("files.index", [
            "files" => File::all()
        ]);
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("files.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
        ]);
       
        // Obtenir dades del fitxer
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        \Log::debug("Storing file '{$fileName}' ($fileSize)...");
 
 
        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Filename
            'public'        // Disk
        );
       
        if (Storage::disk('public')->exists($filePath)) {
            \Log::debug("Disk storage OK");
            $fullPath = Storage::disk('public')->path($filePath);
            \Log::debug("File saved at {$fullPath}");
            // Desar dades a BDx
            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('files.show', $file)
                ->with('success', 'File successfully saved');
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("files.create")
                ->with('error', 'ERROR uploading file');
        }
    } 

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        $filePath = $file->filepath;
        if (Storage::disk('public')->exists($filePath)) {
            return view('files.show', compact('file'));
        }else{
            return redirect()->route('files');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        return view('files.edit', compact('file'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        // Validar el nuevo archivo
        $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:2048',
        ]);
    
        // Obtener el nuevo archivo de la solicitud
        $newUpload = $request->file('upload');
        $newFileName = $newUpload->getClientOriginalName();
        $newFileSize = $newUpload->getSize();
    
        // Eliminar el archivo existente del disco
        if (Storage::disk('public')->exists($file->filepath)) {
            Storage::disk('public')->delete($file->filepath);
        }
    
        // Subir el nuevo archivo al disco duro
        $newUploadName = time() . '_' . $newFileName;
        $newFilePath = $newUpload->storeAs(
            'uploads',      // Ruta
            $newUploadName, // Nombre de archivo
            'public'        // Disco
        );
    
        // Actualizar los datos del archivo en la base de datos
        $file->filepath = $newFilePath;
        $file->filesize = $newFileSize;
        $file->save();

        if ($file->exists) {
            return redirect()->route('files.show', $file)
                ->with('success', 'File successfully updated');
        } else {
            return redirect()->route('files.edit', $file)
                ->with('error', 'Error updating file');
        }
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        if (Storage::disk('public')->exists($file->filepath)) {
            Storage::disk('public')->delete($file->filepath);
        }

        // Eliminar el registro de la base de datos
        $file->delete();

        if ($file->exists) {
            return redirect()->route('files.show', $file)
                ->with('success', 'Error updating file' );
        } else {
            return redirect()->route('files.index', $file)
                ->with('error', 'File successfully deleted');
        }
    }
    
}
