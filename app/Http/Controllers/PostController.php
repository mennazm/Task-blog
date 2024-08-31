<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

  
    public function index()
    {
        $posts = Auth::user()->posts()->with('tags')->orderBy('pinned', 'desc')->get();
        return response()->json($posts);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        
        $path = $request->file('cover_image')->store('images', 'public');

        $post = Auth::user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $path,
            'pinned' => $request->pinned,
        ]);

        $post->tags()->sync($request->tags);

        return response()->json($post, 201);
    }

 
    public function show(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post->load('tags'));
    }

    
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'cover_image' => 'sometimes|image',
            'pinned' => 'sometimes|required|boolean',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($post->cover_image); 
            $path = $request->file('cover_image')->store('images', 'public');
            $post->cover_image = $path;
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'pinned' => $request->pinned,
        ]);

     
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags'));
    }

        public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }

    public function deleted()
    {
        $posts = Auth::user()->posts()->onlyTrashed()->with('tags')->get();
        return response()->json($posts);
    }

    
    public function restore($id)
    {
        $post = Post::withTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $post->restore();
        //return response()->json($post);
        return response()->json(['message' => 'Post restored successfully.']);
    }
}
