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
        Schema::create('networkmanagement', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT PRIMARY KEY
            $table->string('property_name', 255);
            $table->string('oem', 100)->nullable();
            $table->text('property_address')->nullable();
            $table->integer('remote_unit_quantity')->nullable();
            $table->integer('master_unit_quantity')->nullable();
            $table->integer('bda_quantity')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('property_type', 50)->nullable(); // Changed to string to match form options
            $table->string('average_density', 50)->nullable(); // Changed to string to match form options
            $table->string('system_type', 50)->nullable(); // Changed to string to match form options
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networkmanagement');
    }
};

