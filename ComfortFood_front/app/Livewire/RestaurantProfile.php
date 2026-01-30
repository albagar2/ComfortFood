<?php

namespace App\Livewire;

use App\Models\Restaurante;
use Livewire\Component;

use Livewire\WithFileUploads;

class RestaurantProfile extends Component
{
    use WithFileUploads;

    public Restaurante $restaurante;
    public $photo;

    public function mount(Restaurante $restaurante)
    {
        $this->restaurante = $restaurante->load([
            'user',
            'menus' => function ($query) {
                $query->where('esta_activo', true);
            }
        ]);
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);

        $path = $this->photo->store('restaurants', 'public');

        $this->restaurante->update([
            'url_imagen_perfil' => '/storage/' . $path,
        ]);

        $this->dispatch('image-updated');
    }

    public function render()
    {
        return view('livewire.restaurant-profile');
    }
}
