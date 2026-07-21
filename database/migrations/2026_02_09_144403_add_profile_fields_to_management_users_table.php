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
        Schema::table('management_users', function (Blueprint $table) {
            $table->string('avatar', 100)->nullable()->after('password');
            $table->date('birth_date')->nullable()->after('avatar');
            $table->string('gender', 15)->nullable()->after('birth_date');
            $table->string('phone_number', 20)->nullable()->after('gender');
            $table->text('address')->nullable()->after('phone_number');
            $table->string('status', 20)->default('Available')->after('address');
            $table->integer('total_events_handled')->default(0)->after('status');
            $table->date('joined_at')->nullable()->after('total_events_handled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('management_users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'birth_date',
                'gender',
                'phone_number',
                'address',
                'status',
                'total_events_handled',
                'joined_at'
            ]);
        });
    }
};
