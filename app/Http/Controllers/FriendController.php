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
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        if ($user->friends()->where('friend_id', $id)->exists()) {
            return response()->json(['error' => 'Déjà en attente'], 409);
        }

        Friend::create([
            'user_id' => $user->id,
            'friend_id' => $id
        ]);

        return response()->json(['success' => true]);
    }


    public function removeFriend($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User  not authenticated'], 401);
        }

        Friend::where('user_id', $user->id)->where('friend_id', $id)->delete();
        return response()->json(['success' => 'Friend removed successfully.']);
    }

    public function getFriends()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User  not authenticated'], 401);
        }
        $friends = $user->friends()->with('friend')->get();
        return response()->json($friends);
    }
    public function acceptFriendRequest($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $request = $user->receivedRequests()->where('user_id', $id)->first();

        if ($request) {
            $request->pivot->update(['status' => 'accepted']);
            $user->friends()->attach($id, ['status' => 'accepted']);
        }

        return response()->json(['success' => 'Friend request accepted.']);
    }

    public function rejectFriendRequest($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $request = $user->receivedRequests()->where('user_id', $id)->first();

        if ($request) {
            $request->delete(); 
        }

        return response()->json(['success' => 'Friend request rejected.']);
    }
}
