<?php

namespace App\Livewire\Shared\Badges;

use App\Models\Resena;
use Livewire\Attributes\On;
use Livewire\Component;

class NewReviewsBadge extends Component
{
    #[On('refresh-badges')]
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

        return view('livewire.shared.badges.new-reviews-badge', ['count' => $count]);
    }
}
