<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PermissionController;
use App\Http\Middleware\Kernel;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//usuario
Route::group([
    'middleware' => ['cors','api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('show/{id}', [AuthController::class,'show']);
    Route::post('registerUser', [AuthController::class, 'registerUser']);
    Route::get('refresh', [AuthController::class, 'refresh'])->name('user.refresh');
    Route::get('me', [AuthController::class, 'me'])->name('user.me');
    Route::post('register', [AuthController::class, 'register'])->name('user.register');
    Route::get('index', [AuthController::class, 'index'])->name('user.index');
    Route::put('update/{id}', [AuthController::class,'update'])->name('user.update');
    Route::delete('destroy/{id}', [AuthController::class,'destroy'])->name('user.destroy');
    
});

// producto
Route::group([
    'middleware' => ['cors','api'],
    'prefix' => 'articulo'
], function ($router) {
    Route::get('show/{id}', [ArticuloController::class,'show']);
    Route::get('index', [ArticuloController::class,'index'])->name('articulo.index'); 
    Route::post('store', [ArticuloController::class,'store'])->name('articulo.store'); 
    Route::put('update/{id}', [ArticuloController::class,'update'])->name('articulo.update'); 
    Route::delete('destroy/{id}', [ArticuloController::class,'destroy'])->name('articulo.destroy');
    
});

//factura y pago producto
Route::group([
    'middleware' => ['cors','api'],
    'prefix' => 'venta'
], function ($router) {
    Route::post('sale/{id}', [VentaController::class,'sale'])->name('venta.sale');
    Route::post('pago',[VentaController::class,'pago'])->name('venta.pago');
});
//agregar producto para la compra
Route::group([
    'middleware' => ['cors','api'],
    'prefix' => 'compra'
], function ($router) {
    Route::post('add/{id}', [CompraController::class,'add'])->name('compra.add');
});

//role
Route::group([
    'middleware' => ['cors','api'],
    'prefix' => 'rol'
], function ($router) {
    Route::get('show/{id}', [RoleController::class,'show']);
    Route::get('index', [RoleController::class,'index'])->name('rol.index'); 
    Route::post('store', [RoleController::class,'store'])->name('rol.store'); 
    Route::put('update/{id}', [RoleController::class,'update'])->name('rol.update'); 
    Route::delete('destroy/{id}', [RoleController::class,'destroy'])->name('rol.destroy'); 
});
//Permission
Route::group([
    'middleware' => ['cors','api'],
    'prefix' => 'permission'
], function ($router) {
    Route::get('index', [PermissionController::class,'index'])->name('permission.index'); 
    Route::post('UserCreateVendedor', [PermissionController::class,'UserCreateVendedor'])->name('permission.UserCreateVendedor'); 
    Route::post('UserCreateCliente', [PermissionController::class,'UserCreateCliente'])->name('permission.UserCreateCliente'); 
});


