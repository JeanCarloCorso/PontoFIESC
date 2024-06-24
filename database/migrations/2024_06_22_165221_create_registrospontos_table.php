<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrospontos', function (Blueprint $table) {
            $table->id();
            $table->integer('matricula');
            $table->foreign('matricula')->references('matricula')->on('users')->onDelete('cascade');
            $table->date('data');
            $table->time('hora');
            $table->enum('tipo', ['entrada', 'saida']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrospontos');
    }
};
