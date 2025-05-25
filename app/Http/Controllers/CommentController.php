<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $id)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $article = Article::findOrFail($id);

    $comment = $article->comments()->create([
        'user_id' => auth()->id(),
        'body' => $request->comment,
    ]);

    return response()->json([
        'success' => true,
        'comment' => [
            'user' => $comment->user->name,
            'body' => $comment->body,
            'created_at' => $comment->created_at->diffForHumans(),
        ],
    ]);
}
public function destroy(Comment $comment)
{
    // Allow only comment owner or admin to delete
    if (auth()->id() !== $comment->user_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $comment->delete();

    return response()->json(['success' => true]);
}


}
