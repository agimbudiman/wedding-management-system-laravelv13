<?php
/*
 * Created At: 2026-04-20T12:11:31Z
 */

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
            $table->string('groom_name', 50)->nullable()->after('name');
            $table->string('bride_name', 50)->nullable()->after('groom_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['groom_name', 'bride_name']);
        });
    }
};
