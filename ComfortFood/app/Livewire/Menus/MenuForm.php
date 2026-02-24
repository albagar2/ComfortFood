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
    public $current_foto;
    public $stock = 0; // Default stock
    public $isEditing = false;

    public function mount(?Menu $menu = null)
    {
        if ($menu && $menu->exists) {
            $this->isEditing = true;
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
        }
    }

    public function save(ImageService $imageService)
    {
        // Validaciones base
        $rules = [
            'nombre_menu' => 'required|string|min:4|max:100',
            'plato_principal' => 'required|string|min:4|max:100',
            'segundo_plato' => 'nullable|string|min:4|max:100',
            'postre' => 'nullable|string|min:4|max:100',
            'bebida' => 'nullable|string|min:4|max:100',
            'descripcion_menu' => 'required|string|min:10',
            'propiedades_nutricionales' => 'nullable|string',
            'precio' => 'required|numeric|between:1,50',
            'stock' => 'required|integer|min:1',
        ];

        // Foto obligatoria solo en creación, opcional en edición
        if (!$this->isEditing) {
            $rules['foto'] = 'required|image|max:10240';
        } else {
            $rules['foto'] = 'nullable|image|max:10240';
        }

        $this->validate($rules);

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

        // Handle Image before save
        if ($this->foto) {
            $timestamp = time();
            $restaurantName = Auth::user()->nombre_completo;
            $baseCustomName = \Illuminate\Support\Str::slug($this->nombre_menu . '-' . $restaurantName) . '-' . $timestamp;

            // Store new image
            $path = $imageService->processAndStore($this->foto, 'menus', null, null, 80, $baseCustomName);

            $data['url_foto'] = $path;
            $data['url_foto_card'] = $path;

            // Keep track of old images to delete later if this is an update
            $oldFoto = ($this->menu && $this->menu->exists) ? $this->menu->url_foto : null;
            $oldFotoCard = $this->isEditing ? $this->menu->url_foto_card : null;
        }

        if ($this->isEditing) {
            $this->menu->update($data);
            $wasUpdate = true;
        } else {
            $data['id_restaurante'] = Auth::user()->restaurante->id_restaurante;
            $data['esta_activo'] = true;
            $this->menu = Menu::create($data);
            $wasUpdate = false;
        }

        // Cleanup old images only if update was successful and we have new ones
        if ($this->foto && $wasUpdate) {
            if ($oldFoto) {
                $imageService->delete($oldFoto);
            }
            if ($oldFotoCard && $oldFotoCard !== $oldFoto) {
                $imageService->delete($oldFotoCard);
            }
        }

        session()->flash('success', $wasUpdate ? 'Menú actualizado correctamente.' : 'Menú creado correctamente.');

        return redirect()->route('menu.index');
    }

    public function render()
    {
        return view('livewire.menus.menu-form');
    }
}
