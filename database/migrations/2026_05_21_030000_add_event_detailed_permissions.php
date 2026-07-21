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
        // Add new permissions
        $permissions = [
            ['name' => 'event-view', 'display_name' => 'View Events', 'module' => 'Event'],
            ['name' => 'event-create', 'display_name' => 'Create Events', 'module' => 'Event'],
            ['name' => 'event-edit', 'display_name' => 'Edit Events', 'module' => 'Event'],
            ['name' => 'event-delete', 'display_name' => 'Delete Events', 'module' => 'Event'],
        ];

        foreach ($permissions as $permission) {
            $permissionId = DB::table('permissions')->insertGetId(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Assign to admin role by default
            $adminRole = DB::table('roles')->where('name', 'admin')->first();
            if ($adminRole) {
                DB::table('role_permission')->insert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permissionId
                ]);
            }

            // Assign to roles that have 'event-manage'
            $rolesWithManage = DB::table('roles')
                ->join('role_permission', 'roles.id', '=', 'role_permission.role_id')
                ->join('permissions', 'role_permission.permission_id', '=', 'permissions.id')
                ->where('permissions.name', 'event-manage')
                ->where('roles.name', '!=', 'admin') // skip admin as already added
                ->select('roles.id')
                ->get();

            foreach ($rolesWithManage as $role) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'event-view',
            'event-create',
            'event-edit',
            'event-delete'
        ])->delete();
    }
};
