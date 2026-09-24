<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('input_aspirasis', function (Blueprint $table) {
        $table->id('id_pelaporan'); 
        
        $table->integer('nis');
        $table->foreign('nis')->references('nis')->on('siswas');
        
       
        $table->integer('id_kategori'); 
        $table->foreign('id_kategori')->references('id_kategori')->on('kategoris');
        
        
        $table->string('lokasi', 50);
        $table->string('ket', 50);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_aspirasis');
    }
};
