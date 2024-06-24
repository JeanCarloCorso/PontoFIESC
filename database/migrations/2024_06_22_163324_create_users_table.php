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
        Schema::create('users', function (Blueprint $table) {
            $table->string('cpf', 11);
            $table->bigIncrements('matricula')->startingValue(10001);
            $table->string('nome');
            $table->string('usuario');
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->date('dataNascimento');
            $table->date('dataAdmissao');
            $table->date('dataRecisao')->nullable();
            $table->unsignedBigInteger('funcoes_id');
            $table->foreign('funcoes_id')->references('id')->on('funcoes');
            $table->foreignId('cargo_id')->constrained();
            $table->string('senha');
            $table->enum('tipo', ['administrador', 'usuario']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
