<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HseqController;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;
use App\Models\Hseq;

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
        $usuarios = User::with('hseq')->get();
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
        $id=Auth::user()->idpersona;
        $user = Hseq::with('user')->where('idpersona', $id)->get();
        if (count($user) == 0){
            $user=Auth::user();
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

            if ($user->save()) {
                return "Nuevo usuario registrado!";
            } else {
                return "Error al registrar usuario.";
            }
        } else {
            return "Falta información para registrar a un nuevo usuario!";
        }
    }

    //Mostrar un usuario por id
    public function show($id)
    {
        $usuario = User::find($id);
        $usuario->hseq;
        return response()->json($usuario);
    }

    //Funcion test
    public function test()
    {
        $usuario=Auth::user();
        $usuario->hseq;
        return $usuario;
    }

}
