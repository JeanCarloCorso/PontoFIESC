<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'cpf' => '12345678901',
            'matricula' => 10001,
            'nome' => 'Administrador',
            'usuario' => 'admin',
            'email' => 'admin@example.com',
            'telefone' => '49 99187-2380',
            'dataNascimento' => '1980-01-01',
            'dataAdmissao' => now(),
            'funcoes_id' => 1, 
            'cargo_id' => 1, 
            'senha' => Hash::make('admin'), 
            'tipo' => 'administrador',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Usuário Comum
        DB::table('users')->insert([
            'cpf' => '98765432109',
            'matricula' => 10002,
            'nome' => 'Usuário Comum',
            'usuario' => 'usuario',
            'email' => 'usuario@example.com',
            'telefone' => '49 99187-2380',
            'dataNascimento' => '1990-01-01',
            'dataAdmissao' => now(),
            'funcoes_id' => 2,
            'cargo_id' => 2, 
            'senha' => Hash::make('admin'),
            'tipo' => 'usuario',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
