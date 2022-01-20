<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;
use App\Models\Panier;
use App\Models\Post;

class AdminController extends Controller
{
    //
    public function index(Request $request){
        return $request->user();
    }

    public function show(Request $request ,User $user ){
       

        $admin = $request->user();
        if ($admin['id'] == 1) {
            
            return response()->json([
                'users'=>$user->with('paniers')->withCount('paniers')->get()
            ], 200);
        }
    }
 

    public function store(Request $request , $id ){
        $admin= $request->user();
        $attr = $request->validate([
            'name'=>'required|unique:posts|string',
            'description'=>'required|string',
            'time'=>'required|integer',
            'price'=>'required|numeric',
            'image'=>'required'
        ]);
        if ($request->hasFile('image') ) {

            $dest_path='public/profiles';
            $image=$request->file('image');
            $img_name=$image->hashName();
            $path=$request->file('image')->storeAs($dest_path,$img_name);
            $attr['image']='http://localhost:8000/storage/public/profiles/'.$img_name;

    }

        if ($admin['id'] == $id) {
            

            Post::create([
                'admin_id'=>$id,
                'name'=>$attr['name'],
                'description'=>$attr['description'],
                'delivery_time'=>$attr['time'],
                'price'=>$attr['price'],
                'image'=>$attr['image']
            ]);
            return response()->json([
                'message'=>"Articles poster avec success"
            ], 200);
        }else {
            return response()->json([
                'message'=>"Vous n'avez pas le droit"
            ], 403);
        }
    }

    public function update(Request $request ,$id ,$nb ){
        $admin = $request->user();
        $attr = $request->validate([
            'name'=>'string',
            'description'=>'string',
            'time'=>'integer',
            'price'=>'numeric',
            'image'=>'image'
        ]);

        $image = $this->saveImage($attr['image'],'profiles');

        $temp1 = Admin::find($id);
        if ($temp1) {
            $temp = $temp1->posts;
            if ( $admin['id'] == 1 && $admin['id'] == $id) {
                try {
                    Post::where('id',$nb)->update([
 
                        'name'=>$attr['name'],
                        'description'=>$attr['description'],
                        'delivery_time'=>$attr['time'],
                        'price'=>$attr['price'],
                        'image'=>$image
                    ]);
                    return response()->json([
                        'message'=>"Vous venez de mettre a jour cet articles en tant qu'admin principale"
                    ], 200);
                } catch (\Throwable $th) {
                    return response()->json([
                        'message'=>"Un article existe deja avec le meme nom"
                    ], 200);
                }
                
            }elseif ( $admin['id'] != 1 && Post::where('admin_id',$admin['id'])->where('id',$nb)->first()  ) {
                try {
                    Admin::find($id)->posts()->where('id',$nb)->update([

                        'name'=>$attr['name'],
                        'description'=>$attr['description'],
                        'delivery_time'=>$attr['time'],
                        'price'=>$attr['price'],
                        'image'=>$image
                    ]);
                    return response()->json([
                        'message'=>"Articles mise a jour avec success"
                    ], 200);
                } catch (\Throwable $th) {
                    return response()->json([
                        'message'=>"Un article existe deja avec le memme nom"
                    ], 200);
                }
                
            }else {
                return response()->json([
                    'message'=>"Vous n'avez aucun droit sur ce post"
                ], 403);
            }
        }else {
            return response()->json([
                'message'=>"Access rejeté"
            ], 403);
        }
        
        

        
    }

    public function destroy(Request $request , $id ,$nb){
        $admin = $request->user();
        if ($admin['id'] == 1) {
            Post::where('id',$nb)->delete();
            
        }else {
            Admin::find($id)->posts()->where('id',$nb)->delete();
            
        }
        return response()->json([
            'message'=>"Articles supprimer avec success"
        ], 200);

        
    }

    public function PostByAdmin(Request $request,$id ,Post $post){
        $admin = $request->user();
        if ($admin['id']!=1 && $admin['id'] == $id) {
            return response()->json([
                'posts'=>Post::where('admin_id',$admin['id'])->with('admin')->get()
            ], 200);
        }elseif ($admin['id'] == 1 && $admin['id'] == $id) {
            return response()->json([
                'posts'=>$post->with('admin')->get()
            ], 200);
        }
    }

    public function limit(Request $request,$id ,Post $post ,$nb ){
        $admin = $request->user();
        if ($admin['id']!=1 && $admin['id'] == $id) {
            return response()->json([
                'posts'=>Post::where('admin_id',$admin['id'])->limit($nb)->with('admin')->get()
            ], 200);
        }elseif ($admin['id'] == 1 && $admin['id'] == $id) {
            return response()->json([
                'posts'=>$post->limit($nb)->with('admin')->get()
            ], 200);
        }
    }
}
