
<?php
// Proyecto solo API: solo se define la ruta 'login' para evitar errores de Laravel.
use Illuminate\Support\Facades\Route;

Route::get('login', function () {
	return response()->json([
		'error' => 'Login view not available. Use API authentication.'
	], 401);
})->name('login');
