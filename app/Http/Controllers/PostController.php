<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Like; 

class PostController extends Controller
{
    private bool $_pagination = true;

    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Request $request)
    {
        $collectionQuery = Post::withCount('liked')->orderBy('created_at', 'desc');
    
        // ¿Filtrar?
        if ($search = $request->get('search')) {
            $collectionQuery->where('body', 'like', "%{$search}%");
        }
        
        // Paginación
        $posts = $this->_pagination 
            ? $collectionQuery->paginate(5)->withQueryString() 
            : $collectionQuery->get();
        
        return view("posts.index", [
            "posts" => $posts,
            "search" => $search
        ]);
    }

    public function create()
    { 
        return view("posts.create");  
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'body'      => 'required',
            'upload'    => 'required|mimes:gif,jpeg,jpg,png,mp4|max:2048',
            'latitude'  => 'required',
            'longitude' => 'required',
        ]);
        
        $body      = $request->get('body');
        $upload    = $request->file('upload');
        $latitude  = $request->get('latitude');
        $longitude = $request->get('longitude');

        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            Log::debug("Guardando post en la BD...");
            $post = Post::create([
                'body'      => $body,
                'file_id'   => $file->id,
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'author_id' => auth()->user()->id,
            ]);
            Log::debug("Almacenamiento en la BD OK");
            return redirect()->route('posts.show', $post)
                ->with('success', __('Post guardado exitosamente'));
        } else {
            return redirect()->route("posts.create")
                ->with('error', __('ERROR al subir el archivo'));
        }
    }

    public function show(Post $post)
    {
        $user = Auth::user();
        $post->loadCount('liked');
    
        // Obtener la lista de usuarios que han dado like al post
        $usersWhoLiked = $post->liked;
    
        return view("posts.show", [
            'post'          => $post,
            'file'          => $post->file,
            'author'        => $post->user,
            'userLikedPost' => $user && $usersWhoLiked->contains('id', $user->id),
            'usersWhoLiked' => $usersWhoLiked,
        ]);       
    }       

    public function edit(Post $post)
    {
        return view("posts.edit", [
            'post'   => $post,
            'file'   => $post->file,
            'author' => $post->user,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'body'      => 'required',
            'upload'    => 'nullable|mimes:gif,jpeg,jpg,png,mp4|max:2048',
            'latitude'  => 'required',
            'longitude' => 'required',
        ]);

        $body      = $request->get('body');
        $upload    = $request->file('upload');
        $latitude  = $request->get('latitude');
        $longitude = $request->get('longitude');

        if (is_null($upload) || $post->file->diskSave($upload)) {
            Log::debug("Actualizando la BD...");
            $post->body      = $body;
            $post->latitude  = $latitude;
            $post->longitude = $longitude;
            $post->save();
            Log::debug("Almacenamiento en la BD OK");
            return redirect()->route('posts.show', $post)
                ->with('success', __('Post guardado exitosamente'));
        } else {
            return redirect()->route("posts.edit")
                ->with('error', __('ERROR al subir el archivo'));
        }
    }

    public function destroy(Post $post)
    {
        $post->delete();
        $post->file->diskDelete();
        return redirect()->route("posts.index")
            ->with('success', __('Post eliminado exitosamente'));
    }

    public function delete(Post $post)
    {
        return view("posts.delete", [
            'post' => $post
        ]);
    }

    public function like(Post $post)
    {
        $this->authorize('like', $post);
        if (auth()->check()) {
            $existingLike = Like::where('user_id', auth()->user()->id)
                                ->where('post_id', $post->id)
                                ->first();

            if (!$existingLike) {
                Like::create([
                    'user_id' => auth()->user()->id,
                    'post_id' => $post->id,
                ]);
                return redirect()->route('posts.show', $post)
                    ->with('success', __('Post liked successfully'));            }
            else {
                return response()->json(['error' => 'User already liked the post.'], 400);
            }
        } else {
            return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
        }
    }

    public function unlike(Post $post)
    {
        if (auth()->check()) {
            $like = Like::where('user_id', auth()->user()->id)
                        ->where('post_id', $post->id)
                        ->first();

            if ($like) {
                $like->delete();
                return redirect()->route('posts.show', $post)
                    ->with('success', __('Post unliked successfully'));            }
            else {
                return response()->json(['error' => 'User did not like the post.'], 400);
            }
        } else {
            return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
        }
    }
}