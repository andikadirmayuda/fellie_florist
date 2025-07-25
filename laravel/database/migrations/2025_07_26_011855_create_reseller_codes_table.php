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
            $table->string('wa_number', 20)->index(); // Nomor WhatsApp customer
            $table->string('code', 20)->unique(); // Kode reseller unik
            $table->boolean('is_used')->default(false); // Apakah sudah digunakan
            $table->timestamp('expires_at'); // Kapan kode expired
            $table->timestamp('used_at')->nullable(); // Kapan digunakan
            $table->unsignedBigInteger('used_for_order_id')->nullable(); // ID order yang menggunakan kode ini
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
