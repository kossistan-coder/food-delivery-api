<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Admin;

class PostController extends Controller
{
    //
    public function index(Request $request ){
        return response()->json([
            'posts'=>Post::all()
        ], 200);
    }
}
