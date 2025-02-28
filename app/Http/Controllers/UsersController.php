<?php

namespace App\Http\Controllers;
use App\Models\Friend;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
public function index($id){

    $user=User::findOrFail($id);
    $friends=$this->getFriends($id);
    $posts=$this->getPosts($id);
    return view('userProfil',compact('user','friends','posts'));
}
public function getFriends($id) {
    $user = User::findOrFail($id);
    $friends = $user->friends()->get();
    return $friends;
}
public function getPosts($id){
    $user = User::findOrFail($id);
    $posts = $user->posts()->with('user', 'comments')->where('user_id', $user->id)->latest()->get();
    return $posts;
}
}
