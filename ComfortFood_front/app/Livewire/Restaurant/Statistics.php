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

    public function mount()
    {
        $this->loadEarnings();
        $this->loadSatisfaction();
    }

    public function loadEarnings()
    {
        $restauranteId = auth()->user()->restaurante->id_restaurante;

        // Obtener ingresos de los últimos 12 meses
        $earnings = Pedido::where('id_restaurante', $restauranteId)
            ->where('id_estado_pedido', function ($query) {
                $query->select('id_estado_pedido')
                    ->from('estado_pedido')
                    ->where('nombre_estado', 'Completado');
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

        $resenas = Resena::whereHas('pedido', function ($query) use ($restauranteId) {
            $query->where('id_restaurante', $restauranteId);
        });

        $totalResenas = $resenas->count();
        $promedio = $resenas->avg('puntuacion') ?? 0;

        $positivas = $resenas->clone()->where('puntuacion', '>=', 4)->count();
        $negativas = $resenas->clone()->where('puntuacion', '<=', 2)->count();

        $this->satisfactionStats = [
            'total' => $totalResenas,
            'year' => Carbon::now()->year,
            'promedio' => number_format($promedio, 1),
            'positivas_pct' => $totalResenas > 0 ? round(($positivas / $totalResenas) * 100) : 0,
            'negativas_pct' => $totalResenas > 0 ? round(($negativas / $totalResenas) * 100) : 0,
            'diff' => '+0.3', // Mockup de diferencia respecto al año anterior
        ];
    }

    public function render()
    {
        return view('livewire.restaurant.statistics');
    }
}
