<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Dog;
use App\Models\User;
use App\Models\Listing;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Listing::class;

    public function definition(): array
    {
        return [
            'dog_id' => Dog::factory(),
            'seller_id' => User::factory(),
            'price' => 500,
            'status' => true,
        ];
    }
}
