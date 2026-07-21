<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique(); // admin, crew, etc.
            $table->string('display_name', 50);  // Administrator, Staff Crew, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // manage-event, view-dashboard, etc.
            $table->string('display_name', 50);
            $table->string('module', 30)->nullable(); // Event, Crew, Vendor, etc.
            $table->timestamps();
        });

        // 3. Create role_permission pivot table
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. Update management_users table
        Schema::table('management_users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('role')->constrained('roles')->onDelete('set null');
        });

        // 5. Seed default roles and map existing users
        $adminRole = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full access to all system features',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $crewRole = DB::table('roles')->insertGetId([
            'name' => 'crew',
            'display_name' => 'Crew',
            'description' => 'Access to assigned events and crew features',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Map existing admin users
        DB::table('management_users')->where('role', 'admin')->update(['role_id' => $adminRole]);
        // Map existing crew users
        DB::table('management_users')->where('role', 'crew')->update(['role_id' => $crewRole]);

        // 6. Define some basic permissions
        $permissions = [
            ['name' => 'dashboard-view', 'display_name' => 'View Dashboard', 'module' => 'Dashboard'],
            ['name' => 'event-manage', 'display_name' => 'Manage Events', 'module' => 'Event'],
            ['name' => 'crew-manage', 'display_name' => 'Manage Crews', 'module' => 'Crew'],
            ['name' => 'vendor-manage', 'display_name' => 'Manage Vendors', 'module' => 'Vendor'],
            ['name' => 'package-manage', 'display_name' => 'Manage Packages', 'module' => 'Package'],
            ['name' => 'payment-manage', 'display_name' => 'Manage Payments', 'module' => 'Payment'],
            ['name' => 'financial-view', 'display_name' => 'View Financials', 'module' => 'Financial'],
            ['name' => 'system-setting', 'display_name' => 'Manage System Settings', 'module' => 'System'],
        ];

        foreach ($permissions as $permission) {
            $permissionId = DB::table('permissions')->insertGetId(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Assign all permissions to admin by default
            DB::table('role_permission')->insert([
                'role_id' => $adminRole,
                'permission_id' => $permissionId
            ]);
        }
        
        // Crew gets some permissions
        $crewPermissions = ['dashboard-view', 'event-manage'];
        foreach ($crewPermissions as $name) {
            $p = DB::table('permissions')->where('name', $name)->first();
            if ($p) {
                DB::table('role_permission')->insert([
                    'role_id' => $crewRole,
                    'permission_id' => $p->id
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('management_users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
