<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dogs;
use App\Models\User;
class transactions extends Model
{
    protected $guarded = [];


      public function user()
    {
        return $this->belongsTo(User::class,"userid");
    }
    
    public function dog()
    {
        return $this->belongsTo(Dogs::class,"dogid");
    }

}
