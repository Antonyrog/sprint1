<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Articulo;
use App\Models\Compra;

class CompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:compra.add')->only('add');
        
    }

    //agrega la cantidad de productos para comprarlos 
    public function add($articuloid, Request $request){
        $id = Auth::id();
        $ventas = DB::select('select max(id) AS id from ventas where id_user = :id limit 1', ['id' => $id]);

        if(!Articulo::find($articuloid)){
            return response()->json(["message"=>"Producto no existe"],400);
        }
        $valorInit = Articulo::find($articuloid)->precio;
    
        
        if($request->get('stock') > Articulo::find($articuloid)->stock){
            return response()->json(["message"=>" No existe, disponible en el inventario para ". Articulo::find($articuloid)->stock ." articulos"],400);
        }
        $valor = $valorInit * $request->get('stock');

        foreach ($ventas as $venta) {
            $ventaid = $venta->id;
        }
        $compras= Compra::create([
            'id_venta' => $ventaid,
            'id_articulo' => $articuloid,
            'cantidad' => $request->get('stock'),
            'valor' => $valor,
        ]);
       // var_dump($ventaid, $valorInit, $ventas, $id, $articuloid);
        
        return response()->json(compact('compras'));
    }
}
