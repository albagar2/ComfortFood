<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    use HasFactory;

    protected $table = 'estado_pedido';
    protected $primaryKey = 'id_estado_pedido';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_estado_pedido', 'id_estado_pedido');
    }
}
