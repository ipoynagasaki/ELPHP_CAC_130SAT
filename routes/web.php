<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // Retrieve all users and eager load their relationships
    $allUsers = User::with([
        'dogs',                         // All dogs owned by the user
        'favorites',                    // All favorites (dogs marked as favorite by the user)
        'transactionsAsBuyer',          // All transactions where this user is the buyer
        'transactionsAsSeller',         // All transactions where this user is the seller
        'reviews'                       // All reviews written by this user
    ])->get();

    // Pass the users data to the view
    return view('welcome', compact('allUsers'));
});
