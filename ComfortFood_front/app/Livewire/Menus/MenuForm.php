<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class MenuForm extends Component
{
    use WithFileUploads;

    public ?Menu $menu = null;

    // Form fields
    public $nombre_menu;
    public $plato_principal;
    public $segundo_plato;
    public $postre;
    public $bebida;
    public $descripcion_menu;
    public $propiedades_nutricionales;
    public $precio;
    public $foto;
    public $foto_card; // New property for thumbnail
    public $current_foto;
    public $current_foto_card; // New property for current thumbnail
    public $stock = 0; // Default stock

    public function mount(?Menu $menu = null)
    {
        if ($menu && $menu->exists) {
            $this->menu = $menu;
            $this->nombre_menu = $menu->nombre_menu;
            $this->plato_principal = $menu->plato_principal;
            $this->segundo_plato = $menu->segundo_plato;
            $this->postre = $menu->postre;
            $this->bebida = $menu->bebida;
            $this->descripcion_menu = $menu->descripcion_menu;
            $this->propiedades_nutricionales = $menu->propiedades_nutricionales;
            $this->precio = $menu->precio;
            $this->stock = $menu->stock;
            $this->current_foto = $menu->url_foto;
            $this->current_foto_card = $menu->url_foto_card;
        }
    }

    public function save(ImageService $imageService)
    {
        $this->validate([
            'nombre_menu' => 'required|string|max:255',
            'plato_principal' => 'nullable|string|max:255',
            'segundo_plato' => 'nullable|string|max:255',
            'postre' => 'nullable|string|max:255',
            'bebida' => 'nullable|string|max:255',
            'descripcion_menu' => 'nullable|string',
            'propiedades_nutricionales' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'foto' => 'nullable|image',
            'foto_card' => 'nullable|image',
        ]);

        $data = [
            'nombre_menu' => $this->nombre_menu,
            'plato_principal' => $this->plato_principal,
            'segundo_plato' => $this->segundo_plato,
            'postre' => $this->postre,
            'bebida' => $this->bebida,
            'descripcion_menu' => $this->descripcion_menu,
            'propiedades_nutricionales' => $this->propiedades_nutricionales,
            'precio' => $this->precio,
            'stock' => $this->stock,
        ];

        // Only set id_restaurante for new creations OR ensure it matches the user
        if (!$this->menu || !$this->menu->exists) {
            $data['id_restaurante'] = Auth::user()->restaurante->id_restaurante;
            $data['esta_activo'] = true;
        }

        if ($this->foto) {
            // High quality version
            $data['url_foto'] = $imageService->processAndStore($this->foto, 'menus');
        }

        if ($this->foto_card) {
            // Thumbnail version
            $data['url_foto_card'] = $imageService->processAndStore($this->foto_card, 'menus');
        }

        if ($this->menu && $this->menu->exists) {
            $this->menu->update($data);
            session()->flash('success', 'Menú actualizado correctamente.');
        } else {
            Menu::create($data);
            session()->flash('success', 'Menú creado correctamente.');
        }

        return redirect()->route('menu.index');
    }

    public function render()
    {
        return view('livewire.menus.menu-form');
    }
}
