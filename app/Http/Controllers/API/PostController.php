<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    //show all post data
    public function index()
    {
        $posts = Post::all();
        return response()->json(['posts' => $posts]);
    }

    //show all specific post data
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        return response()->json(['post' => $post]);
    }
    //create post
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user is invalid.',
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $post = Post::create($request->all());
        return response()->json(['post' => $post], 201);
    }
    //update post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user is invalid.',
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $post->update($request->all());
        return response()->json(['post' => $post]);
    }
    //delete post
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
