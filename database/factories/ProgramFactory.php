<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
    public function definition()
    {
        // Definir los nombres de los programas en el orden deseado
        $programNames = ['Python', 'Ciberseguridad', 'PHP', 'Javascript'];

        // Obtener un nombre de programa en el orden específico (javascript, php, ciberseguridad, python)
        $programName = $programNames[fake()->unique()->numberBetween(0, 3)];

        // Obtener el nombre del archivo de imagen asociado al programa
        $imageName = $programName . '.png';

        // Mover la imagen a la ruta con el nombre del programa asociado
        $imagePath = 'images/' . $imageName;
        Storage::move('public/images/' . $imageName, 'public/' . $imagePath);

        return [
            'name' => $programName,
            'description' => fake()->paragraph(),
            'price' => fake()->randomDigit(),
            'image' => $imagePath,
        ];
    }
};
