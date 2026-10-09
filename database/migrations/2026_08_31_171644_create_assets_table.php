<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();          // Kode aset
            $table->string('name');                     // Nama aset
            $table->string('category')->nullable();     // Kategori
            $table->string('unit')->default('unit');    // Satuan
            $table->boolean('has_serial_number')->default(false);
            $table->unsignedInteger('qty')->default(0); // Stok saat ini (auto)
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
