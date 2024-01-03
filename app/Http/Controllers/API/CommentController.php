<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    //Show all comment data
    public function index()
    {
        $comments = Comment::all();
        return response()->json(['comments' => $comments]);
    }

     //Show all specific comment data
    public function show($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        return response()->json(['comment' => $comment]);
    }

    //create comment
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user is invalid.',
            'post_id.required' => 'The post ID field is required.',
            'post_id.exists' => 'The selected post is invalid.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $comment = Comment::create($request->all());
        return response()->json(['comment' => $comment], 201);
    }

    //update comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user is invalid.',
            'post_id.required' => 'The post ID field is required.',
            'post_id.exists' => 'The selected post is invalid.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $comment->update($request->all());
        return response()->json(['comment' => $comment]);
    }
    //delete comment
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
