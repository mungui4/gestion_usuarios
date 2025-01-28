<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


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
                'phone' => 'required|string|max:15',
                'password' => 'required|min:8'
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

    public function logout(Request $request) //Cierra sesión eliminando los tokens de acceso
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

    public function index(){ //Muestra todos los usuarios registrados
        
        // Verificar si el usuario tiene permisos para realizar esta acción
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 401);
        }
        
        $users = User::all();
        return response()->json([
            'message' => 'Usuarios registrados',
            'data' => $users
        ], 200);
    }

    public function allNormalUsers(){ //Muestra todos los usuarios con rol "user"
        
        $users = User::where('role', 'user')->get();
        return response()->json([
            'message' => 'Usuarios registrados',
            'data' => $users
        ], 200);
    }

    public function profile() //Muestra la información del usuario
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
            $request->validate([ //Valida los campos recibidos
                'name' => 'required|string',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'password' => 'required|min:8'
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

    public function destroy($id) //Elimina un usuario
    {
        // Verificar si el usuario tiene permisos para realizar esta acción
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 401);
        }

        $perfil = User::findOrfail($id);
            // Verificar si el usuario a eliminar es un administrador
        if ($perfil->role == 'admin') {
            return response()->json([
                'message' => 'No puedes eliminar a otro administrador'
            ], 403);
        }

        $perfil->delete();
        return response()->json([
            'message' => 'Usuario eliminado'
        ], 200);
    }

    //función que retorna los usuarios registrados en un dia específico
    public function usersByDate(Request $request)
    {
        // Verificar si el usuario tiene permisos para realizar esta acción
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 401);
        }

        $date = $request->query('date');

        //Validar formato de fecha
        if(!Carbon::hasFormat($date, 'Y-m-d')){
            return response()->json([
                'message' => 'Formato de fecha incorrecto'
            ], 400);
        }
        $carbonDate = Carbon::createFromFormat('Y-m-d', $date);

        $users = User::whereDate('created_at', $carbonDate)->get();
        return response()->json([
            'message' => 'Usuarios registrados en la fecha: ' . $date,
            'data' => $users
        ], 200);
    }

    public function usersByDates(Request $request)
    {
        // Verificar si el usuario tiene permisos para realizar esta acción
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 401);
        }

        $date1 = $request->query('date1');
        $date2 = $request->query('date2');

        //Validar formato de fecha
        if(!Carbon::hasFormat($date1, 'Y-m-d') || !Carbon::hasFormat($date2, 'Y-m-d')){
            return response()->json([
                'message' => 'Formato de fecha incorrecto'
            ], 400);
        }
        $carbonDate1 = Carbon::createFromFormat('Y-m-d', $date1);
        $carbonDate2 = Carbon::createFromFormat('Y-m-d', $date2);

        $users = User::whereBetween('created_at', [$carbonDate1, $carbonDate2])->get();
        return response()->json([
            'message' => 'Usuarios registrados entre las fechas: ' . $date1 . ' y ' . $date2,
            'data' => $users
        ], 200);
    }

}
