<?php

namespace Database\Factories;

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterClassFactory extends Factory
{
    protected $model = MasterClass::class;

    public function definition(): array
    {
        return [
            'master_id' => User::factory()->create(['role' => 'master']),
            'type_id' => CreativityType::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time_slot' => fake()->randomElement(['09:00', '11:00', '13:00', '15:00']),
            'max_seats' => fake()->numberBetween(5, 20),
            'price' => fake()->randomFloat(2, 10, 100),
        ];
    }
}
