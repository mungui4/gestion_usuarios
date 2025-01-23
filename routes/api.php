<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [UserController::class, 'register']); //Resgitro usuarios con rol "user"
Route::post('login', [UserController::class, 'login']); //Inicia sesion usuarios "user" y "admin"

Route::middleware('auth:sanctum')->group(function () {
    Route::get('perfil', [UserController::class, 'index']); //Muestra los datos de usuario que ha iniciado sesión
    Route::get('logout', [UserController::class, 'logout']);//Cierra sesión
    Route::put('update/{id}', [UserController::class, 'update']);//Actualiza información del usuario

    Route::post('refresh-token', function (Request $request) { //Genera y retorna un token actualizado
        $user = $request->user();
        $user->tokens()->delete();
        $newToken = $user->createToken('auth_token');
        return response()->json([
            'token' => $newToken->plainTextToken,
        ]);
    });
});
