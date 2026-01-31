<?php

namespace App\Livewire\Restaurant;

use App\Models\Pedido;
use App\Models\Resena;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistics extends Component
{
    public $satisfactionStats = [];
    public $stats = [];

    // New metrics
    public $dailyOrders = [];
    public $peakDays = [];
    public $topMenus = [];

    // Review filters
    public $search = '';
    public $filterRating = '';
    public $filterDate = '';

    public function mount()
    {
        $this->loadSatisfaction();
        $this->loadAdvancedMetrics();
        $this->loadQuickStats();
        $this->markReviewsAsSeen();
    }

    public function markReviewsAsSeen()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;
        Resena::where('id_restaurante', $restauranteId)
            ->where('visto', false)
            ->update(['visto' => true]);

        $this->dispatch('refresh-badges');
    }

    public function loadSatisfaction()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;

        $resenas = Resena::where('id_restaurante', $restauranteId);

        $totalResenas = (clone $resenas)->count();
        $promedio = (clone $resenas)->avg('puntuacion') ?? 0;

        $positivas = (clone $resenas)->where('puntuacion', '>=', 4)->count();
        $negativas = (clone $resenas)->where('puntuacion', '<=', 2)->count();

        $this->satisfactionStats = [
            'total' => $totalResenas,
            'year' => Carbon::now()->year,
            'promedio' => number_format($promedio, 1),
            'positivas_pct' => $totalResenas > 0 ? round(($positivas / $totalResenas) * 100) : 0,
            'negativas_pct' => $totalResenas > 0 ? round(($negativas / $totalResenas) * 100) : 0,
            'diff' => '+0.3', // Mockup de diferencia respecto al año anterior
        ];
    }

    public function loadQuickStats()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;

        $ordersToday = Pedido::where('id_restaurante', $restauranteId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $ordersWeek = Pedido::where('id_restaurante', $restauranteId)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        $ratingLast7Days = Resena::where('id_restaurante', $restauranteId)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->avg('puntuacion') ?? 0;

        $this->stats = [
            'orders_today' => $ordersToday,
            'orders_week' => $ordersWeek,
            'rating_last_7_days' => number_format($ratingLast7Days, 1),
        ];
    }

    public function loadAdvancedMetrics()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;

        // Daily Orders (Last 30 days)
        $this->dailyOrders = Pedido::where('id_restaurante', $restauranteId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Peak Days (Names of days with more orders)
        $this->peakDays = Pedido::where('id_restaurante', $restauranteId)
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->orderByDesc('count')
            ->limit(3)
            ->get()
            ->pluck('count', 'day')
            ->toArray();

        // Top Rated Menus (Subquery to get avg rating per menu)
        $this->topMenus = DB::table('resena')
            ->join('pedido', 'resena.id_pedido', '=', 'pedido.id_pedido')
            ->join('detalle_pedido', 'pedido.id_pedido', '=', 'detalle_pedido.id_pedido')
            ->join('menu', 'detalle_pedido.id_menu', '=', 'menu.id_menu')
            ->where('resena.id_restaurante', $restauranteId)
            ->select('menu.nombre_menu', DB::raw('AVG(resena.puntuacion) as avg_rating'), DB::raw('COUNT(resena.id_resena) as count'))
            ->groupBy('menu.id_menu', 'menu.nombre_menu')
            ->orderByDesc('avg_rating')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }

    public function getReviewsProperty()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;
        $query = Resena::with(['cliente.user', 'pedido'])->where('id_restaurante', $restauranteId);

        if ($this->search) {
            $query->whereHas('cliente.user', function ($q) {
                $q->where('nombre_completo', 'like', '%' . $this->search . '%');
            })->orWhere('comentario', 'like', '%' . $this->search . '%');
        }

        if ($this->filterRating) {
            $query->where('puntuacion', $this->filterRating);
        }

        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        return $query->latest()->get();
    }

    public function render()
    {
        return view('livewire.restaurant.statistics');
    }
}
