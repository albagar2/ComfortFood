<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteUserForm extends Component
{
    use PasswordValidationRules;

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        $user = Auth::user();

        // Check for active orders
        $activeOrdersQuery = \App\Models\Pedido::where(function ($query) use ($user) {
            if ($user->isCliente()) {
                $query->where('id_cliente', $user->cliente->id_cliente);
            } elseif ($user->isRestaurante()) {
                $query->where('id_restaurante', $user->restaurante->id_restaurante);
            }
        })->whereHas('estado', function ($query) {
            $query->whereNotIn('nombre_estado', ['Completado', 'Cancelado']);
        });

        if ($activeOrdersQuery->exists()) {
            $this->addError('password', 'No puedes eliminar tu cuenta mientras tengas pedidos en curso. Finaliza o cancela los pedidos activos antes de continuar.');
            return;
        }

        tap($user, $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
