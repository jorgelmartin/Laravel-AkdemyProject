<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Convocation>
 */
class ConvocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'beginning'=>fake()->date($format = 'Y-m-d', $max = 'now'),
            'schedule'=>fake()->randomElement(['Mañanas 09:00-14:00', 'Tardes 15:00-20:00']),
            // 'user_id' => User::factory(),
            // 'status'=>false
        ];
    }
}
