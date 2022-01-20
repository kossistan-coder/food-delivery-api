<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin;
class Post extends Model
{
    use HasApiTokens, HasFactory, Notifiable; 
    protected $fillable = [
        'admin_id',
        'name',
        'description',
        'delivery_time',
        'price',
        'image'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
