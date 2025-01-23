<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    //
    public function register(Request $request) //Registra un nuevo usuario en la db
    {
        try {
            $request->validate([ //Valida los campos recibidos
                'name' => 'required|string',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'phone' => 'required|string|max:15'
            ]);

            $user = User::create([ //Guarda los datos en la db
                'name' => $request->name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)

            ]);

            return response()->json([ // Regresa una respuesta con los datos creados
                'message' => 'Usuario creado con éxito',
                'data' => $user
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function login(Request $request) //Permite el log de un usuario registrado en la db
    {
        try {
            $request->validate([ // Valida en formato de las credenciales
                'email' => 'required|string|email',
                'password' => 'required|string|min:8'
            ]);

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                throw new Exception('Credenciales invalidas');
            }
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;


            return response()->json([ //Responde con un Json el token e información del usuario
                'message' => 'Sesión iniciada',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request) //Cierra sesió eliminando los tokens de acceso
    {

        try {

            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Sesión cerrada'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 400);
        };
    }

    public function index() //Muestra la información del usuario
    {
        //
        $usuario = Auth::user();
        return response()->json([
            'id' => $usuario->id,
            'name' => $usuario->name,
            'last_name' => $usuario->last_name,
            'address' => $usuario->address,
            'email' => $usuario->email,
            'phone' => $usuario->phone,
            'role' => $usuario->role
        ], 200);
    }

    public function update(Request $request, string $id) //Actualiza la información de un usuario
    {

        $perfil = User::findOrfail($id);
        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:15'
        ]);

        if ($request->user()->id === $perfil->id) {
            $perfil->update($request->all());

            return response()->json([ //Retorna un Json con los datos actualizados
                'message' => 'Actualizado con éxito',
                'data' => $perfil
            ], 200);
        } else {
            return response()->json([
                'message' => 'No encontrado'
            ], 400);
        }
    }
}
