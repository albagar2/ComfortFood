<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $status = '';

    protected $listeners = [
        'searchUpdated' => 'updateSearch',
        'typeUpdated' => 'updateType',
        'statusUpdated' => 'updateStatus',
    ];

    public function updateSearch($query)
    {
        $this->search = $query;
        $this->resetPage();
    }

    public function updateType($type)
    {
        $this->type = $type;
        $this->resetPage();
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->with(['cliente', 'restaurante', 'rol'])
            ->where('id_rol', '!=', 1); // Exclude Admin

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre_completo', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhereHas('cliente', function ($sq) {
                      $sq->where('direccion', 'like', '%' . $this->search . '%')
                        ->orWhere('DNI', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('restaurante', function ($sq) {
                      $sq->where('direccion', 'like', '%' . $this->search . '%')
                        ->orWhere('NIF', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->type === 'cliente') {
            $query->where('id_rol', 2);
        } elseif ($this->type === 'restaurante') {
            $query->where('id_rol', 3);
        }

        if ($this->status === 'activo') {
            $query->where('es_activo', true);
        } elseif ($this->status === 'inactivo') {
            $query->where('es_activo', false);
        }

        return view('livewire.admin.user-list', [
            'users' => $query->latest('id_usuario')->paginate(10)
        ]);
    }
}
