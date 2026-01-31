<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_cliente',
        'id_restaurante',
        'precio_total',
        'id_estado_pedido',
        'direccion_entrega',
        'visto_completado',
    ];

    protected $casts = [
        'precio_total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'id_estado_pedido', 'id_estado_pedido');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    public function resena()
    {
        return $this->hasOne(Resena::class, 'id_pedido', 'id_pedido');
    }
}
