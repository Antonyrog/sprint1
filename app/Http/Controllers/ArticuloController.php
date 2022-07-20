<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Articulo;
use App\Models\Venta;
use App\Models\User;

class ArticuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('can:articulo.index')->only('index');
        $this->middleware('can:articulo.store')->only('store');
        $this->middleware('can:articulo.update')->only('update');
        $this->middleware('can:articulo.destroy')->only('destroy');
        $this->middleware('can:articulo.compra')->only('compra');
    }

    public function index()
    {
        $articulos = Articulo::all();
        return $articulos;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|unique:articulos',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|numeric', 
        ]);
        
        if($validator->fails()){
            return response()->json([
                $validator->errors()->toJson(),
                'status'  => 0,
                'message' => 'El ID del producto ingresado ya existe!',
                'code' => 409
            ]);
        }else {
            $articulo = new Articulo();
            $articulo->id = $request->id;
            $articulo->descripcion = $request->descripcion;
            $articulo->precio = $request->precio;
            $articulo->stock = $request->stock;
            $articulo->save();

            return response()->json([
                'status'  => 1,
                'message' => 'Producto registrado exitosamente!'
            ], 201);
        }        
    }

    public function update(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|numeric', 
        ]);
        
        if($validator->fails()){
            return response()->json([
                $validator->errors()->toJson(),
                'status'  => 0,
                'message' => 'Verifique los datos ingresados!',
                'code' => 409
            ]);
        }else {
            $articulo = Articulo::findOrFail($request->id);
            $articulo->descripcion = $request->descripcion;
            $articulo->precio = $request->precio;
            $articulo->stock = $request->stock;
            $articulo->save();

        return response()->json([
            'status'  => 1,
            'message' => 'Producto actualizado exitosamente!',
        ], 201);
        }
    }

    
    public function destroy(Request $request)
    {
        $articulo = Articulo::destroy($request->id);
        return response()->json([
            'status'  => 1,
            'message' => 'Producto eliminado exitosamente!'
        ], 201);
    }


    public function show(Request $request, $id)
    {
        $articulo=Articulo::find($request->id);
        return response()->json([
            'status'  => 1,
            'user' => $articulo
        ], 201);
    }
}
