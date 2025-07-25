<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reseller_codes', function (Blueprint $table) {
            $table->id();
            $table->string('wa_number'); // Nomor WhatsApp customer
            $table->string('code', 20)->unique(); // Kode unik reseller
            $table->boolean('is_used')->default(false); // Status apakah sudah digunakan
            $table->timestamp('expires_at'); // Waktu kadaluarsa
            $table->timestamp('used_at')->nullable(); // Waktu digunakan
            $table->unsignedBigInteger('used_for_order_id')->nullable(); // ID pesanan yang menggunakan kode ini
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['wa_number', 'is_used']);
            $table->index(['code', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_codes');
    }
};
