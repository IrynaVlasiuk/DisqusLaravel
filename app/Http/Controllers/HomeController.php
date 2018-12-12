<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all()->where('parent_id', 0);
        return view('home', compact('comments'));
    }
}