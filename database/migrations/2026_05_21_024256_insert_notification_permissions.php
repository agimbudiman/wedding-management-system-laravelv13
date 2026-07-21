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
        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => 'notification-financial', 'display_name' => 'Receive Financial Notifications', 'module' => 'Notifications', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'notification-event', 'display_name' => 'Receive Event Notifications', 'module' => 'Notifications', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('permissions')->whereIn('name', [
            'notification-financial',
            'notification-event'
        ])->delete();
    }
};
