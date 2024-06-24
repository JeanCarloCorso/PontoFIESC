<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcoes extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'funcoes_id', 'id');
    }
}
