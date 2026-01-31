<?php

namespace App\Livewire\Shared\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmationModal extends Component
{
    public $show = false;
    public $title = '¿Estás seguro?';
    public $message = '';
    public $confirmText = 'Confirmar';
    public $cancelText = 'Cancelar';
    public $confirmAction = '';
    public $confirmParams = [];

    #[On('show-confirmation')]
    public function showConfirmation($title, $message, $confirmAction, $confirmParams = [], $confirmText = 'Confirmar', $cancelText = 'Cancelar')
    {
        $this->title = $title;
        $this->message = $message;
        $this->confirmAction = $confirmAction;
        $this->confirmParams = $confirmParams;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
        $this->show = true;
    }

    public function confirm()
    {
        $this->show = false;

        // Dispatch the confirmed action
        $this->dispatch($this->confirmAction, ...$this->confirmParams);
    }

    public function cancel()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.shared.modals.confirmation-modal');
    }
}
