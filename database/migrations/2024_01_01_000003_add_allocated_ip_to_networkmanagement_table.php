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
        Schema::table('networkmanagement', function (Blueprint $table) {
            $table->unsignedBigInteger('allocated_ip_id')->nullable()->after('system_type');
            $table->foreign('allocated_ip_id')->references('id')->on('ips')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('networkmanagement', function (Blueprint $table) {
            $table->dropForeign(['allocated_ip_id']);
            $table->dropColumn('allocated_ip_id');
        });
    }
};

