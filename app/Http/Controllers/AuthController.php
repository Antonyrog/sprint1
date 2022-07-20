<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
//ecepciones de error en jwt
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTExceptions;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registerUser']]);
        $this->middleware('can:user.refresh')->only('refresh');
        $this->middleware('can:user.me')->only('me');
        $this->middleware('can:user.register')->only('register');
        $this->middleware('can:user.index')->only('index');
        $this->middleware('can:user.update')->only('update');
        $this->middleware('can:user.destroy')->only('destroy');
    }
    
    public function login(Request $request) 
    {
        $credentials = $request->only('email', 'password');
        try {
            if(!JWTAuth::attempt($credentials)){
                $response['status'] = 0;
                $response['code']= 409;
                $response['data'] = null;
                $response['message'] = 'Correo o contraseña incorrecta!';
                return response()->json($response);
            }
        } catch (JWTException $e) {
            $response['data'] = null;
            $response['code']= 409;
            $response['message'] = 'No se pudo crear token';
        }

        $user = auth()->user();
        $data['token'] = auth()->claims([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'id_rol' => $user->id_rol
        ])->attempt($credentials);

        $response['data'] = $data;
        $response['status']= 1;
        $response['code'] = 200;
        $response['message'] = 'Bienvenido al sistema!';
        return response()->json($response);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout() 
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|between:6,15',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'  => 0,
                'message' => '¡Correo ya existe!',
                'code' => 409
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'id_rol' => '3',
            ])->assignRole('Cliente');
            
            return response()->json([
                'status'  => 1,
                'message' => '¡Usuario registrado exitosamente!',
                'code' => 200
            ]);
        }

        
    }

    public function register(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|between:6,15',
            'id_rol' => 'required|numeric', 
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status'  => 0,
                'message' => '¡Correo ya existe!',
                'code' => 409
            ]);
        }
        else{
            $user = User::create(array_merge(
                $validator->validate(),
                ['password' => bcrypt($request->password)]
            ));
            
            $role = $request->id_rol;
        
            if ($role == "1") {
                $user->assignRole('Administrador');
                
            }else if ($role == "2") {
                $user->assignRole('Vendedor');
             
            }else if($role == "3") {
                $user->assignRole('Cliente');
            }
            
            return response()->json([
                'status'  => 1,
                'message' => '¡Usuario registrado exitosamente!',
                'code' => 201
            ]);
        }
        
    }
    
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'string|email|max:100',
            'password' => 'string|between:6,15',
            'id_rol' => 'numeric', 
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status'  => 0,
                'message' => 'Correo ya existe',
                'code' => 409
            ]);
        }else {
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->id_rol = $request->id_rol;        
            $role = $request->id_rol; 

            if ($role == "1") {
                $user->syncRoles(['Administrador']);
                $user->save();
        
            }else if ($role == "2") {
                $user->syncRoles(['Vendedor']);
                $user->save();
            }else if($role == "3") {
                $user->syncRoles(['Cliente']);
                $user->save();
            }
            return response()->json([
                'status'  => 1,
                'message' => 'Usuario actualizado exitosamente!',
            ], 201); 
        }
    }


    public function destroy(Request $request, $id)
    {
        $user = User::destroy($request->id);
        return response()->json([
            'status' => 1,
            'message' => 'Usuario eliminado exitosamente!',
        ], 201); 
    }

    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function show(Request $request, $id)
    {
        $User=User::find($request->id);
        return response()->json([
            'user' => $User
        ], 201);
    }
}