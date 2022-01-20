<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;
class Panier extends Model
{
    use HasFactory;

    protected $fillable=[
        'nb_post',
        'user_id',
        'sum'
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
