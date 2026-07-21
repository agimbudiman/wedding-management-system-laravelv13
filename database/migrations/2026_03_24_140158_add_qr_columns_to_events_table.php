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
        Schema::table('events', function (Blueprint $table) {
            $table->string('client_qr_token', 100)->nullable()->unique()->after('status');
            $table->boolean('is_client_qr_active')->default(false)->after('client_qr_token');
            $table->string('guest_qr_token', 100)->nullable()->unique()->after('is_client_qr_active');
            $table->boolean('is_guest_qr_active')->default(false)->after('guest_qr_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['client_qr_token', 'is_client_qr_active', 'guest_qr_token', 'is_guest_qr_active']);
        });
    }
};
