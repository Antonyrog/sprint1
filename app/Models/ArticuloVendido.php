<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloVendido extends Model
{
    use HasFactory;
    
    protected $table = "articulos_vendidos";
    protected $fillable = 
    [
        "id_venta", "descripcion", "codigo_barras", "precio", "cantidad"
    ];
    
}
