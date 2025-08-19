<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\EmpresaController;
use App\Http\Controllers\Api\V1\ServicioController;
use App\Http\Controllers\Api\V1\PedidosMController;
use App\Http\Controllers\Api\V1\PedidoProductoController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\MesaController;
use App\Http\Controllers\Api\V1\ClienteController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthController;

use App\Http\Controllers\Api\V1\ImageController;


use Illuminate\Auth\AuthenticationException;


Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});


Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('images')->group(function () {
        Route::post('upload', [ImageController::class, 'upload']);
        Route::post('save', [ImageController::class, 'upload']);
    });

    Route::prefix('products')->group(function () {
        Route::get('select', [ProductController::class, 'index']);
        Route::post('create', [ProductController::class, 'store']);
        Route::get('show/{id}', [ProductController::class, 'show']);
        Route::put('update/{id}', [ProductController::class, 'update']);
        Route::delete('delete/{id}', [ProductController::class, 'destroy']);
    });
    
    Route::prefix('users')->group(function () {
        Route::get('select', [UserController::class, 'index']);
        Route::post('create', [UserController::class, 'store']);
        Route::get('show/{id}', [UserController::class, 'show']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
    });
    Route::prefix('empresas')->group(function () {
        Route::get('select', [EmpresaController::class, 'index']);
        Route::post('create', [EmpresaController::class, 'store']);
        Route::get('show/{id}', [EmpresaController::class, 'show']);
        Route::put('update/{id}', [EmpresaController::class, 'update']);
        Route::delete('delete/{id}', [EmpresaController::class, 'destroy']);
    });
    Route::prefix('servicios')->group(function () {
        Route::get('select', [ServicioController::class, 'index']);
        Route::post('create', [ServicioController::class, 'store']);
        Route::get('show/{id}', [ServicioController::class, 'show']);
        Route::put('update/{id}', [ServicioController::class, 'update']);
        Route::delete('delete/{id}', [ServicioController::class, 'destroy']);
    });
    Route::prefix('pedidos')->group(function () {
        Route::get('select', [PedidosMController::class, 'index']);
        Route::post('create', [PedidosMController::class, 'store']);
        Route::get('show/{id}', [PedidosMController::class, 'show']);
        Route::put('update/{id}', [PedidosMController::class, 'update']);
        Route::delete('delete/{id}', [PedidosMController::class, 'destroy']);
    });
    Route::prefix('pedido-productos')->group(function () {
        Route::get('select', [PedidoProductoController::class, 'index']);
        Route::post('create', [PedidoProductoController::class, 'store']);
        Route::get('show/{id}', [PedidoProductoController::class, 'show']);
        Route::put('update/{id}', [PedidoProductoController::class, 'update']);
        Route::delete('delete/{id}', [PedidoProductoController::class, 'destroy']);
    });
    Route::prefix('mensajes')->group(function () {
        Route::get('select', [MessageController::class, 'index']);
        Route::post('create', [MessageController::class, 'store']);
        Route::get('show/{id}', [MessageController::class, 'show']);
        Route::put('update/{id}', [MessageController::class, 'update']);
        Route::delete('delete/{id}', [MessageController::class, 'destroy']);
    });
    Route::prefix('mesas')->group(function () {
        Route::get('select', [MesaController::class, 'index']);
        Route::post('create', [MesaController::class, 'store']);
        Route::get('show/{id}', [MesaController::class, 'show']);
        Route::put('update/{id}', [MesaController::class, 'update']);
        Route::delete('delete/{id}', [MesaController::class, 'destroy']);
    });
    Route::prefix('clientes')->group(function () {
        Route::get('select', [ClienteController::class, 'index']);
        Route::post('create', [ClienteController::class, 'store']);
        Route::get('show/{id}', [ClienteController::class, 'show']);
        Route::put('update/{id}', [ClienteController::class, 'update']);
        Route::delete('delete/{id}', [ClienteController::class, 'destroy']);
    });
});

