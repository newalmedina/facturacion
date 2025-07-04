<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/test-session', function () {
    session(['foo' => 'bar']);
    return response()->json([
        'stored' => session('foo'),
        'session_id' => session()->getId(),
    ]);
});
