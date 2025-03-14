<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FormateurController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\DisciplineController;

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
    Route::get('formateur/dashboard', function () {
        return Inertia::render('Formateurs.Dashboard');
    })->name('formateur.dashboard');
});


Route::middleware(['auth', 'userRole:student'])->group(function () {
    Route::get('student/dashboard', function () {
        return Inertia::render('Students.Dashboard');
    })->name('student.dashboard');
});
Route::middleware(['auth', 'userRole:admin'])->group(function () {
    Route::get('bob/dashboard', function () {
        return Inertia::render('Amdin.Dashboard');
    })->name('admin.dashboard');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
