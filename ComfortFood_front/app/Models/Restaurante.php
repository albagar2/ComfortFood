<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
