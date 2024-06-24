<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuncaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcoes')->insert([
            'descricao' => 'Desenvolvedor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('funcoes')->insert([
            'descricao' => 'Designer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('funcoes')->insert([
            'descricao' => 'QA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
