<?php

namespace Database\Factories;

use App\Models\Contacto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direccion>
 */
class DireccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'calle' => $this->faker->streetAddress,
            'ciudad' => $this->faker->city,
            'estado' => $this->faker->state,
            'codigo_postal' => $this->faker->postcode,
            'contacto_id' => Contacto::factory(),
        ];
    }
}
