<?php

use Inertia\Inertia;
use App\Models\Formateur;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FormateurController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\DisciplineController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\ApprenantDashboardController;
use App\Http\Controllers\Formateur\FormateurDashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('MonDashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/students',[StudentController::class, "index"])->name('student.index');
Route::get('/student/create',[StudentController::class, "create"])->name('student.create');
Route::post('/student/store',[StudentController::class, "store"])->name('student.store');
Route::delete('/student/{student}',[StudentController::class, "destroy"])->name('student.destroy');
Route::get('/student/{student}',[StudentController::class, "edit"])->name('student.edit');
Route::put('/student/{student}',[StudentController::class, "update"])->name('student.update');


Route::resource('formateurs', FormateurController::class);


Route::resource('formations', FormationController::class);

Route::resource('disciplines', DisciplineController::class);


Route::middleware(['auth', 'formateur'])->group(function () {
    Route::get('/formateur/mes-formations', [FormateurController::class, 'index'])->name('formateur.formations');
    Route::get('/formateur/formation/{id}/apprenants', [FormateurController::class, 'showApprenants'])->name('formateur.apprenants');
    Route::post('/formateur/attribution-notes', [FormateurController::class, 'attributionNotes'])->name('formateur.attribution.notes');
});



Route::middleware(['auth', 'userRole:formateur'])->group(function () {
    Route::get('formateur/dashboard', [FormateurDashboardController::class, 'dashboard'])->name('formateur.dashboard');
    
    Route::get('mes-formations', [App\Http\Controllers\Formateur\FormateurController::class, 'mes_formations'])->name('formateur.formations');
});


Route::middleware(['auth', 'userRole:apprenant'])->group(function () {
    Route::get('apprenant/dashboard', [ApprenantDashboardController::class, 'dashboard'])->name('apprenant.dashboard');
});
Route::middleware(['auth', 'userRole:admin'])->group(function () {
    Route::get('/bob/dashboard',[AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
