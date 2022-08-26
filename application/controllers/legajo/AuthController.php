<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|string|min:6',
        ]);

        $credentials = ['ccredusuario' => $request->username, 'password' => $request->password];

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $value = $this->verificarAcceso($request->username);
       
        if ($value == 0) {
            return response()->json(['error' => 'El usuario no existe en nuestros registros.'], 403);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Verifica tu usuario y contraseña'], 401);
        }
        return $this->createNewToken($token);
    }

    public function verificarAcceso($ccredusuario)
    {   
        //poner prefijo, en este caso es ficha ...
        $credencial =   DB::table('ficha.credenciales')
            ->where([['ccredusuario','=',$ccredusuario],['iestado','=', 1],['bhabilitado','=', 1]])
            ->get();

        $access = count($credencial);

        return $access;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'El usuario se registró correctamente',
            'user' => $user
        ], 201);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'El usuario cerró sesión correctamente']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()

        ]);
    }
}
