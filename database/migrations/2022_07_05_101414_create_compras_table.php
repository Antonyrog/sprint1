<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_venta");
            $table->foreign("id_venta")
                ->references("id")
                ->on("ventas")
                ->onDelete("cascade")
                ->onUpdate("cascade");
                //$table->unsignedBigInteger("id_articulo");
                $table->integer("id_articulo")->unsigned();
                $table->foreign("id_articulo")
                    ->references("id")
                    ->on("articulos")
                    ->onDelete("cascade")
                    ->onUpdate("cascade");
            $table->float("valor"); 
            $table->integer("cantidad");
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('compras');
    }
};
