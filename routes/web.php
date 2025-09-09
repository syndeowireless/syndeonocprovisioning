<?php

use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\NetworkProvisioningController;
use App\Http\Controllers\ProvisionController;



Route::get('/', function () {
    return redirect()->route('login');
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


Route::get('/dashboard', function () {
    return redirect('/network-provisioning/create');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/profile/manage-users', [UserController::class, 'manageUsers'])
    ->middleware(['auth', 'admin'])
    ->name('others.manage-users');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profile/admin-requests', [AdminRequestController::class, 'index'])->name('others.admin-requests');
    Route::post('/profile/admin-requests/{adminRequest}/accept', [AdminRequestController::class, 'accept'])->name('others.admin-requests.accept');
    Route::post('/profile/admin-requests/{adminRequest}/reject', [AdminRequestController::class, 'reject'])->name('others.admin-requests.reject');
});

Route::post('/profile/users', [UserController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('users.store');

Route::put('/profile/users/{user}', [UserController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('users.update');

Route::put('/profile/users/{user}/reset-password', [UserController::class, 'resetPassword'])
    ->middleware(['auth', 'admin'])
    ->name('users.reset-password');

Route::delete('/profile/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('users.destroy');

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
})->middleware(['auth'])->name('pfsense');



Route::get('/network-provisioning/create', function () {
    return view('network-provisioning.create');
})->middleware(['auth', 'admin'])->name('network-provisioning.create');


Route::post('/network-provisioning/store', [NetworkProvisioningController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('network-provisioning.store');

Route::get('/network-provisioning', function () {
    return view('network-provisioning.index');
})->middleware(['auth'])->name('network-provisioning.index');

Route::get('/network-provisioning/pfsense', function () {
    return view('network-provisioning.pfsense');
})->middleware(['auth'])->name('network-provisioning.pfsense');


Route::get('/network-provisioning/download-xml/{fileName}', [NetworkProvisioningController::class, 'downloadXml'])
    ->middleware(['auth'])
    ->name('network-provisioning.downloadXml');
Route::get('/network-provisioning/download-xml-db/{id}', [NetworkProvisioningController::class, 'downloadXmlFromDatabase'])
    ->middleware(['auth'])
    ->name('network-provisioning.downloadXmlFromDatabase');

Route::middleware(['auth'])->group(function () {
    Route::get('/network-provisioning/search', function () {
        return view('network-provisioning.search');
    })->name('network-provisioning.search');
    
    Route::get('/network-provisioning/details/{id}', [NetworkProvisioningController::class, 'showDetails'])
        ->name('network-provisioning.details');
});

Route::post('/provision/start', [ProvisionController::class, 'start'])->name('provision.start');

// Test route to demonstrate the enhanced 403 error page
Route::get('/admin/test', function () {
    return view('dashboard'); // Simple view for testing
})->middleware(['auth', 'admin'])->name('admin.test');
