<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaSemana extends Model
{
    use HasFactory;

    protected $table = 'dia_semana';
    protected $primaryKey = 'id_dia';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_dia',
        'nombre_dia',
    ];

    public function horarios()
    {
        return $this->hasMany(HorarioRestaurante::class, 'id_dia', 'id_dia');
    }
}
