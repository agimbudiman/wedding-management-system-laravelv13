<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add dashboard role access permissions
        $permissions = [
            ['name' => 'dashboard-admin-view', 'display_name' => 'View Admin Dashboard', 'module' => 'Dashboard'],
            ['name' => 'dashboard-crew-view', 'display_name' => 'View Crew Dashboard', 'module' => 'Dashboard'],
        ];

        foreach ($permissions as $permission) {
            $permissionId = DB::table('permissions')->insertGetId(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Assign dashboard-admin-view to admin role
            if ($permission['name'] === 'dashboard-admin-view') {
                $adminRole = DB::table('roles')->where('name', 'admin')->first();
                if ($adminRole) {
                    DB::table('role_permission')->insert([
                        'role_id' => $adminRole->id,
                        'permission_id' => $permissionId
                    ]);
                }
            }

            // Assign dashboard-crew-view to crew role
            if ($permission['name'] === 'dashboard-crew-view') {
                $crewRole = DB::table('roles')->where('name', 'crew')->first();
                if ($crewRole) {
                    DB::table('role_permission')->insert([
                        'role_id' => $crewRole->id,
                        'permission_id' => $permissionId
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'dashboard-admin-view',
            'dashboard-crew-view'
        ])->delete();
    }
};
