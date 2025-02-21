<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at');
        $users = User::where('id', '!=', Auth::id())
            ->when($search, function ($query, $search) {
                return $query->where('nom', 'like', "%{$search}%")
                             ->orWhere('prenom', 'like', "%{$search}%")
                             ->orWhere('pseudo', 'like', "%{$search}%");
            })
            ->orderBy($sort, 'asc')
            ->paginate(4);

        return view('users', compact('users', 'search', 'sort'));
    }
}
