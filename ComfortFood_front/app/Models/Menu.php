<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_restaurante',
        'nombre_menu',
        'descripcion_menu',
        'precio',
        'url_foto',
        'plato_principal',
        'segundo_plato',
        'postre',
        'bebida',
        'propiedades_nutricionales',
        'esta_activo',
        'stock',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'esta_activo' => 'boolean',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'id_menu', 'id_menu');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'id_menu', 'id_menu');
    }

    public function carrito()
    {
        return $this->hasMany(Carrito::class, 'id_menu', 'id_menu');
    }
}
