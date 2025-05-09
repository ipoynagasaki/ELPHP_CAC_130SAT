<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reviews extends Model
{
    protected $guarded = [];

    //

   
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewerid');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'revieweduserid');
    }
    
    
}
