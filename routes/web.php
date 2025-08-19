<?php

use App\Http\Controllers\BackupDownloadController;
use App\Http\Controllers\FrontBookingController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    return redirect('/admin');

    return view('welcome');
});
// Route::get('/landing', function () {

//     return view('welcome');
// });
Route::get('/landing', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/booking', [FrontBookingController::class, 'index'])->name('booking');


Route::middleware('auth')->get('/admin/backups/download/{filepath}', [BackupDownloadController::class, 'download'])
    ->where('filepath', '.*')
    ->name('filament.backups.download');
