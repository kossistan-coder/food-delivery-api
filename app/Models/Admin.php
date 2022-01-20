<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Post;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable =[
        'name',
        'email',
        'password',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

  

    public function posts(){
        return $this->hasMany(Post::class);
    }
}
