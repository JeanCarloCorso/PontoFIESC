<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistroPontosSeeder extends Seeder
{
    public function run()
    {
        // Data de hoje e ontem
        $hoje = Carbon::now()->toDateString();
        $ontem = Carbon::yesterday()->toDateString();

        $registros = [
            [
                'matricula' => 10001,
                'data' => $ontem,
                'hora' => '08:00:00',
                'tipo' => 'entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10001,
                'data' => $ontem,
                'hora' => '12:00:00',
                'tipo' => 'saida',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10001,
                'data' => $ontem,
                'hora' => '13:00:00',
                'tipo' => 'entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10001,
                'data' => $ontem,
                'hora' => '17:00:00',
                'tipo' => 'saida',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10001,
                'data' => $hoje,
                'hora' => '08:30:00',
                'tipo' => 'entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10001,
                'data' => $hoje,
                'hora' => '12:30:00',
                'tipo' => 'saida',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10002,
                'data' => $ontem,
                'hora' => '08:00:00',
                'tipo' => 'entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10002,
                'data' => $ontem,
                'hora' => '12:00:00',
                'tipo' => 'saida',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10002,
                'data' => $ontem,
                'hora' => '13:00:00',
                'tipo' => 'entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula' => 10002,
                'data' => $ontem,
                'hora' => '17:00:00',
                'tipo' => 'saida',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('registrospontos')->insert($registros);
    }
}
