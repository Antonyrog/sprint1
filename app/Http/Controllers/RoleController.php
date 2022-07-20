<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
//ecepciones de error en jwt
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTExceptions;

class RoleController extends Controller
{   
    public function __construct()
    {   
        $this->middleware('can:rol.index')->only('index');
        $this->middleware('can:rol.store')->only('store');     
        $this->middleware('can:rol.update')->only('update');  
        $this->middleware('can:rol.destroy')->only('destroy');         
    }
    
    public function index()
    {
        $roles = Role::all(); 
        return $roles;
    }
    
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        
        if($validator->fails()){
            return response()->json([
                $validator->errors()->toJson(),
                'status'  => 0,
                'code' => 409
            ]);
        }else {
            $uso = $request->name;
            $role = Role::Create([
                'name' => $uso
            ]);
            return response()->json([
                'status'  => 1,
                'message' => 'Rol registrado exitosamente!',
                'Rol' => $role
            ], 201);
        }
        
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        
        if($validator->fails()){
            return response()->json([
                $validator->errors()->toJson(),
                'status'  => 0,
                'code' => 409
            ]);
        }else {
            $role = Role::findOrFail($request->id);
                $role->name = $request->name;
                $role->save();
            return response()->json([
                'status'  => 1,
                'message' => 'Rol actualizado exitosamente!',
                'Rol' => $role
            ], 201);
        }
    }

    
    public function destroy(Request $request, $id)
    {
        $role = Role::destroy($request->id);
        return response()->json([
            'status'  => 1,
            'message' => 'Rol eliminado exitosamente!'
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $role=Role::find($request->id);
        return response()->json([
            'user' => $role
        ], 201);
    }
}
