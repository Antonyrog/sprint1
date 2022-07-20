<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PermissionController extends Controller
{
    public function __construct()
    {   
        $this->middleware('can:permission.index')->only('index'); 
        $this->middleware('can:permission.UserCreateVendedor')->only('UserCreateVendedor');   
        $this->middleware('can:permission.UserCreateCliente')->only('UserCreateCliente');      
    }

    public function index()
    {
        $permissions = Permission::all(); 
        return $permissions;
    }

    public function UserCreateVendedor(Request $request)
    {
        $id=Role::findById($request->id);

        $validator = Validator::make($request->all(),[
            'id' => 'required',
            ]);
        if($validator->fails()){
            return response()->json([
                $validator->errors()->toJson(),
                'status'  => 0,
                'message' => 'El ID ingresado no corresponde a ningun Rol',
                'code' => 409
            ]);
        }

        $id->givePermissionTo([
            'user.refresh',
            'user.me',
            'articulo.index',
            'articulo.store',
            'articulo.update',
            'articulo.destroy'
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Permisos de Vendedor asignados!',
            ],201);;
    }

    public function UserCreateCliente(Request $request)
    {
        $id=Role::findById($request->id);

        $validator = Validator::make($request->all(),[
            'id' => 'required',
            ]);
        if($validator->fails()){
            return response()->json([
                $validator->errors()->toJson(),
                'status'  => 0,
                'message' => 'El ID ingresado no corresponde a ningun Rol',
                'code' => 409
            ]);
        }

        $id->givePermissionTo([
            'user.refresh',
            'user.me',
            'articulo.index',
            'venta.sale',
            'venta.pago',
            'compra.add'
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Permisos de Cliente asignados!',
            ],201);;
    }
}
