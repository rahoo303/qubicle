<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $limit = 20;
        $page = 1;
        $page = $request->has('page') ? $request->get('page') : $page;
        $userList = User::where('id', '!=', \Auth::id())->orderBy('point', 'desc')->paginate($limit);
        return view('home', compact('userList', 'limit', 'page'));
    }
}
