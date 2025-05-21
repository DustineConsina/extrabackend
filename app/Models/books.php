<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books'; 

    protected $fillable = [
        'title',
        'author',
        'category',
        'status', 
        'image',  
        'user_id',
    ];
}
