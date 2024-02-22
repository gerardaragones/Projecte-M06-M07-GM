<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create(Post $post)
    {
        return view('comments.create', compact('post'));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $commentData = $request->all();
        $commentData['user_id'] = Auth::id();
    
        $post->comments()->create($commentData);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment created successfully');
    }

    public function edit(Post $post, Comment $comment)
    {
        if (Auth::id() == $comment->user_id) {
            return view('comments.edit', compact('comment'));
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function destroy(Post $post, Comment $comment)
    {
        if (Auth::id() == $comment->user_id || Auth::user()->role_id == Role::ADMIN) {
            $comment->delete();
            return redirect()->route('posts.show', $post)
                ->with('success', 'Comment deleted successfully');
        } else {
            abort(403, 'Unauthorized');
        }
    }
}