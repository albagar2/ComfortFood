<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
    use ProfileValidationRules;
    use \Livewire\WithFileUploads;

    public string $nombre_completo = '';
    public string $email = '';
    public string $rol = '';

    // Client specific fields
    public $foto_perfil; // For the file upload
    public string $direccion = '';
    public string $telefono = '';
    public string $tarjeta_mock = '';

    // Restaurant specific fields
    public string $tipo_cocina = '';
    public string $redes_sociales = '';
    public string $descripcion = '';
    public string $cuenta_bancaria_mock = '';
    public string $NIF = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->nombre_completo = $user->nombre_completo;
        $this->email = $user->email;
        $this->rol = $user->isCliente() ? 'cliente' : ($user->isRestaurante() ? 'restaurante' : 'admin');

        if ($user->isCliente() && $user->cliente) {
            $this->direccion = $user->cliente->direccion ?? '';
            $this->telefono = $user->cliente->telefono ?? '';
            $this->tarjeta_mock = $user->cliente->tarjeta_mock ?? '';
        } elseif ($user->isRestaurante() && $user->restaurante) {
            $this->direccion = $user->restaurante->direccion ?? '';
            $this->telefono = $user->restaurante->telefono ?? '';
            $this->tipo_cocina = $user->restaurante->tipo_cocina ?? '';
            $this->redes_sociales = $user->restaurante->redes_sociales ?? '';
            $this->descripcion = $user->restaurante->descripcion ?? '';
            $this->cuenta_bancaria_mock = $user->restaurante->cuenta_bancaria_mock ?? '';
            $this->NIF = $user->restaurante->NIF ?? '';
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = $this->profileRules($user->id_usuario);

        // Remove rol from validation if we are not changing it
        unset($rules['rol']);

        if ($user->isCliente()) {
            $rules['direccion'] = ['nullable', 'string', 'max:255'];
            $rules['telefono'] = ['nullable', 'string', 'max:20'];
            $rules['tarjeta_mock'] = ['nullable', 'string', 'max:19'];
            $rules['foto_perfil'] = ['nullable', 'image', 'max:1024'];
        } elseif ($user->isRestaurante()) {
            $rules['direccion'] = ['nullable', 'string', 'max:255'];
            $rules['telefono'] = ['nullable', 'string', 'max:20'];
            $rules['tipo_cocina'] = ['nullable', 'string', 'max:255'];
            $rules['redes_sociales'] = ['nullable', 'string', 'max:255'];
            $rules['descripcion'] = ['nullable', 'string', 'max:1000'];
            $rules['cuenta_bancaria_mock'] = ['nullable', 'string', 'max:50'];
            $rules['NIF'] = ['nullable', 'string', 'max:20'];
            $rules['foto_perfil'] = ['nullable', 'image', 'max:1024'];
        }

        $validated = $this->validate($rules);

        $user->fill([
            'nombre_completo' => $validated['nombre_completo'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->isCliente()) {
            $clienteData = [
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'tarjeta_mock' => $this->tarjeta_mock,
            ];

            if ($this->foto_perfil) {
                $path = $this->foto_perfil->store('perfiles', 'public');
                $clienteData['url_imagen_perfil'] = '/storage/' . $path;
            }

            $user->cliente()->updateOrCreate(
                ['id_usuario' => $user->id_usuario],
                $clienteData
            );
        } elseif ($user->isRestaurante()) {
            $restauranteData = [
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'tipo_cocina' => $this->tipo_cocina,
                'redes_sociales' => $this->redes_sociales,
                'descripcion' => $this->descripcion,
                'cuenta_bancaria_mock' => $this->cuenta_bancaria_mock,
                'NIF' => $this->NIF,
            ];

            if ($this->foto_perfil) {
                $path = $this->foto_perfil->store('restaurantes', 'public');
                $restauranteData['url_imagen_perfil'] = '/storage/' . $path;
            }

            $user->restaurante()->updateOrCreate(
                ['id_usuario' => $user->id_usuario],
                $restauranteData
            );
        }

        $this->dispatch('profile-updated', name: $user->nombre_completo);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && !Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return !Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}
