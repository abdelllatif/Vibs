<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function addFriend($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $friendUser = User::findOrFail($id);

            if ($user->friends()->where('friend_id', $id)->exists()) {
                return response()->json(['error' => 'Already friends'], 409);
            }

            Friend::create([
                'user_id' => $user->id,
                'friend_id' => $id
            ]);

            return response()->json(['success' => 'Friend added']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Operation failed'], 500);
        }
    }

    public function removeFriend($id)
    {
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User  not authenticated'], 401);
        }

        // Remove the friend relationship
        Friend::where('user_id', $user->id)->where('friend_id', $id)->delete();
        return response()->json(['success' => 'Friend removed successfully.']);
    }

    public function getFriends()
    {
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User  not authenticated'], 401);
        }

        // Get the user's friends
        $friends = $user->friends()->with('friend')->get(); // Get all friends
        return response()->json($friends);
    }
}
