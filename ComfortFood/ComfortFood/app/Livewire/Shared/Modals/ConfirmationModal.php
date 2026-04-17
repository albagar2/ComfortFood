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
    public function showConfirmation($data = [])
    {
        $this->title = $data['title'] ?? '¿Estás seguro?';
        $this->message = $data['message'] ?? '';
        $this->confirmAction = $data['event'] ?? '';
        $this->confirmParams = $data['params'] ?? [];
        $this->confirmText = $data['confirmText'] ?? 'Confirmar';
        $this->cancelText = $data['cancelText'] ?? 'Cancelar';
        $this->show = true;
    }

    public function confirm()
    {
        $this->show = false;

        // Dispatch event that all components can listen to
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
