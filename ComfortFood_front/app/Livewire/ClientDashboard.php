<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class ClientDashboard extends Component
{
    public $search = '';

    public function render()
    {
        $query = Menu::where('esta_activo', true)
            ->with(['restaurante.user'])
            ->latest(); // Or random/trending order

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre_menu', 'like', '%' . $this->search . '%')
                  ->orWhereHas('restaurante.user', function($sq) {
                      $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return view('livewire.client-dashboard', [
            'menus' => $query->get()
        ]);
    }
}
