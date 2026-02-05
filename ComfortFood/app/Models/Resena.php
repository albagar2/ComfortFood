<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'resena';
    protected $primaryKey = 'id_resena';

    protected $fillable = [
        'id_pedido',
        'id_cliente',
        'id_restaurante',
        'id_menu',
        'puntuacion',
        'comentario',
        'visto',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
