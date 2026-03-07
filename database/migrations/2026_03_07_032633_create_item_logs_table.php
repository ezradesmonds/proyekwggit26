<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_logs', function (Blueprint $table) {
            $table->id();
            // foreignId nullable -> supaya kalau barang dihapus, history-nya tetap ada
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete(); 
            $table->string('item_name'); // Simpan nama barang sebagai snapshot
            $table->string('action'); // Contoh: "Update Stok", "Barang Dihapus"
            $table->text('notes')->nullable(); // Alasan/catatan dari user
            $table->timestamps(); // Otomatis bikin created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_logs');
    }
};