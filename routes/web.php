<?php

use App\Models\User;
use Inertia\Inertia;
use App\Models\Formateur;
use App\Events\MessageSend;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Formateur\NoteController;
use App\Http\Controllers\Admin\ApprenantController;
use App\Http\Controllers\Admin\FormateurController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\DisciplineController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Apprenant\NotificationController;
use App\Http\Controllers\Admin\ManageAssociationController;
use App\Http\Controllers\Apprenant\ApprenantDashboardController;
use App\Http\Controllers\Formateur\FormateurDashboardController;

Route::get('chat/messages', [ChatController::class, 'getMessages'])->middleware('auth')->name('getMessages');

Route::post('/chat/send', [ChatController::class, 'sendMessage'])->middleware('auth');

Route::get('chat/{friend?}',[ChatController::class, 'chat'])->middleware('auth')->name('chat');
Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome')->middleware('guest');
Route::middleware(['auth', 'userRole:formateur'])->group(function () {

    
    Route::get('formateur/dashboard', [FormateurDashboardController::class, 'dashboard'])->name('formateur.dashboard');
    
    Route::get('mes-formations', [App\Http\Controllers\Formateur\FormateurController::class, 'mes_formations'])->name('formateur.formations');

    Route::get('mes-apprenants/{formation?}', [App\Http\Controllers\Formateur\FormateurController::class, 'showApprenants'])->name('formateur.apprenants');
    
    Route::get('/mes-disciplines', [App\Http\Controllers\Formateur\FormateurController::class, 'showDisciplines'])->name('formateur.disciplines');


    /******* note */
    // Route::get('/notes/add/{formationId}/{disciplineId}', [NoteController::class, 'showAddNoteForm'])->name('notes.add');

    Route::get('/formateur/notes/{formation?}', [NoteController::class, 'showNotes'])
        ->name('formateur.notes')
        ->middleware('auth');

    Route::post('/formateur/notes/update', [NoteController::class, 'updateNote'])
        ->name('formateur.updateNote')
        ->middleware('auth');
});


Route::middleware(['auth', 'userRole:apprenant'])->group(function () {
    Route::get('apprenant/dashboard', [ApprenantDashboardController::class, 'dashboard'])->name('apprenant.dashboard');

    // Route::get('/apprenant/formations', [ApprenantsController::class, 'formations'])->name('apprenant.formations');

    Route::get('/apprenant/notes', [App\Http\Controllers\Apprenant\NoteController::class, 'notes'])
        ->name('apprenant.notes');

    Route::get('/apprenant/progression', [App\Http\Controllers\Apprenant\ApprenantController::class, 'progression'])->name('apprenant.progression');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/apprenant/carnet', [App\Http\Controllers\Apprenant\ApprenantController::class, 'carnet'])->name('apprenant.carnet');

    Route::get('/apprenant/disciplines', [App\Http\Controllers\Apprenant\DisciplineController::class, 'showDisciplines'])->name('apprenant.disciplines');
});


Route::middleware(['auth', 'userRole:admin'])->prefix('bob')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::resource('formateurs', FormateurController::class);

    Route::resource('formations', FormationController::class);

    Route::resource('disciplines', DisciplineController::class);

    Route::resource('apprenants', ApprenantController::class);
    

    /**formateur -> formation-> discipline */
    Route::get('/formateurs/{formateur}/associations', [ManageAssociationController::class, 'manageFormateurFormationDisciplineAssociations'])->name('formateurs.manageAssociations');

    Route::post('/formateurs/{formateur}/associations', [ManageAssociationController::class, 'updateFormateurFormationDisciplineAssociations'])->name('formateurs.updateFormateurFormationDisciplineAssociations');


    /**formation-> discipline */

    Route::get('/formations/{formation}/discipline-associations', [ManageAssociationController::class, 'manageFormationDisciplineAssociations'])->name('formations.manageDisciplineAssociations');
    Route::post('/formations/{formation}/discipline-associations', [ManageAssociationController::class, 'updateFormationDisciplineAssociations'])->name('formations.updateDisciplineAssociations');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
