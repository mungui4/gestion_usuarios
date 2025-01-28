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
    Route::get('users', [UserController::class, 'index']); //Muestra todos los usuarios
    Route::get('profile', [UserController::class, 'profile']); //Muestra los datos de usuario que ha iniciado sesión
    Route::get('users/user', [UserController::class, 'allNormalUsers']); //Muestra todos los usuarios con rol "user"
    Route::get('logout', [UserController::class, 'logout']);//Cierra sesión
    Route::put('update/{id}', [UserController::class, 'update']);//Actualiza información del usuario
    Route::delete('delete/{id}', [UserController::class, 'destroy']);//Elimina un usuario
    Route::get('users/by-date', [UserController::class, 'usersByDate']);//Muestra los usuarios registrados en una fecha específica
    Route::get('users/by-dates', [UserController::class, 'usersByDates']);//Muestra los usuarios registrados en un rango de fechas

    Route::post('refresh-token', function (Request $request) { //Genera y retorna un token actualizado
        $user = $request->user();
        $user->tokens()->delete();
        $newToken = $user->createToken('auth_token');
        return response()->json([
            'token' => $newToken->plainTextToken,
        ]);
    });
});
