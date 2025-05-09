<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dog;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dog>
 */
class DogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Dog::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'name' => 'Bran', // Static for the seed
            'age' => '2',
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'price' => '500',
            'description' => 'A friendly dog',
            'status' => true,
        ];
    }
}
