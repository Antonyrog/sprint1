<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Articulo;
use App\Models\Compra;


class VentaController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('can:venta.sale')->only('sale');
        $this->middleware('can:venta.pago')->only('pago');
    }

    //especie de factura temporal
    public function sale()
    {
        $id = Auth::id();
        $ventas = Venta::create([
            'id_user'=> $id,
            'valor_total'=> '0',
        ]);

        //return response()->json(compact('ventas'));
    }

    //Venta definitiva
    public function pago()
    {
        $id = Auth::id();
        $ventas = DB::select('select max(id) AS id from ventas where id_user = :id limit 1',['id' => $id]);

        foreach ($ventas as $venta) {
            $ventaid = $venta->id;
        }
        $compras = DB::select('select id_articulo, cantidad from compras where id_venta = :id', ['id' => $ventaid]);

        foreach ($compras as $compra ) {
            $articulo = Articulo::find($compra->id_articulo);
            $articulo->stock = $articulo->stock - $compra->cantidad;
            $articulo->save();
        }
        return response()->json(["message" => "Gracias por su compra, Hasta la proxima!"]);
    }
 
}
