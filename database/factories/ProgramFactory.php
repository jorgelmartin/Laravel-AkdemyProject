<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement($array = array ('ciberseguridad','python','php', 'javascript')),
            'description'=>fake()->paragraph(),
            'price' => fake()->randomDigit(),
            // 'pdf' => fake()->randomElement($array = array ('ciberseguridad','python','php', 'javascript')),
        ];
    }
}
