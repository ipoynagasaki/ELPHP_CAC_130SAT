<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Messages;
use App\Models\Dogs;
use App\Models\Reviews;
use App\Models\Transactions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenumber',
        'address',
        'user_type'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # dogs
    public function dogs(){

        return $this->hasMany( Dogs::class);
    }
    # transaction
    public function transactions(){
        return $this->hasMany( Transactions::class);

    }

    # user


    # reviews
    public function writtenReviews()
    {
        return $this->hasMany(Reviews::class, 'reviewerid');
    }
    
    public function receivedReviews()
    {
        return $this->hasMany(Reviews::class, 'revieweduserid');
    }
    

    # message


    
    public function sentMessages()
    {
        return $this->hasMany( Messages::class, 'sender_id');
    }
    
    public function receivedMessages()
    {
        return $this->hasMany(Messages::class,  'reviewer_id');
    }


   
}
