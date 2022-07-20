<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'id_venta',
        'id_articulo',
        'cantidad',
        'valor'
    ];
}
