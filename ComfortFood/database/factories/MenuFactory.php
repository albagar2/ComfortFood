<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Restaurante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_restaurante' => Restaurante::factory(),
            'nombre_menu' => $this->faker->words(3, true),
            'descripcion_menu' => $this->faker->sentence(10),
            'precio' => $this->faker->randomFloat(2, 5, 30),
            'url_foto' => null,
            'url_foto_card' => null,
            'plato_principal' => $this->faker->word,
            'segundo_plato' => $this->faker->word,
            'postre' => $this->faker->word,
            'bebida' => $this->faker->word,
            'propiedades_nutricionales' => $this->faker->text(100),
            'esta_activo' => true,
            'stock' => $this->faker->numberBetween(1, 50),
        ];
    }
}
