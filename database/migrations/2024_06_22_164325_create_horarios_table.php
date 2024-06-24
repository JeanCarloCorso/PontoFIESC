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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->integer('matricula');
            $table->foreign('matricula')->references('matricula')->on('users')->onDelete('cascade');
            $table->tinyInteger('DiaSemana');
            $table->dateTime('entrada1');
            $table->dateTime('saida1');
            $table->dateTime('entrada2')->nullable();
            $table->dateTime('saida2')->nullable();
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
        Schema::dropIfExists('horarios');
    }
};
