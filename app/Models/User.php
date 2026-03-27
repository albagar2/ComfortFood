<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre_completo',
        'email',
        'password',
        'id_rol',
        'es_activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'es_activo' => 'boolean',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario');
    }

    public function restaurante()
    {
        return $this->hasOne(Restaurante::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->nombre_completo)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user is a client
     */
    public function isCliente(): bool
    {
        return $this->rol && $this->rol->nombre_rol === 'Cliente';
    }

    /**
     * Check if user is a restaurant
     */
    public function isRestaurante(): bool
    {
        return $this->rol && $this->rol->nombre_rol === 'Restaurante';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->rol && $this->rol->nombre_rol === 'Administrador';
    }

    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        $path = null;

        if ($this->isCliente()) {
            // Check if relation is loaded to avoid N+1
            $cliente = $this->relationLoaded('cliente') ? $this->cliente : $this->cliente()->first();
            $path = $cliente->url_imagen_perfil ?? null;
        } elseif ($this->isRestaurante()) {
            // Check if relation is loaded to avoid N+1
            $restaurante = $this->relationLoaded('restaurante') ? $this->restaurante : $this->restaurante()->first();
            $path = $restaurante->url_imagen_perfil ?? null;
        }

        if (!$path) {
            return null;
        }

        // Handle both absolute URLs (legacy) and relative paths
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // If path already contains storage/, don't prepend it again
        if (Str::startsWith($path, '/storage/') || Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}
