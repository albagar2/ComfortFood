<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class MenuShow extends Component
{
    public Menu $menu;

    public function mount(Menu $menu)
    {
        $this->menu = $menu->load('restaurante.user');
    }

    public function render()
    {
        return view('livewire.menu-show');
    }
}
