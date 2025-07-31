<?php

use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NetworkProvisioningController;


Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');


// Rotas de Formulário
Route::middleware(["auth"])->group(function () {
    Route::get("/formularios/create", [FormularioController::class, "create"])->name("formularios.create");
    Route::post("/formularios", [FormularioController::class, "store"])->name("formularios.store");
    Route::get("/formularios", [FormularioController::class, "meus"])->name("formularios.meus");
    Route::get("/formularios/{formulario}", [FormularioController::class, "show"])->name("formularios.show");
});

// Rotas de Admin
Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    Route::get("/formularios", [FormularioController::class, "index"])->name("formularios.index");
    Route::put("/formularios/{formulario}/status", [FormularioController::class, "updateStatus"])->name("formularios.updateStatus");
    // Adicionar rotas para gerenciamento de usuários aqui
}); 

Route::middleware(['auth'])->group(function () {

    Route::get('/create-form', [FormularioController::class, 'create'])
         ->name('create.form'); // Nomeie a rota conforme sua lógica
         
    Route::post('/submit-form', [FormularioController::class, 'store'])
         ->name('submit.form');
});


Route::get('/pfsense', function () {
    return view('layouts.pfsense');
})->name('pfsense');



Route::get('/network-provisioning/create', function () {
    return view('network-provisioning.create');
});


Route::post('/network-provisioning/store', [NetworkProvisioningController::class, 'store'])
    ->name('network-provisioning.store');

Route::get('/network-provisioning/create', function () {
    return view('network-provisioning.create');
})->name('network-provisioning.create');

Route::get('/network-provisioning', function () {
    return view('network-provisioning.index');
})->name('network-provisioning.index');

Route::get('/network-provisioning/pfsense', function () {
    return view('network-provisioning.pfsense');
})->name('network-provisioning.pfsense');