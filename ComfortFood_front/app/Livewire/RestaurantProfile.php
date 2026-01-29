<?php

namespace App\Livewire;

use App\Models\Restaurante;
use Livewire\Component;

class RestaurantProfile extends Component
{
    public Restaurante $restaurante;

    public function mount(Restaurante $restaurante)
    {
        $this->restaurante = $restaurante->load(['user', 'menus' => function($query) {
            $query->where('esta_activo', true);
        }]);
    }

    public function render()
    {
        return view('livewire.restaurant-profile');
    }
}
