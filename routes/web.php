<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColaboradoresController;
use App\Http\Controllers\RegistrarPontoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/ponto', [RegistrarPontoController::class, 'pontoView'])->name('ponto.view');
Route::get('/bate-ponto', [RegistrarPontoController::class, 'BatePonto'])->name('bate.ponto');

Route::get('/colaboradores', [ColaboradoresController::class, 'ColaboradoresView'])->name('colaboradores.view');
Route::get('/novo-colaborador', [ColaboradoresController::class, 'NovoColaborador'])->name('novo.colaborador');
Route::post('/novo-colaborador/cadastrar', [ColaboradoresController::class, 'CadastrarNovoColaborador'])->name('colaboradores.cadastrar');
Route::delete('/colaborador/{matricula}', [ColaboradoresController::class, 'destroy'])->name('colaborador.destroy');
Route::get('/editar-colaborador/{matricula}', [ColaboradoresController::class, 'EditarColaborador'])->name('editar.colaborador');
Route::post('/editar-colaborador/salvar/{matricula}', [ColaboradoresController::class, 'SalvaEdicaoColaborador'])->name('colaboradores.salvar');

Route::get('/verificar-cpf', [ColaboradoresController::class, 'VerificarCPF'])->name('verificar.cpf');

Route::get('/registrar-ponto', function () {
    return view('registroPonto');
});

