<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReactionController extends Controller
{
    public function addReaction(Request $request, $postId)
    {
        // Log incoming request data for debugging
        Log::info('Reaction request received', [
            'post_id' => $postId,
            'user' => Auth::id(),
            'request_data' => $request->all()
        ]);

        try {
            // Verify authentication
            if (!Auth::check()) {
                Log::error('Authentication failed in reaction controller');
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            // Get authenticated user
            $user = Auth::user();

            // Check if post exists
            $post = Post::find($postId);
            if (!$post) {
                Log::error('Post not found', ['post_id' => $postId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Post non trouvé'
                ], 404);
            }

            Log::info('Processing reaction', ['post' => $postId, 'user' => $user->id]);

            // Simple toggle - just handle likes without reaction type
            $existingReaction = Reaction::where('post_id', $postId)
                                        ->where('user_id', $user->id)
                                        ->first();

            if ($existingReaction) {
                // If reaction exists, remove it (unlike)
                Log::info('Removing existing reaction', ['reaction_id' => $existingReaction->id]);
                $existingReaction->delete();
            } else {
                // Create new like reaction
                Log::info('Creating new reaction');
                Reaction::create([
                    'post_id' => $postId,
                    'user_id' => $user->id,
                    'reaction' => 'like',
                ]);
            }

            // Count likes
            $likeCount = Reaction::where('post_id', $postId)
                                ->where('reaction', 'like')
                                ->count();

            // Count dislikes
            $dislikeCount = Reaction::where('post_id', $postId)
                                    ->where('reaction', 'dislike')
                                    ->count();

            Log::info('Reaction processed successfully', [
                'post_id' => $postId,
                'like_count' => $likeCount,
                'dislike_count' => $dislikeCount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réaction traitée avec succès',
                'like_count' => $likeCount,
                'dislike_count' => $dislikeCount
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in reaction controller', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }
}
