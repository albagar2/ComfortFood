<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioRestaurante extends Model
{
    use HasFactory;

    protected $table = 'horario_restaurante';
    protected $primaryKey = 'id_horario';

    protected $fillable = [
        'id_restaurante',
        'id_dia',
        'hora_apertura',
        'hora_cierre',
        'esta_abierto',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    public function dia()
    {
        return $this->belongsTo(DiaSemana::class, 'id_dia', 'id_dia');
    }
}
