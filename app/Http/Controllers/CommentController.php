<?php
namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommentController extends Controller
{
    public function addcomments(Request $request, $post)
    {
        $request->validate([
            'comment' => 'required|string|max:400',
        ]);

        $comment = new Comment();
        $comment->contenu = $request->comment;
        $comment->post_id = $post;
        $comment->user_id = auth()->id();

        if ($comment->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment successfully added!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add comment!'
        ], 500);
    }
    public function afficher($postId){
        $comments = Comment::with('user')->where('post_id', $postId)->latest()->get();
        return view('post.show', compact('comments'));

    }
}
