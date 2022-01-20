<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    

    public function register(Request $request){
        $attr = $request->validate([
            'name'=>'required|max:255',
            'email'=>'email|required|unique:users|unique:admins',
            'password'=>'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'=>$attr['name'],
            'email'=>$attr['email'],
            'password'=>Hash::make($attr['password'])
        ]);

         return response()->json([
            'message'=>"Compte créer avec success"
        ],200);
    }

    public function login(Request $request){
        $attr = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email',$attr['email'])->first();
        $admin = Admin::where('email',$attr['email'])->first();

        if ($user && Hash::check($attr['password'], $user->password)) {
            $token = $user->createToken('secret')->plainTextToken;
            return response()->json([
                'user'=>$user,
                'token'=>$token,
                'root'=>0
            ], 200);
        }elseif ($admin && Hash::check($attr['password'], $admin->password) ) {
           
            $token = $admin->createToken('secret')->plainTextToken;
            return response()->json([
                'user'=>$admin,
                'token'=>$token,
                'root'=>1
            ], 200);
        }else {
            return response()->json([
                'message'=>"Identifiant ou mot de passe incorrect"
            ], 403);
        }

        
    }

    public function admin(Request $request){
        $attr = $request->validate([
            'name'=>'required|max::255',
            'email'=>'email|required|unique:users|unique:admins',
            'password'=>'required|min:8|confirmed',
        ]);

        $user = Admin::create([
            'name'=>$attr['name'],
            'email'=>$attr['email'],
            'password'=>Hash::make($attr['password'])
        ]);

         return response()->json([
            'message'=>"Compte créer avec success"
        ],200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return  response()->json([
            'message'=>"Deconnecté avec success"
        ], 200 );
    }

    public function show(Request $request){
        $user = $request->user();

        if($user['id'] == 1){
            $list = Admin::where('id','!=',1)->get();
             return response()->json([
                'admins'=>$list
            ], 200);
        }
    }

    public function destroy(Request $request , $id){
        $user = $request->user();

        if ($user['id'] == 1) {
            Admin::destroy($id);
            return response()->json([
                'message'=>"Admin supprimé avec succès"
            ], 200);
        }
    }

    public function update(Request $request , $id){
        $user = $request->user();
        $attr = $request->validate([
            'name'=>'required|max:255',
            'email'=>'email|required',
            'password'=>'required|min:8|confirmed',
        ]);
        if ($user['id'] == 1) {
            Admin::where('id',$id)->update(
                [
                    'name'=>$attr['name'],
                    'email'=>$attr['email'],
                    'password'=>Hash::make($attr['password'])
                ]
                );

                return response()->json([
                    'message'=>"Admin mis a jour avec success"
                ], 200);
        }
    }
}
