<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'DNI' => $this->faker->regexify('[0-9]{8}[A-Z]'),
            'url_imagen_perfil' => null,
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'tarjeta_mock' => $this->faker->creditCardNumber,
        ];
    }
}
