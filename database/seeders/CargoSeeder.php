<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cargos')->insert([
            'descricao' => 'Gerente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cargos')->insert([
            'descricao' => 'Analista',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cargos')->insert([
            'descricao' => 'Trainee',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
