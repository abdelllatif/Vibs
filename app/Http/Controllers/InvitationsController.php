<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    public function invitations()
    {
        $user = Auth::user();

        $sentRequests = $user->sentRequests;
        $receivedRequests = $user->receivedRequests;

        return view('invitationStatus', compact('sentRequests', 'receivedRequests'));
    }
    public function acceptInvitation($userId)
    {
        $user = Auth::user();
        $user->receivedRequests()->updateExistingPivot($userId, ['status' => 'accepted']);

        return redirect()->route('invitations.index');
    }
    public function rejectInvitation($userId)
    {
        $user = Auth::user();
        $user->receivedRequests()->updateExistingPivot($userId, ['status' => 'rejected']);
        return redirect()->route('invitations.index');
    }
}
