<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\User;
use App\Models\Dog;
use App\Models\Favorite;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\Listing;
use App\Models\Chat;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $john = User::factory()->create([
            'name' => 'John',
            'email' => 'john@example.com',
            'phone_number' => '1234567890',
            'address' => '123 John St',
        ]);

        $bruno = User::factory()->create([
            'name' => 'Bruno',
            'email' => 'bruno@example.com',
            'phone_number' => '0987654321',
            'address' => '456 Bruno Rd',
        ]);

        // Create dog for John (Bran)
        $bran = Dog::factory()->create([
            'owner_id' => $john->id,
            'name' => 'Bran',
        ]);

        // Create a listing for the dog
        $listing = Listing::factory()->create([
            'dog_id' => $bran->id,
            'seller_id' => $john->id,
            'price' => 500,
        ]);

        Chat::factory()->create([
            'sender_id' => $bruno->id,      // Bruno is asking
            'receiver_id' => $john->id,     // John is the seller
            'listing_id' => $listing->id,
            'message' => 'Is Bran still available?',
        ]);
    }
}
