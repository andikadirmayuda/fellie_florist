<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Customer Information
            $table->string('name', 100);
            $table->string('email')->nullable()->unique();
            $table->string('phone', 20)->unique();
            $table->enum('type', ['walk-in', 'reseller', 'regular'])->default('walk-in');
            
            // Address Information
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur trash/restore
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
