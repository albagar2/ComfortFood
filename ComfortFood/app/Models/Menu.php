<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    // ... (existing properties)

    public function getUrlFotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Remove 'storage/' or '/storage/' prefix if present
        $value = preg_replace('/^(\/)?storage\//', '', $value);

        return Storage::url($value);
    }

    public function getUrlFotoCardAttribute($value)
    {
        if (!$value) {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Remove 'storage/' or '/storage/' prefix if present
        $value = preg_replace('/^(\/)?storage\//', '', $value);

        return Storage::url($value);
    }

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_restaurante',
        'nombre_menu',
        'descripcion_menu',
        'precio',
        'url_foto',
        'url_foto_card',
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

    /**
     * N:M Relationship with Cliente via favorito pivot table.
     */
    public function clientesQueLoFavorecen()
    {
        return $this->belongsToMany(Cliente::class, 'favorito', 'id_menu', 'id_cliente')
            ->withPivot('id_restaurante')
            ->withTimestamps();
    }

    public function carrito()
    {
        return $this->hasMany(Carrito::class, 'id_menu', 'id_menu');
    }

    public function hasActiveOrders(): bool
    {
        return $this->detallesPedido()
            ->whereHas('pedido.estado', function ($query) {
                $query->whereNotIn('nombre_estado', ['Completado', 'Cancelado']);
            })->exists();
    }
}
