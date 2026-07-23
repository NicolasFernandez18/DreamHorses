<?php

use App\Http\Controllers\HorseController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\VetVisitController;
use App\Http\Controllers\HorsePhotoController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CaretakerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlacksmithController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleManagementController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    /* Training */
    Route::get('/training', [TrainingController::class, 'index'])->name('training.index')->middleware('permission:training.view');
    Route::get('/training/create', [TrainingController::class, 'create'])->name('training.create')->middleware('permission:training.create');
    Route::post('/training', [TrainingController::class, 'store'])->name('training.store')->middleware('permission:training.create');
    Route::get('/training/{training}/edit', [TrainingController::class, 'edit'])->name('training.edit')->middleware('permission:training.edit');
    Route::put('/training/{training}', [TrainingController::class, 'update'])->name('training.update')->middleware('permission:training.edit');
    Route::delete('/training/{training}', [TrainingController::class, 'destroy'])->name('training.destroy')->middleware('permission:training.delete');

    /* Vet Visits */
    Route::get('/vet-visits', [VetVisitController::class, 'index'])->name('vet-visits.index')->middleware('permission:vet-visits.view');
    Route::get('/vet-visits/create', [VetVisitController::class, 'create'])->name('vet-visits.create')->middleware('permission:vet-visits.create');
    Route::post('/vet-visits', [VetVisitController::class, 'store'])->name('vet-visits.store')->middleware('permission:vet-visits.create');
    Route::get('/vet-visits/{vetVisit}/edit', [VetVisitController::class, 'edit'])->name('vet-visits.edit')->middleware('permission:vet-visits.edit');
    Route::put('/vet-visits/{vetVisit}', [VetVisitController::class, 'update'])->name('vet-visits.update')->middleware('permission:vet-visits.edit');
    Route::delete('/vet-visits/{vetVisit}', [VetVisitController::class, 'destroy'])->name('vet-visits.destroy')->middleware('permission:vet-visits.delete');

    /* Horse */
    Route::get('CreateHorse', [HorseController::class, 'create'])->name('CreateHorse')->middleware('permission:horses.create');
    Route::post('StoreHorse', [HorseController::class, 'store'])->name('StoreHorse')->middleware('permission:horses.create');
    Route::get('Horseindex', [HorseController::class, 'index'])->name('Horseindex')->middleware('permission:horses.view');
    Route::get('horses/{horse}', [HorseController::class, 'show'])->name('horses.show')->middleware('permission:horses.view');
    Route::get('horses/{horse}/edit', [HorseController::class, 'edit'])->name('horses.edit')->middleware('permission:horses.edit');
    Route::put('horses/{horse}', [HorseController::class, 'update'])->name('horses.update')->middleware('permission:horses.edit');
    Route::delete('horses/{horse}', [HorseController::class, 'destroy'])->name('horses.destroy')->middleware('permission:horses.delete');
    Route::get('horses/{horse}/pdf', [HorseController::class, 'downloadPDF'])->name('horses.pdf')->middleware('permission:horses.pdf');
    Route::delete('/photos/{photo}', [HorsePhotoController::class, 'destroy'])->name('photos.destroy')->middleware('permission:horses.edit');

    /* Race */
    Route::get('/race', [RaceController::class, 'index'])->name('race.index')->middleware('permission:race.view');
    Route::get('/race/create', [RaceController::class, 'create'])->name('race.create')->middleware('permission:race.create');
    Route::post('/race', [RaceController::class, 'store'])->name('race.store')->middleware('permission:race.create');
    Route::get('/race/{race}/edit', [RaceController::class, 'edit'])->name('race.edit')->middleware('permission:race.edit');
    Route::put('/race/{race}', [RaceController::class, 'update'])->name('race.update')->middleware('permission:race.edit');
    Route::delete('/race/{race}', [RaceController::class, 'destroy'])->name('race.destroy')->middleware('permission:race.delete');

    /* Expense */
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index')->middleware('permission:expenses.view');
    Route::get('/expenses/chart', [ExpenseController::class, 'chart'])->name('expenses.chart')->middleware('permission:expenses.chart');
    Route::get('/expenses/summary', [ExpenseController::class, 'summary'])->name('expenses.summary')->middleware('permission:expenses.summary');
    Route::post('expenses/summary/pdf', [ExpenseController::class, 'downloadSummaryPdf'])->name('expenses.summary.pdf')->middleware('permission:expenses.summary');
    Route::get('/expenses/pdf', [ExpenseController::class, 'downloadPdf'])->name('expenses.pdf')->middleware('permission:expenses.view');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create')->middleware('permission:expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store')->middleware('permission:expenses.create');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show')->middleware('permission:expenses.view');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit')->middleware('permission:expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update')->middleware('permission:expenses.edit');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('permission:expenses.delete');

    /* Caretakers*/
    Route::get('/caretakers', [CaretakerController::class, 'index'])->name('caretakers.index')->middleware('permission:caretakers.view');
    Route::get('/caretakers/{caretaker}', [CaretakerController::class, 'show'])->name('caretakers.show')->middleware('permission:caretakers.show');
    Route::delete('/caretakers/{caretaker}', [CaretakerController::class, 'destroy'])->name('caretakers.destroy')->middleware('permission:caretakers.delete');
    Route::post('/caretakers/{caretaker}/reassign', [CaretakerController::class, 'reassign'])->name('caretakers.reassign')->middleware('permission:caretakers.reassign');

    /* Herreria */
    Route::get('/blacksmiths', [BlacksmithController::class, 'index'])->name('blacksmiths.index')->middleware('permission:blacksmiths.view');
    Route::get('/blacksmiths/create', [BlacksmithController::class, 'create'])->name('blacksmiths.create')->middleware('permission:blacksmiths.create');
    Route::post('/blacksmiths', [BlacksmithController::class, 'store'])->name('blacksmiths.store')->middleware('permission:blacksmiths.create');
    Route::get('/blacksmiths/{blacksmith}/edit', [BlacksmithController::class, 'edit'])->name('blacksmiths.edit')->middleware('permission:blacksmiths.edit');
    Route::put('/blacksmiths/{blacksmith}', [BlacksmithController::class, 'update'])->name('blacksmiths.update')->middleware('permission:blacksmiths.edit');
    Route::delete('/blacksmiths/{blacksmith}', [BlacksmithController::class, 'destroy'])->name('blacksmiths.destroy')->middleware('permission:blacksmiths.delete');

    /* Calendar */
    Route::get('/calendar', [CalendarEventController::class, 'index'])->name('calendar.index')->middleware('permission:calendar.view');
    Route::get('/calendar/create', [CalendarEventController::class, 'create'])->name('calendar.create')->middleware('permission:calendar.create');
    Route::post('/calendar', [CalendarEventController::class, 'store'])->name('calendar.store')->middleware('permission:calendar.create');
    Route::get('/calendar/{calendarEvent}/edit', [CalendarEventController::class, 'edit'])->name('calendar.edit')->middleware('permission:calendar.edit');
    Route::put('/calendar/{calendarEvent}', [CalendarEventController::class, 'update'])->name('calendar.update')->middleware('permission:calendar.edit');
    Route::delete('/calendar/{calendarEvent}', [CalendarEventController::class, 'destroy'])->name('calendar.destroy')->middleware('permission:calendar.delete');
    Route::get('/calendarhorse', [CalendarEventController::class, 'calendar'])->name('calendarhorse')->middleware('permission:calendar.schedule');

    /* Roles management */
    Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index')->middleware('permission:roles.manage');
    Route::get('/roles/create', [RoleManagementController::class, 'create'])->name('roles.create')->middleware('permission:roles.manage');
    Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store')->middleware('permission:roles.manage');
    Route::get('/roles/{role}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.manage');
    Route::put('/roles/{role}', [RoleManagementController::class, 'update'])->name('roles.update')->middleware('permission:roles.manage');

    /* Studs */
    Route::get('/studs', [StudController::class, 'index'])->name('studs.index')->middleware('permission:studs.view');
    Route::get('/studs/create', [StudController::class, 'create'])->name('studs.create')->middleware('permission:studs.create');
    Route::post('/studs', [StudController::class, 'store'])->name('studs.store')->middleware('permission:studs.create');
    Route::get('/studs/{stud}', [StudController::class, 'show'])->name('studs.show')->middleware('permission:studs.view');
    Route::get('/studs/{stud}/edit', [StudController::class, 'edit'])->name('studs.edit')->middleware('permission:studs.edit');
    Route::put('/studs/{stud}', [StudController::class, 'update'])->name('studs.update')->middleware('permission:studs.edit');
    Route::post('/studs/{stud}/join', [StudController::class, 'join'])->name('studs.join')->middleware('permission:studs.join');
    Route::post('/studs/{stud}/leave', [StudController::class, 'leave'])->name('studs.leave')->middleware('permission:studs.leave');
    Route::post('/studs/{stud}/kick', [StudController::class, 'kick'])->name('studs.kick')->middleware('permission:studs.kick');
    Route::delete('/studs/{stud}', [StudController::class, 'destroy'])->name('studs.destroy')->middleware('permission:studs.delete');
    Route::post('/studs/{stud}/hire', [StudController::class, 'hire'])->name('studs.hire')->middleware('permission:studs.hire');
    Route::post('/studs/{stud}/fire', [StudController::class, 'fire'])->name('studs.fire')->middleware('permission:studs.fire');
    Route::post('/studs/{stud}/hire-respond/{boss}', [StudController::class, 'respondToHireRequest'])->name('studs.hire.respond')->middleware('permission:studs.respond');

    Route::get('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

/* Roles */
Route::get('/select-role', [RegisteredUserController::class, 'create'])
    ->name('select-role');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store']);


require __DIR__ . '/auth.php';
