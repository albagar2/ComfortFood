<?php

namespace App\Livewire\Shared\Notifications;

use Livewire\Component;
use Livewire\Attributes\On;

class ToastContainer extends Component
{
    public $toasts = [];

    #[On('show-toast')]
    public function addToast($payload)
    {
        $this->toasts[] = [
            'id' => uniqid(),
            'message' => $payload['message'],
            'type' => $payload['type'] ?? 'info',
            'icon' => $payload['icon'] ?? 'bell',
            'sticky' => $payload['sticky'] ?? false,
        ];
    }

    public function removeToast($id)
    {
        $this->toasts = array_filter($this->toasts, fn($toast) => $toast['id'] !== $id);
    }

    public function render()
    {
        return view('livewire.shared.notifications.toast-container');
    }
}
