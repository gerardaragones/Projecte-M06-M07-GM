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
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401);
        }

        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        $comment = $post->comments()->create([
            'comment' => $request->input('comment'),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $comment,
        ], 201);
    }

    public function show(Post $post, Comment $comment)
    {
        return response()->json([
            'success' => true,
            'data'    => $comment,
        ], 200);
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        $comment->update(['comment' => $request->input('comment')]);

        return response()->json([
            'success' => true,
            'data'    => $comment,
        ], 200);
    }

    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ], 200);
    }

    public function update_workaround(Request $request, Post $post, Comment $comment)
    {
        return $this->update($request, $post, $comment);
    }
}