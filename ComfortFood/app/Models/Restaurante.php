<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Restaurante extends Model
{
    use HasFactory;

    protected $table = 'restaurante';
    protected $primaryKey = 'id_restaurante';

    protected $fillable = [
        'id_usuario',
        'tipo_cocina',
        'redes_sociales',
        'descripcion',
        'cuenta_bancaria_mock',
        'NIF',
        'url_imagen_perfil',
        'direccion',
        'telefono',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_restaurante', 'id_restaurante');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioRestaurante::class, 'id_restaurante', 'id_restaurante');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_restaurante', 'id_restaurante');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'id_restaurante', 'id_restaurante');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'id_restaurante', 'id_restaurante');
    }

    public function isOpen()
    {
        $now = now();
        $currentDayId = $now->format('N');
        $currentTime = $now->format('H:i:s');

        // Use the relation property (collection) if loaded, otherwise relation method query
        // Ideally should be eager loaded for lists
        $schedule = $this->horarios->where('id_dia', $currentDayId)->first();

        if (!$schedule || !$schedule->esta_abierto) {
            return false;
        }

        $openingTime = $schedule->hora_apertura;
        $closingTime = $schedule->hora_cierre;

        // Handle midnight (00:00:00) as the end of the day or full day
        if ($closingTime === '00:00:00' || $closingTime === '23:59:59') {
            $closingTime = '23:59:59';
        }

        if ($closingTime < $openingTime) {
            // Spans across midnight
            return $currentTime >= $openingTime || $currentTime <= $closingTime;
        }

        return $currentTime >= $openingTime && $currentTime <= $closingTime;
    }
    public function getUrlImagenPerfilAttribute($value)
    {
        if (!$value) {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Remove 'storage/' or '/storage/' prefix if present to prevent double path
        $value = preg_replace('/^(\/)?storage\//', '', $value);

        return Storage::url($value);
    }
}
