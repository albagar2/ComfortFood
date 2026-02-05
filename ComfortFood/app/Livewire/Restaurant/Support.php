<?php

namespace App\Livewire\Restaurant;

use Livewire\Component;

class Support extends Component
{
    public $search = '';

    public function render()
    {
        return view('livewire.restaurant.support');
    }
}
