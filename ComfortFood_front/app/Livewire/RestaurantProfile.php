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
            // Aquí usamos el nombre original (con tildes) para mostrar
            $originalDay = $dbDays->first(fn($d) => $d->id_dia == $idDia)->nombre_dia;

            $displayName = mb_strtoupper($originalDay); // mantiene tildes

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

        if (!$menu || $menu->id_restaurante !== $this->restaurante->id_restaurante) {
            return;
        }

        // Check for active orders containing this menu
        // We strictly block if the order is "Pendiente" or "En Preparación" or "En camino"
        // User Request: "todos los menus que no esten completados y han sido puestos en preparacion no podrán ser eliminados... avisar con alert"

        $hasActiveOrders = \App\Models\Pedido::whereHas('detalles', function ($q) use ($menuId) {
            $q->where('id_menu', $menuId);
        })->whereHas('estado', function ($q) {
            $q->whereIn('nombre_estado', ['Pendiente', 'En Preparación', 'En camino', 'Entregado']);
        })->exists();

        if ($hasActiveOrders) {
            $this->dispatch('notify', 'No se puede eliminar el menú: Está incluido en pedidos activos (pendiente o en preparación). Complete o cancele los pedidos primero.');
            return;
        }

        $menu->delete();
        $this->dispatch('notify', 'Menú eliminado correctamente.');
    }

    public $selectedMenu = null;
    public $observation = '';
    public $quantity = 1;

    // Direct Add To Cart (simple version for profile list)
    public function addToCart($menuId)
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            $this->dispatch('notify', 'Debes iniciar sesión para añadir al carrito');
            return;
        }

        $menu = \App\Models\Menu::find($menuId);
        if (!$menu) {
            $this->dispatch('notify', 'Menú no encontrado');
            return;
        }

        if ($menu->stock <= 0) {
            $this->dispatch('notify', 'Este menú no tiene stock disponible');
            return;
        }

        // Check restaurant consistency
        $existingCart = \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)->first();
        if ($existingCart && $existingCart->id_restaurante != $menu->id_restaurante) {
            $this->dispatch('notify', 'Solo puedes añadir menús de un restaurante a la vez. Vacía tu carrito primero.');
            return;
        }

        // Add logic (Direct 1 item)
        $carritoItem = \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)
            ->where('id_menu', $menu->id_menu)
            ->first();

        if ($carritoItem) {
            if (($carritoItem->cantidad + 1) > $menu->stock) {
                $this->dispatch('notify', 'No hay suficiente stock disponible');
                return;
            }
            $carritoItem->cantidad++;
            $carritoItem->save();
        } else {
            \App\Models\Carrito::create([
                'id_cliente' => $cliente->id_cliente,
                'id_menu' => $menu->id_menu,
                'id_restaurante' => $menu->id_restaurante,
                'cantidad' => 1,
            ]);
        }

        $this->dispatch('cart-updated');
        $this->dispatch('notify', 'Menú añadido al carrito.');
    }

    // Client Action - Step 1: Open Modal
    public function openAddToCartModal($menuId)
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            session()->flash('error', 'Debes iniciar sesión para añadir al carrito');
            return;
        }

        $menu = \App\Models\Menu::find($menuId);
        if (!$menu) {
            session()->flash('error', 'Menú no encontrado');
            return;
        }

        if ($menu->stock <= 0) {
            session()->flash('error', 'Este menú no tiene stock disponible');
            return;
        }

        // Check restaurant consistency
        $existingCart = \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)->first();
        if ($existingCart && $existingCart->id_restaurante != $menu->id_restaurante) {
            session()->flash('error', 'Solo puedes añadir menús de un restaurante a la vez. Vacía tu carrito primero.');
            return;
        }

        $this->selectedMenu = $menu;
        $this->observation = '';
        $this->quantity = 1;

        $this->dispatch('open-add-to-cart-modal');
    }

    // Client Action - Step 2: Confirm Add
    public function confirmAddToCart()
    {
        if (!$this->selectedMenu)
            return;

        $cliente = auth()->user()->cliente;
        $menu = $this->selectedMenu;

        // Check if item already in cart (same menu AND same observations? OR just same menu?)
        // Usually, if observations differ, it should be a separate line item.
        // But for simplicity/schema, usually 'carrito' is (id_cliente, id_menu). 
        // If schema has unique(id_cliente, id_menu), we can't have duplicates.
        // The migration didn't remove unique constraints if they existed. 
        // Let's assume we update the existing item OR create new if unique is not enforced.
        // Standard logic: If item exists, update quantity.
        // BUT if user adds "No onions", then "Extra sauce", they should probably be separate?
        // Current Schema check: `create_carrito_table` likely didn't enforce composite unique since it's a simple ID PK.
        // Let's check `Carrito` model logic from before:
        /*
           $carritoItem = Where... ->first();
           if ($carritoItem) -> quantity++
        */
        // If I update quantity, I overwrite observations? Or just append?
        // User asked "should appear observations". 
        // If I add 1x Burger "No Onion" and then 1x Burger "No Pickle". 
        // If I merge them => 2x Burger "No Pickle" (last overwrite).
        // For MVP, valid to overwrite or merge. 
        // Better: treat as same item index. 
        // I will stick to "Update existing" policy for now to match previous logic, 
        // but I will APPEND the observation if it's new. 
        // OR better: Just overwrite observation with latest generic note?
        // Let's simpler: Create NEW row if observation is different? 
        // `first()` calls might merge them.
        // Let's stick to "Update existing, overwrite observation" for simplicity unless schema allows multiples.
        // Given `carrito` usually prevents duplicates per user/product in basic designs (like mine), I will overwrite.

        $carritoItem = \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)
            ->where('id_menu', $menu->id_menu)
            ->first();

        if ($carritoItem) {
            if (($carritoItem->cantidad + $this->quantity) > $menu->stock) {
                session()->flash('error', 'No hay suficiente stock disponible');
                return;
            }
            $carritoItem->cantidad += $this->quantity;
            if (!empty($this->observation)) {
                $carritoItem->observaciones = $this->observation; // Overwrite/Update note
            }
            $carritoItem->save();
        } else {
            \App\Models\Carrito::create([
                'id_cliente' => $cliente->id_cliente,
                'id_menu' => $menu->id_menu,
                'id_restaurante' => $menu->id_restaurante,
                'cantidad' => $this->quantity,
                'observaciones' => $this->observation,
            ]);
        }

        $this->selectedMenu = null;
        $this->dispatch('close-add-to-cart-modal'); // Close modal
        $this->dispatch('cart-updated');
        session()->flash('success', 'Producto añadido al carrito.');
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
                'class' => 'bg-emerald-300/50 text-white border-emerald-200' // Green
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
