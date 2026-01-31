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

        // Forced Spanish Order (Lunes -> Domingo)
        $targetOrder = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // Fetch DB days. 
        $dbDays = \App\Models\DiaSemana::all();

        // Map target names to DB IDs
        $orderedIds = [];
        foreach ($targetOrder as $target) {
            $found = $dbDays->first(function ($day) use ($target) {
                // simple normalization
                $dbName = strtolower($this->removeAccents($day->nombre_dia));
                $targetName = strtolower($this->removeAccents($target));
                return $dbName === $targetName;
            });

            if ($found) {
                $orderedIds[] = ['id' => $found->id_dia, 'name' => $target];
            }
        }

        // Fallback
        if (empty($orderedIds)) {
            $orderedIds = [
                ['id' => 1, 'name' => 'Lunes'],
                ['id' => 2, 'name' => 'Martes'],
                ['id' => 3, 'name' => 'Miércoles'],
                ['id' => 4, 'name' => 'Jueves'],
                ['id' => 5, 'name' => 'Viernes'],
                ['id' => 6, 'name' => 'Sábado'],
                ['id' => 7, 'name' => 'Domingo']
            ];
        }

        foreach ($orderedIds as $dayInfo) {
            $idDia = $dayInfo['id'];
            $displayName = strtoupper($dayInfo['name']);

            $horario = $existingHorarios->get($idDia);

            $fullSchedule[] = [
                'id_horario' => $horario ? $horario->id_horario : null,
                'id_dia' => $idDia,
                'nombre_dia' => $displayName,
                'hora_apertura' => $horario && $horario->hora_apertura ? date('H:i', strtotime($horario->hora_apertura)) : null,
                'hora_cierre' => $horario && $horario->hora_cierre ? date('H:i', strtotime($horario->hora_cierre)) : null,
                'esta_abierto' => $horario ? (bool) $horario->esta_abierto : false,
            ];
        }

        $this->schedule = $fullSchedule;
    }

    private function removeAccents($string)
    {
        $map = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        ];
        return strtr($string, $map);
    }

    // Menu Actions
    protected $listeners = ['deleteMenuConfirmed' => 'deleteMenu'];

    public function confirmDeleteMenu($menuId)
    {
        $this->dispatch(
            'show-confirmation',
            title: '¿Eliminar Menú?',
            message: '¿Estás seguro de que deseas eliminar este menú? Esta acción no se puede deshacer.',
            confirmAction: 'deleteMenuConfirmed',
            confirmParams: [$menuId],
            confirmText: 'Sí, Eliminar',
            cancelText: 'Cancelar'
        );
    }

    public function deleteMenu($menuId)
    {
        $menu = \App\Models\Menu::find($menuId);

        if ($menu && $menu->id_restaurante === $this->restaurante->id_restaurante) {
            $menu->delete();
            $this->dispatch('notify', 'Menú eliminado correctamente.');
        }
    }

    // Client Action
    public function addToCart($menuId)
    {
        $menu = \App\Models\Menu::find($menuId);
        if (!$menu || $menu->stock <= 0) {
            $this->dispatch('notify', 'Producto agotado o no disponible.');
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity']++;
        } else {
            $cart[$menuId] = [
                "name" => $menu->nombre_menu,
                "quantity" => 1,
                "price" => $menu->precio,
                "image" => $menu->url_foto,
                "restaurant_id" => $menu->id_restaurante
            ];
        }

        session()->put('cart', $cart);

        // Force refresh of any cart components
        $this->dispatch('cart-updated');
        $this->dispatch('notify', 'Producto añadido al carrito.');
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
