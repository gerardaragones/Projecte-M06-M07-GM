<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->get();

        return response()->json([
            'success' => true,
            'data'    => $comments,
        ], 200);
    }

    public function store(Request $request, Post $post)
    {
        if (Auth::check()) {
            $validatedData = $request->validate([
                'title'       => 'required',
                'description' => 'required',
                'rating'      => 'required|numeric|min:1|max:5',
            ]);

            $userId = Auth::id();

            $comment = new Comment([
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'rating'      => $request->input('rating'),
                'user_id'     => $userId,
            ]);

            $post->comments()->save($comment);

            return response()->json([
                'success' => true,
                'data'    => $comment,
            ], 201);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401);
        }
    }

    public function show(Post $post, Comment $comment) // Cambiado de Place a Post y de Review a Comment
    {
        return response()->json([
            'success' => true,
            'data'    => $comment, // Cambiado de reviews a comments
        ], 200);
    }

    public function update(Request $request, Post $post, Comment $comment) // Cambiado de Place a Post y de Review a Comment
    {
        $validatedData = $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'rating'      => 'required|numeric|min:1|max:5',
        ]);

        $comment->title = $request->input('title');
        $comment->description = $request->input('description');
        $comment->rating = $request->input('rating');
        $comment->save();

        return response()->json([
            'success' => true,
            'data'    => $comment, // Cambiado de reviews a comments
        ], 200);
    }

    public function destroy(Post $post, Comment $comment) // Cambiado de Place a Post y de Review a Comment
    {
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully', // Cambiado de Review a Comment
        ], 200);
    }

    public function update_workaround(Request $request, Post $post, Comment $comment) // Cambiado de Place a Post y de Review a Comment
    {
        return $this->update($request, $post, $comment); // Cambiado de reviews a comments
    }
}