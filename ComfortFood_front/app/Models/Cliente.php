<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_usuario',
        'DNI',
        'url_imagen_perfil',
        'direccion',
        'telefono',
        'tarjeta_mock',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'id_cliente', 'id_cliente');
    }

    /**
     * N:M Relationship with Menu via favorito pivot table.
     */
    public function menusFavoritos()
    {
        return $this->belongsToMany(Menu::class, 'favorito', 'id_cliente', 'id_menu')
            ->withPivot('id_restaurante')
            ->withTimestamps();
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cliente', 'id_cliente');
    }
}
