<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('serial_number');
            $table->enum('status', ['in', 'out'])->default('in'); // in = masih ada, out = sudah keluar
            $table->foreignId('asset_in_id')->nullable()->constrained('asset_ins')->nullOnDelete();
            $table->foreignId('asset_out_id')->nullable()->constrained('asset_outs')->nullOnDelete();
            $table->timestamps();

            $table->unique(['asset_id', 'serial_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_serial_numbers');
    }
};
