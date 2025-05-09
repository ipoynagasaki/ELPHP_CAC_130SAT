<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dogs extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    public function user()
    {
        return $this->belongsTo(User::class); // Laravel will auto use 'user_id'
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}