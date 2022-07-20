<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('descripcion');
            $table->float('precio');
            $table->integer('stock');
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('articulos');
    }
};
