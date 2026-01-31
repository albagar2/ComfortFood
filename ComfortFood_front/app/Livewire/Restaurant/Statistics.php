<?php

namespace App\Livewire\Restaurant;

use App\Models\Pedido;
use App\Models\Resena;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistics extends Component
{
    public $monthlyEarnings = [];
    public $satisfactionStats = [];

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
        $this->loadEarnings();
        $this->loadSatisfaction();
        $this->loadAdvancedMetrics();
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

    public function loadEarnings()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;

        // Obtener ingresos de los últimos 12 meses
        $earnings = Pedido::where('id_restaurante', $restauranteId)
            ->whereHas('estado', function ($q) {
                $q->where('nombre_estado', 'Completado');
            })
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('SUM(precio_total) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Rellenar meses sin datos
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
            $monthName = Carbon::now()->subMonths($i)->translatedFormat('M');
            $months[$monthName] = $earnings[$monthKey] ?? 0;
        }

        $this->monthlyEarnings = $months;
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
