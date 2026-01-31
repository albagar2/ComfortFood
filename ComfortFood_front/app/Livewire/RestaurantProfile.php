<?php

namespace App\Livewire;

use App\Models\Restaurante;
use Livewire\Attributes\Computed;
use Livewire\Component;

use Livewire\WithFileUploads;

class RestaurantProfile extends Component
{
    use WithFileUploads;

    public Restaurante $restaurante;
    public $search = '';
    public $sort = 'latest';
    public $schedule = [];

    public function mount(Restaurante $restaurante)
    {
        $this->restaurante = $restaurante->load('user');
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        $existingHorarios = $this->restaurante->horarios->keyBy('id_dia');
        $fullSchedule = [];

        // Ensure we have entries for days 1 (Monday) through 7 (Sunday)
        $days = \App\Models\DiaSemana::all()->pluck('nombre_dia', 'id_dia')->toArray();
        // Fallback if DiaSemana table is empty or specific IDs are static
        if (empty($days)) {
            $days = [
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado',
                7 => 'Domingo'
            ];
        }

        foreach ($days as $idDia => $nombreDia) {
            $horario = $existingHorarios->get($idDia);

            $fullSchedule[] = [
                'id_horario' => $horario ? $horario->id_horario : null, // Null implies new record needed
                'id_dia' => $idDia,
                'nombre_dia' => $nombreDia,
                'hora_apertura' => $horario && $horario->hora_apertura ? date('H:i', strtotime($horario->hora_apertura)) : null,
                'hora_cierre' => $horario && $horario->hora_cierre ? date('H:i', strtotime($horario->hora_cierre)) : null,
                'esta_abierto' => $horario ? (bool) $horario->esta_abierto : false,
            ];
        }

        $this->schedule = $fullSchedule;
    }

    public function updateSchedule()
    {
        foreach ($this->schedule as $dayData) {
            \App\Models\HorarioRestaurante::updateOrCreate(
                [
                    'id_restaurante' => $this->restaurante->id_restaurante,
                    'id_dia' => $dayData['id_dia']
                ],
                [
                    'hora_apertura' => $dayData['hora_apertura'] ?? '00:00',
                    'hora_cierre' => $dayData['hora_cierre'] ?? '00:00',
                    'esta_abierto' => $dayData['esta_abierto'],
                ]
            );
        }

        // Reload to get fresh IDs
        $this->restaurante->refresh();
        $this->loadSchedule();

        $this->dispatch('notify', 'Horario actualizado correctamente.');
    }

    #[Computed]
    public function currentStatus()
    {
        // Calculate dynamic status: Open/Closed and Time
        $now = now();
        // Assuming 1 = Monday, 7 = Sunday matches PHP 'N' format
        $currentDayId = $now->format('N');
        $currentTime = $now->format('H:i:s');

        $todaySchedule = $this->restaurante->horarios->where('id_dia', $currentDayId)->first();

        if (!$todaySchedule || !$todaySchedule->esta_abierto) {
            return [
                'isOpen' => false,
                'text' => 'Cerrado',
                'class' => 'bg-zinc-100 text-zinc-500 border-zinc-200'
            ];
        }

        if ($currentTime >= $todaySchedule->hora_apertura && $currentTime <= $todaySchedule->hora_cierre) {
            return [
                'isOpen' => true,
                'text' => 'Abierto hasta ' . date('H:i', strtotime($todaySchedule->hora_cierre)),
                'class' => 'bg-emerald-100/50 text-emerald-700 border-emerald-200' // Green
            ];
        }

        return [
            'isOpen' => false,
            'text' => 'Cerrado ahora',
            'class' => 'bg-zinc-100 text-zinc-500 border-zinc-200'
        ];
    }

    #[Computed]
    public function menus()
    {
        $query = $this->restaurante->menus()->where('esta_activo', true);

        if ($this->search) {
            $query->where('nombre_menu', 'like', '%' . $this->search . '%');
        }

        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('precio', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->get();
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
