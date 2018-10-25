<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Hseq;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    //Listar todos los usuarios
    public function index()
    {
        $usuarios = User::with('hseq', 'area')->has('hseq', 'area')->get();
        return response()->json($usuarios);
    }

    //Iniciar sesión y capturar token
    public function login(Request $request)
    {
        $this->validate($request, [
            'usuario' => 'required',
            'password' => 'required',
        ]);

        try {

            if (!$token = $this->jwt->attempt($request->only('usuario', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        $response = compact('token');
        $id = Auth::user()->idpersona;
        $user = Hseq::with('user')->where('idpersona', $id)->get();
        if (count($user) == 0) {
            $user = Auth::user();
        }
        $response['usuario'] = Auth::user();
        return response()->json($response);
    }

    //Registrar usuario nuevo
    public function register(Request $request)
    {
        if ($request->has('usuario') && $request->has('password') && $request->has('dni') && $request->has('nombre') && $request->has('apellido') && $request->has('legajo')) {

            $user = new User;
            $user->usuario = $request->input('usuario');
            $user->dni = $request->input('dni');
            $user->nombre = $request->input('nombre');
            $user->email = $request->input('email');
            $user->apellido = $request->input('apellido');
            $user->telefono = $request->input('telefono');
            $user->legajo = $request->input('legajo');
            $user->rol = $request->input('rol');
            $user->password = Hash::make($request->input('password'));

            if (User::where('dni', '=', $request->input('dni'))->exists()) {
                return response()->json("¡El DNI ingresado ya existe!", 409);
                
            } else if (User::where('usuario', '=', $request->input('usuario'))->exists()) {
                return response()->json("¡El nombre de usuario ingresado ya existe!", 409);

            } else if (User::where('legajo', '=', $request->input('legajo'))->exists()) {
                return response()->json("¡El legajo ingresado ya existe!", 409);

            } else if (User::where('email', '=', $request->input('email'))->exists()) {
                return response()->json("¡El email ingresado ya existe!", 409);

            } else if (User::where('telefono', '=', $request->input('telefono'))->exists()) {
                return response()->json("¡El teléfono ingresado ya existe!", 409);

            } else {
                if ($user->save()) {
                    return response()->json($user->idpersona, 201);
                } else {
                    return response()->json("Error al registrar usuario.", 400);
                }
            }
        } else {
            return response()->json("Falta información para registrar a un nuevo usuario!", 422);
        }
    }

    //Mostrar un usuario por id
    public function show($id)
    {
        $usuario = User::with('hseq', 'area')->has('hseq', 'area')->find($id);
        return response()->json($usuario);
    }

    //Funcion test
    public function test()
    {
        $usuario = Auth::user();
        $usuario->hseq;
        $usuario->area;
        return $usuario;
    }

}
