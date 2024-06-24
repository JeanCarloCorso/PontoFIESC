<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'matricula',
        'DiaSemana',
        'entrada1',
        'saida1',
        'entrada2',
        'saida2',
    ];

    protected $dates = [
        'entrada1',
        'saida1',
        'entrada2',
        'saida2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'matricula', 'matricula');
    }
}
