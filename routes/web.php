<?php

use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/formularios/criar', function () {
    return view('formularios.create'); 
})->name('formularios.create'); 