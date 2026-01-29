<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
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
    public $current_foto;

    public function mount(Menu $menu = null)
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
            $this->current_foto = $menu->url_foto;
        }
    }

    public function save()
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
            'foto' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $data = [
            'id_restaurante' => Auth::user()->restaurante->id_restaurante,
            'nombre_menu' => $this->nombre_menu,
            'plato_principal' => $this->plato_principal,
            'segundo_plato' => $this->segundo_plato,
            'postre' => $this->postre,
            'bebida' => $this->bebida,
            'descripcion_menu' => $this->descripcion_menu,
            'propiedades_nutricionales' => $this->propiedades_nutricionales,
            'precio' => $this->precio,
        ];

        if ($this->foto) {
             // Store image logic - simplified for storage link
             $path = $this->foto->store('menus', 'public');
             $data['url_foto'] = '/storage/' . $path;
        }

        if ($this->menu && $this->menu->exists) {
            $this->menu->update($data);
        } else {
            $data['esta_activo'] = true;
            Menu::create($data);
        }

        return redirect()->route('menu.index');
    }

    public function render()
    {
        return view('livewire.menus.menu-form');
    }
}
