<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'matricula';

    protected $fillable = [
        'cpf',
        'telefone',
        'matricula',
        'nome',
        'usuario',
        'email',
        'dataNascimento',
        'dataAdmissao',
        'dataRecisao',
        'funcoes_id',
        'cargo_id',
        'senha',
        'tipo',
    ];

    protected $hidden = [
        'senha', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function funcao()
    {
        return $this->belongsTo(Funcoes::class, 'funcoes_id', 'id');
    }

    
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

}
