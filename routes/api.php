<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Messages;




Route::POST('/register',  function(Request $request){
    $request->validate([
        'name' => 'required',
        'email'=> 'required|email',
        'password'=> 'required',
        'phonenumber' => 'required',
        'address' => 'required',
        'user_type' => 'required|in:seller,buyer'
    ]);
    
     
    $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phonenumber'=>$request->phonenumber,
            'address'=>$request->address,
            'user_type'=> $request->user_type
        
    ]);

    return response()->json([
        'message' => 'Registered successfully please login',
        'name'    => $user->name,
        'email' => $user->email
    ]);

    
});


Route::POST('/login', function(Request $request){

   
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();


    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials']);
    }

    $user->tokens()->delete();

    $token = $user->createToken('postman')->plainTextToken;

    return response()->json([
        'message' => 'Logged in',
        'token' => $token,
        'Welcome' => $user->name
    ]);


});

# Public route
Route::GET('/users',  function(){

    $user = User::all();
    $users = $user->map(function ($user) {
    return [
            'user id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'User type' => $user->User_Type

        ];


});

return response()->json([
    'all user' => $users
]);

}); # list of users

Route::GET('/users/{id}',  function($id){
$user = User::find($id);
if(!$user){
    return response()->json(['message' => "LAB U"]);
}
return response()->json([
    'user' => [
      'user id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'address' => $user->address,
        'User type' => $user->User_Type
    ]
]);

}); # Get user details

# Protected route
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', function(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });
   
    Route::patch('/users', function(Request $request) {

        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'phonenumber' => 'sometimes|string',
            'address' => 'sometimes|string',
            'user_type' => 'sometimes|in:seller,buyer'
        ]);
        
        $user = User::findOrFail(auth()->id());
        
        $user->update($request->only([
            'name',
            'email',
            'phonenumber',
            'address',
            'user_type'
        ]));
        
        return response()->json('Account updated successfully');
    
    }); # Update user

    Route::patch('/users/password', function(Request $request){
        $request->validate([
            'password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        $user = auth()->user();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password updated successfully']);

    }); # change password
    Route::DELETE('/users',  function(Request $request,) {

        $user = User::findorfail(auth()->id());
        $user->delete();
        $user->tokens()->delete();
        return response()->json(['message' => 'User deleted successfully']);
       
    }); # Delete user


    

    Route::POST('/message', function(Request $request){
        $request->validate([
            'receiver_id' => 'required',
            'content' => 'required'
        ]);
    
        $message = User::find($request->receiver_id);
        if (!$message) {
            return response()->json(['message' => 'User not found']);
        }
    
        $attributes = [
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content

        ];


        Messages::create($attributes);
        
        return response()->json([
            'message' => 'Message sent successfully',
        ]);
    }); # send message
    

   

    

});


# dogs
use App\Http\Controllers\DogController;

Route::get('/dogs', [DogController::class, 'index']);
Route::get('/dogs/{id}', [DogController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/dogs', [DogController::class, 'store']);
    Route::patch('/dogs/{id}', [DogController::class, 'update']);
    Route::delete('/dogs/{id}', [DogController::class, 'destroy']);
    

});



use App\Models\Reviews;

Route::get('/reviews', function(){

    $reviews = Reviews::with(['reviewer', 'user'])->get()->map(function ($review) {
        return [
            'review_id' => $review->id,
            'reviewed_user' => $review->user->name,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'reviewer' => [
                'id' => $review->reviewer->id,
                'name' => $review->reviewer->name,
            ],
           
        ];
    });

    return response()->json(["reviews" => $reviews]);

});

Route::get('/reviews/{user}', function($user){

    $reviews = Reviews::with(['reviewer', 'user'])
    ->whereHas('user', function ($query) use ($user) {
        $query->where('name', $user);
    })
    ->get()
    ->map(function ($review) {
        return [
            'review_id' => $review->id,
            'reviewed_user_id' => $review->revieweduserid,
            'reviewed_user' => $review->user?->name,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'reviewer' => $review->reviewer ? [
                'id' => $review->reviewer->id,
                'name' => $review->reviewer->name,
            ] : null,
        ];
    });

return response()->json(['reviews' => $reviews]);

}); # search review user

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/reviews/submit', function(Request $request){
        $request->validate([
            'user_id'=>'required',
            'rating'=>'required|integer',
            'comment' => 'required'
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found']);
        }
        if($user->User_Type != "seller"){
            return response()->json(['message' => 'User not seller']);

        }
        $attributes = [
            'reviewerid' => auth()->id(),
            'revieweduserid' => $request->user_id,
            'comment' => $request->comment,
            'rating' => $request->rating

        ];
        $reviews = Reviews::create($attributes);
       

        return response()->json([
            'message' => 'Comment created successfully',
            'created_comment' => $reviews
        ]);
    });# create reviews

    Route::patch('/reviews/{review_id}', function(Request $request, $review_id){
        $request->validate([
            'user_id'=>'required',
            'rating'=>'sometimes|integer',
            'comment' => 'sometimes'
        ]);
        $review = Reviews::all()->find($review_id);

        if (!$review) {
            return response()->json(['message' => 'review not found']);
        }

        if ($review->reviewerid !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized']);
        }


        $review->update($request->only([
            'rating',
            'comment',

        ]));

        return response()->json([
            'message' => 'Review updated successfully',
            'updated_comment' => $review
        ]);

    });# update review
   

Route::delete('/reviews/{review_id}', function($review_id){
     
        $review = Reviews::all()->find($review_id);

        if (!$review) {
            return response()->json(['message' => 'review not found']);
        }

        if ($review->reviewerid !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized']);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted Review successfully',
        ]);

    });# delete review
   

});


use App\Models\transactions;
use App\Models\Dogs;
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/transactions' , function(){
        $transactions = Transactions::with(['dog.owner', 'user'])
        ->whereHas('dog.owner', function ($query) {
            $query->where('id', auth()->id());
        })
        ->get()
        ->map(function ($transact) {
            return [
                'transaction_id' => $transact->id,
                'buyer_information' => [
                    'name' => $transact->user->name
                ],
                'dog_information' => [
                    'owner_id' => $transact->dog->owner->id,
                    'dog_owner_name' => $transact->dog->owner->name,
                    'description' => $transact->dog->description,
                    'gender' => $transact->dog->gender,
                    'price' => $transact->dog->price,
                    'dog_status' => $transact->dog->status
                ],
                'status' => $transact->status,
                'created_at' => $transact->created_at,
                'updated_at' => $transact->updated_at
            ];
        });
    
    
            return response()->json(['message' =>$transactions]);

    }); # list of all transaction

  
    Route::get('/transactions/search/{id}' , function($id){
        $transaction = Transactions::with(['dog.owner', 'user'])
        ->where('id', $id)
        ->whereHas('dog.owner', function ($query) {
            $query->where('id', auth()->id());
        })
        ->first();

    if (!$transaction) {
        return response()->json(['message' => 'Transaction not found or unauthorized']);
    }

    return response()->json([
        'transaction_id' => $transaction->id,
        'buyer_information' => [
            'name' => $transaction->user->name
        ],
        'dog_information' => [
            'owner_id' => $transaction->dog->owner->id,
            'dog_owner_name' => $transaction->dog->owner->name,
            'description' => $transaction->dog->description,
            'gender' => $transaction->dog->gender,
            'price' => $transaction->dog->price,
            'dog_status' => $transaction->dog->status
        ],
        'status' => $transaction->status,
        'created_at' => $transaction->created_at,
        'updated_at' => $transaction->updated_at
    ]);
    }); # filter transaction by id

    Route::post('/transactions', function(Request $request){
        $request->validate([
            'dogid' => 'required|integer',
            'date' => 'required ',
            'status' => 'required'
        ]);

        $transaction = Dogs::find($request->dogid);
        if (!$transaction) {
            return response()->json(['message' => 'Dog not found']);
        }

        $attributes = [
            'userid' => auth()->id(),
            'dogid' => $request->dogid,
            'transaction_date' => $request->date,
            'status'=> $request->status


        ];

        $attributes = Transactions::create($attributes);
        

        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $attributes
        ]);
        
    }); # create a transaction


    Route::delete('/transactions/{id}', function($id) {
        $transaction = Transactions::with('dog.owner')->find($id);
    
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found']);
        }
        
    
        if ($transaction->dog->owner->id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized: Not the dog owner']);
        }

     
        $transaction->delete();
    
        return response()->json(['message' => 'Transaction deleted successfully']);
    }); # delete a transaction



});




