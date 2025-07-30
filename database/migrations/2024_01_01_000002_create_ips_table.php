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
        Schema::create('ips', function (Blueprint $table) {
            $table->id();
            $table->string('first_usable_ip', 45); // Support IPv4 and IPv6
            $table->string('last_usable_ip', 45); // Support IPv4 and IPv6
            $table->boolean('in_use')->default(false);
            $table->string('network_range', 100)->nullable(); // Optional: to store the network range (e.g., 192.168.1.0/24)
            $table->text('description')->nullable(); // Optional: description of the IP range
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ips');
    }
};

