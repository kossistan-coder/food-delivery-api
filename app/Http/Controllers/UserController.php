<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
Use App\Models\Panier;

class UserController extends Controller
{
    //
    public function index(Request $request){
        return $request->user();
    }

    public function store(Request $request , $id){
        $attr = $request->validate([
            'nb_post'=>'integer',
            'sum'=>'integer'
        ]);
        Panier::create([
            'nb_post'=>$attr['nb_post'],
            'user_id'=>$id,
            'sum'=>$attr['sum']
        ]);

        return response()->json([
            'message'=>"Achat effectuer avec success"
        ], 200);
    }
}
