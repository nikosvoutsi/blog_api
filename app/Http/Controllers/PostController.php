<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;


class PostController extends Controller
{
    public function index(Request $request)
{
    try {


        $query = Post::with(['tags', 'creator', 'category']);
    
        $searchCriteria = $request->input('searchCriteria', []);

    
        if (!empty($searchCriteria)) {
            // author filter
            if (isset($searchCriteria['author'])) {
                $query->where('author', $searchCriteria['author']); 
            }

            // tags filter
            if (isset($searchCriteria['tagIDs']) && is_array($searchCriteria['tagIDs']) && !empty($searchCriteria['tagIDs'])) {
                $tagIDs = $searchCriteria['tagIDs'];
    
                $query->whereHas('tags', function ($q) use ($tagIDs) {
                    $q->whereIn('tags.id', $tagIDs);
                });
            }

            // categories filter
            if (isset($searchCriteria['categoryID'])) {
                $query->where('category_id', $searchCriteria['categoryID']); 
            }
        }

        $posts = $query->paginate(15);

        return response()->json([
            'posts' => $posts,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong while fetching posts.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function store(Request $request)
{
    try {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|unique:posts,slug',
            'tag_ids' => 'array',
            'tag_ids.*' => 'integer|exists:tags,id',
            'category_id' => 'integer|exists:categories,id',
        ]);

        $user = auth('sanctum')->user();



        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => $validated['slug'],
            'author' => $user->id,
            'category_id' => $user->category_id,

        ]);


      
        $tagIds = array_unique(array_merge(
            $validated['tag_ids'] ?? [],
            [1]
        ));
        $post->tags()->attach($tagIds);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load(['tags', 'categories', 'creator']),
        ], 201);

    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to create post',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function update(Request $request, $post_id)
{
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|unique:posts,slug',
            'tag_ids' => 'array',
            'tag_ids.*' => 'integer|exists:tags,id',
            'category_id' => 'integer|exists:categories,id',
        ]);

        $user = auth('sanctum')->user();
        $post = Post::findOrFail($post_id);

        if ($post->author !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Fill with new values to check changes
        $post->fill([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => $validated['slug'],
            'category_id' => $user->category_id,
        ]);

        $postChanged = $post->isDirty(['title', 'content']);
        $post->save();


        // Sync tags with "edited" tag only if title or content changed
        $tagIds = $validated['tag_ids'] ?? [];

        if ($postChanged && !in_array(2, $tagIds)) {
            $tagIds[] = 2; // Tag ID 2 = "edited"
        }

        $post->tags()->sync(array_unique($tagIds));

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post->load(['tags', 'categories', 'creator']),
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to update post',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function delete($post_id)
{
    try {
        $user = auth('sanctum')->user();
        $post = Post::findOrFail($post_id);

        // Check if the authenticated user is the author
        if ($post->author !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Comment::where('post_id', $post->id)->delete();


        // Detach related tags and categories
        $post->tags()->detach();
        $post->categories()->detach();

        // Delete the post 
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);

    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to delete post',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function getPost(Request $request, $id)
{
    try {
        // Optional slug filter
        $slug = $request->query('slug');

        $query = Post::with(['tags', 'category', 'creator'])->where('id', $id);

        if ($slug) {
            $query->where('slug', $slug);
        }

        $post = $query->firstOrFail();

        return response()->json([
            'post' => $post
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Post not found',
            'error' => $e->getMessage(),
        ], 404);
    }
    
}

public function getUserPosts(Request $request, $user_id)
{
    try {
        $posts = Post::where('author', $user_id)->get();

        return response()->json([
            'success' => true,
            'posts' => $posts,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching posts.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
