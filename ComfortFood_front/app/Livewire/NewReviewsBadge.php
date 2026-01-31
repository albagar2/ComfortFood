<?php

namespace App\Livewire;

use App\Models\Resena;
use Livewire\Component;

class NewReviewsBadge extends Component
{
    #[Livewire\Attributes\On('refresh-badges')]
    public function refresh()
    {
    }

    public function render()
    {
        $count = 0;
        if (auth()->check() && auth()->user()->isRestaurante()) {
            $count = Resena::where('id_restaurante', auth()->user()->restaurante->id_restaurante)
                ->where('visto', false)
                ->count();
        }

        return view('livewire.new-reviews-badge', ['count' => $count]);
    }
}
