<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\File;
use App\Models\Like;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        if ($posts) {
            return response()->json([
                'success' => true,
                'data'    => $posts,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar posts',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'body'        => 'required',
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
            $file_id = File::where('filepath', $filePath)->where('filesize', $fileSize)->first();
            if ($file_id) {
                $post = Post::create([
                    'body' => $request->input('body'),
                    'file_id' => $file_id->id,
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'author_id' => auth()->user()->id,
                ]);
                return response()->json([
                    'success' => true,
                    'data'    => $post
                ], 201);
            } else {
                return response()->json([
                    'success'  => false,
                    'message' => 'Error creating post'
                ], 500);
            }

        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error uploading file'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        if ($post) {
            return response()->json([
                'success' => true,
                'data' => $post,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error showing a post',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if ($post) {
            $oldfilePath = $post->file->filepath;

            $validatedData = $request->validate([
                'upload' => 'nullable|mimes:gif,jpeg,jpg,png|max:1024'
            ]);
            if ($request->hasFile('upload')) {
                $upload = $request->file('upload');
                $fileName = $upload->getClientOriginalName();
                $fileSize = $upload->getSize();
                $uploadName = time() . '_' . $fileName;
                $filePath = $upload->storeAs(
                    'uploads',      // Path
                    $uploadName,   // Filename
                    'public'        // Disk
                );

                if (\Storage::disk('public')->exists($filePath)) {
                    $fullPath = \Storage::disk('public')->path($filePath);
                    $post->file->update([
                        'filepath' => $filePath,
                        'filesize' => $fileSize,
                    ]);
                    \Storage::disk('public')->delete($oldfilePath);
                    $file_id = File::where('filepath', $filePath)->where('filesize', $fileSize)->first();
                    if ($file_id) {
                        $post->update([
                            'body' => $request->input('body'),
                            'file_id' => $file_id->id,
                            'latitude' => $request->input('latitude'),
                            'longitude' => $request->input('longitude'),
                        ]);
                        return response()->json([
                            'success' => true,
                            'data' => $post,
                        ], 200);
                    } else {
                        return response()->json([
                            'success'  => false,
                            'message' => 'Error creating file'
                        ], 500);
                    }
                }
            } else {
                $file_id = File::where('filepath', $post->file->filepath)->where('filesize', $post->file->filesize)->first();
                if ($file_id) {
                    $post->update([
                        'body' => $request->input('body'),
                        'file_id' => $file_id->id,
                        'latitude' => $request->input('latitude'),
                        'longitude' => $request->input('longitude'),
                    ]);
                    return response()->json([
                        'success' => true,
                        'data' => $post,
                    ], 200);
                } else {
                    return response()->json([
                        'success'  => false,
                        'message' => 'Error editing post'
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error finding post'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);

        if ($post) {
            $file = File::find($post->file_id);
            $post->delete();
            $file->diskDelete();
            $file->delete();

            return response()->json([
                'success' => true,
                'data' => $post,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }
    }

    public function like(Request $request, string $id)
    {
        $post = Post::find($id);
        if ($post) {
            $author = User::where('id', $request->user()->id)->first();
            if ($author) {
                $like = Like::where('post_id', $id)->where('user_id', $request->user()->id)->first();
                
                if ($like) {
                    try {
                        $like->delete();
                        return response()->json([
                            'success' => true,
                            'data' => 'like eliminado' . $like,
                        ], 200);
                    } catch (Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al eliminar el like ' . $e,
                        ], 404);
                    }
                } else {
                    $like = Like::create([
                        'user_id' => $request->user()->id,
                        'post_id' => $id,
                    ]);

                    if ($like) {
                        return response()->json([
                            'success' => true,
                            'data' => 'like creado' . $like,
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al crear el like',
                        ], 404);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Author no encontrado',
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post no encontrado',
            ], 404);
        }
    }

    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }
}
