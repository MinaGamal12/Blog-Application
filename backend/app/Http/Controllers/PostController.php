<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'tags', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'author' => [
                        'id' => $post->author->id,
                        'name' => $post->author->name,
                        'email' => $post->author->email,
                        'image' => $post->author->image,
                    ],
                    'tags' => $post->tags,
                    'comments' => $post->comments,
                    'created_at' => $post->created_at,
                    'expires_at' => $post->expires_at,
                    'is_expired' => $post->isExpired(),
                ];
            });

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'required|array|min:1',
            'tags.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'author_id' => auth()->id(),
        ]);

        // Attach tags
        $tagIds = [];
        foreach ($request->tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);

        $post->load(['author', 'tags', 'comments.user']);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
                'author' => [
                    'id' => $post->author->id,
                    'name' => $post->author->name,
                    'email' => $post->author->email,
                    'image' => $post->author->image,
                ],
                'tags' => $post->tags,
                'comments' => $post->comments,
                'created_at' => $post->created_at,
                'expires_at' => $post->expires_at,
                'is_expired' => $post->isExpired(),
            ],
        ], 201);
    }

    public function show($id)
    {
        $post = Post::with(['author', 'tags', 'comments.user'])->findOrFail($id);

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'author' => [
                'id' => $post->author->id,
                'name' => $post->author->name,
                'email' => $post->author->email,
                'image' => $post->author->image,
            ],
            'tags' => $post->tags,
            'comments' => $post->comments,
            'created_at' => $post->created_at,
            'expires_at' => $post->expires_at,
            'is_expired' => $post->isExpired(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->author_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('title')) {
            $post->title = $request->title;
        }

        if ($request->has('body')) {
            $post->body = $request->body;
        }

        $post->save();
        $post->load(['author', 'tags', 'comments.user']);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
                'author' => [
                    'id' => $post->author->id,
                    'name' => $post->author->name,
                    'email' => $post->author->email,
                    'image' => $post->author->image,
                ],
                'tags' => $post->tags,
                'comments' => $post->comments,
                'created_at' => $post->created_at,
                'expires_at' => $post->expires_at,
                'is_expired' => $post->isExpired(),
            ],
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->author_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function updateTags(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->author_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tags' => 'required|array|min:1',
            'tags.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tagIds = [];
        foreach ($request->tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);

        return response()->json([
            'message' => 'Tags updated successfully',
            'tags' => $post->tags,
        ]);
    }
}

