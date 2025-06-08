<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'comment' => 'required|string',
        ]);

        $user = auth('sanctum')->user();
        $post = Post::with('creator')->findOrFail($validated['post_id']);


        $comment = Comment::create([
            'post_id' => $post->id,
            'created_by' => $user->id,
            'comment' => $validated['comment'],
        ]);

        // Send email notification to the post author
        if ($post->creator && $post->creator->id == $user->id) {
    $post->creator->notify(new NewCommentNotification($comment, $post));
}


        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment->load('creator'),
        ], 201);

    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to create comment',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function update(Request $request, $comment_id)
{
    try {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $user = auth('sanctum')->user();
        $comment = Comment::findOrFail($comment_id);
        

        // Only the comment owner can update it
        if ($comment->created_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->comment = $validated['comment'];
        $comment->save();

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment->load('creator')
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to update comment',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function delete($comment_id)
{
    try {
        $user = auth('sanctum')->user();
        $comment = Comment::findOrFail($comment_id);

        // Ensure the user is the owner
        if ($comment->created_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to delete comment',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getUserComments(Request $request, $user_id)
{
    try {
        $comments = Comment::with('creator')->where('created_by', $user_id)->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching comments.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
