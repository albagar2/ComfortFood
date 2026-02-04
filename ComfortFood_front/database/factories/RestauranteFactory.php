<?php

namespace Database\Factories;

use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurante>
 */
class RestauranteFactory extends Factory
{
    protected $model = Restaurante::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'tipo_cocina' => $this->faker->randomElement(['Italiana', 'Mexicana', 'Japonesa', 'Mediterránea', 'Vegetariana']),
            'redes_sociales' => json_encode(['instagram' => '@' . $this->faker->userName]),
            'descripcion' => $this->faker->text(200),
            'cuenta_bancaria_mock' => $this->faker->iban('ES'),
            'NIF' => $this->faker->regexify('[0-9]{8}[A-Z]'),
            'url_imagen_perfil' => null,
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
        ];
    }
}
