<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\User;
use Auth;

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

    public function login(Request $request)
    {
        $this->validate($request, [
            'usuario' => 'required',
            'password' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('usuario', 'password'))) {
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
        $response['user'] = Auth::user();
        return response()->json($response);
    }
    public function register(Request $request){
        if ($request->has('usuario') && $request->has('password') && $request->has('dni') && $request->has('nombre')&& $request->has('apellido') && $request->has('legajo')) {
          $user = new User;
          $user->usuario=$request->input('usuario');
          $user->dni=$request->input('dni');
          $user->nombre=$request->input('nombre');
          $user->email=$request->input('email');
          $user->apellido=$request->input('apellido');
          $user->telefono=$request->input('telefono');
          $user->legajo=$request->input('legajo');
          $user->password=Hash::make($request->input('password'));
  
          if($user->save()){
            return "Nuevo usuario registrado!";
          } else {
            return "Error al registrar usuario.";
          }
        } else {
          return "Falta información para registrar a un nuevo usuario!";
        }
      }
      public function test(){
          return Auth::user();
      }
}