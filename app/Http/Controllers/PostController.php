<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\PostInc;

class PostController extends Controller
{
    public function afficher(Request $request)
    {
        $posts = Post::with(['user', 'comments.user'])->orderBy('created_at', 'desc')->get();

        foreach ($posts as $post) {
            $likeCount = Reaction::where('post_id', $post->id)->where('reaction', 'like')->count();
            $dislikeCount = Reaction::where('post_id', $post->id)->where('reaction', 'dislike')->count();
            $post->like_count = $likeCount;
            $post->dislike_count = $dislikeCount;
        }

        $user = $request->user();
        return view('faild', compact('user', 'posts'));
    }


    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postContent' => 'required|string',
            'imageInput' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $imagePath = null;
        if ($request->hasFile('imageInput')) {
            $imagePath = $request->file('imageInput')->store('posts', 'public');
        }
        $post = new Post();
        $post->contenu = $request->postContent;
        if ($imagePath) {
            $post->image = $imagePath;
        }
        $post->user_id = auth()->id();
        $post->save();
        return response()->json(['success' => true, 'message' => 'Post créé avec succès!'], 200);
    }

    public function editpost(Request $request, $PostId)
    {
        $validator = Validator::make($request->all(), [
            'contenu' => 'required|string|max:255', // Change 'postContent' to 'contenu'
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Change 'imageInput' to 'image'
        ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $post = Post::find($PostId);
            if (!$post) {
                return back()->withErrors(['post' => 'Post not found.']);
            }
            $post->contenu = $request->contenu;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
                $post->image = $imagePath;
            }
            $post->save();
            return back()->with('success', 'Post updated successfully');
        }
        public function delete($postId)
{
    $post = Post::findOrFail($postId);
   if($post->delete()){
        return back()->with('success', 'Post deleted successfully');
   }
   else{
    return back()->withErrors(['post' => 'Post not supprimed.']);
   }
}

}
