<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroPonto extends Model
{
    use HasFactory;

    protected $table = 'registrospontos';

    protected $fillable = [
        'cpf',
        'data',
        'hora',
        'tipo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'cpf', 'cpf');
    }
}
