<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\PdfController;

require __DIR__.'/evolucion_temp.php';
require __DIR__.'/evolucion_final.php';


Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name("login");
    Route::get('/registro', [AuthController::class, 'registro'])->name("registro");
    Route::post('/registrar', [AuthController::class, 'registrar'])->name("registrar");
    Route::post('/loguear', [AuthController::class, 'loguear'])->name("loguear");
});
Route::middleware([RolAdministrador::class])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});

Route::middleware('auth')->group(function () {
Route::get('/welcome', [AuthController::class, 'welcome'])->name("welcome");
Route::get('/logout', [AuthController::class, 'logout'])->name("logout");

Route::get('/servicios/index', [App\Http\Controllers\ServiciosController::class, 'index'])->name('servicios.index');
Route::post('/servicios/store', [App\Http\Controllers\ServiciosController::class, 'store'])->name('servicios.store');
Route::get('/servicios/administrar/{id_servicio}', [App\Http\Controllers\ServiciosController::class, 'administrar'])->name('servicios.administrar');
Route::post('/servicios/store_permisos', [App\Http\Controllers\ServiciosController::class, 'store_permisos'])->name('servicios.store_permisos');

Route::get('/historial/index', [App\Http\Controllers\HistorialController::class, 'index'])->name('historial.index');
Route::get('/historial/formulario/{id_servicio}', [App\Http\Controllers\HistorialController::class, 'formulario'])->name('historial.formulario');
Route::get('/historial/show/{id_servicio}', [App\Http\Controllers\HistorialController::class, 'show'])->name('historial.show');
Route::get('/historial/edit/{id_historial}', [App\Http\Controllers\HistorialController::class, 'edit'])->name('historial.edit');
Route::post('/historial/store', [App\Http\Controllers\HistorialController::class, 'store'])->name('historial.store');

Route::put('/historial/update/{id_historial}', [App\Http\Controllers\HistorialController::class, 'update'])->name('historial.update');


Route::get('/servicios/acceso-areas', function () {
    return view('servicios.acceso_areas');
})->name('servicios.acceso_areas');
//Route::post('/historial/guardar', [HistorialController::class, 'store'])->name('historial.store');
//Route::post('/historial', [HistorialController::class, 'store'])->name('historial.store');

Route::get('/historial/secciones/{id_servicio}', [HistorialController::class, 'editSecciones'])->name('historial.secciones.edit');
Route::post('/historial/secciones/{id_servicio}', [HistorialController::class, 'updateSecciones'])->name('historial.secciones.update');
Route::resource('usuarios', UsuarioController::class);

Route::get('/generate-pdf/{id_historial}', [PdfController::class, 'generatePdf'])->name('pdf.generatePdf');
Route::get('/generate_pdf/{id_historial}', [PdfController::class, 'generateSOAP'])->name('pdf.generateSOAP');
Route::get('/generate_soap/{id_evolucion}', [PdfController::class, 'generateSOAP_internos'])->name('pdf.generateSOAP_internos');

Route::get('/pdf/', [PdfController::class, 'pdf'])->name('pdf.pdf');

Route::get('/evolucion/edit/{id_historial}', [App\Http\Controllers\EvolucionController::class, 'edit'])->name('evolucion.edit');
Route::get('/evolucion/edit_evolucion/{id_evolucion}', [App\Http\Controllers\EvolucionController::class, 'edit_evolucion'])->name('evolucion.edit_evolucion');
Route::put('/evolucion/update/{id_evolucion}', [App\Http\Controllers\EvolucionController::class, 'update'])->name('evolucion.update');

Route::put('/diagnostico/update/{id_diagnostico}', [App\Http\Controllers\DiagnosticoSoapController::class, 'update'])->name('diagnostico.update');

Route::get('/evolucion/index', [App\Http\Controllers\EvolucionController::class, 'index'])->name('evolucion.index');
Route::get('/evolucion/formulario_soap', [App\Http\Controllers\EvolucionController::class, 'formulario_soap'])->name('evolucion.formulario_soap');
Route::post('/evolucion/store_temp_internos', [App\Http\Controllers\EvolucionController::class, 'store_temp_internos'])->name('evolucion.store_temp_internos');
Route::delete('/evolucion/delete_internos/{id_temporal}', [App\Http\Controllers\EvolucionController::class, 'delete_internos'])->name('evolucion.delete_internos');
Route::post('/evolucion/store_internos', [App\Http\Controllers\EvolucionController::class, 'store_internos'])->name('evolucion.store_internos');
Route::get('/evolucion/edit_evolucion_interno/{id_evolucion}', [App\Http\Controllers\EvolucionController::class, 'edit_interno'])->name('evolucion.edit_interno');

});
