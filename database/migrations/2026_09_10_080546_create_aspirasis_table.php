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
    Schema::create('aspirasis', function (Blueprint $table) {
        $table->id('id_aspirasi');
        $table->foreignId('id_pelaporan')->references('id_pelaporan')->on('input_aspirasis');
        $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu'); 
        $table->foreignId('id_kategori')->references('id_kategori')->on('kategoris');
        $table->text('feedback');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
