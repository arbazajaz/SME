<?php

use App\Livewire\ClientManager;
use App\Livewire\InvoiceManager;
use App\Livewire\ServiceManager;
use App\Livewire\EmployeeManager;
use App\Livewire\Settings\Profile;
use App\Livewire\InvoiceRowManager;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\ServicesProvidedManager;
use App\Livewire\ClientAppointmentManager;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('/employees', EmployeeManager::class)->name('employees.index');
    Route::get('/clients', ClientManager::class)->name('clients.index');
    Route::get('/services', ServiceManager::class)->name('services.index');
    Route::get('/invoices', InvoiceManager::class)->name('invoices.index');
    Route::get('/client-appointments', ClientAppointmentManager::class)->name('client-appointments.index');
    Route::get('/services-provided', ServicesProvidedManager::class)->name('services-provided.index');
    Route::get('/invoice-rows', InvoiceRowManager::class)->name('invoice-rows.index');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
