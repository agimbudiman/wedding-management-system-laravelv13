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
        $permissionId = Illuminate\Support\Facades\DB::table('permissions')->insertGetId([
            'name' => 'client-access-manage',
            'display_name' => 'Manage Client Access',
            'module' => 'Event',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign to admin role by default
        $adminRole = Illuminate\Support\Facades\DB::table('roles')->where('name', 'admin')->first();
        if ($adminRole) {
            Illuminate\Support\Facades\DB::table('role_permission')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $permissionId
            ]);
        }

        // Assign to roles that have 'event-manage'
        $rolesWithManage = Illuminate\Support\Facades\DB::table('roles')
            ->join('role_permission', 'roles.id', '=', 'role_permission.role_id')
            ->join('permissions', 'role_permission.permission_id', '=', 'permissions.id')
            ->where('permissions.name', 'event-manage')
            ->where('roles.name', '!=', 'admin') // skip admin as already added
            ->select('roles.id')
            ->get();

        foreach ($rolesWithManage as $role) {
            Illuminate\Support\Facades\DB::table('role_permission')->insert([
                'role_id' => $role->id,
                'permission_id' => $permissionId
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = Illuminate\Support\Facades\DB::table('permissions')->where('name', 'client-access-manage')->first();
        if ($permission) {
            Illuminate\Support\Facades\DB::table('role_permission')->where('permission_id', $permission->id)->delete();
            Illuminate\Support\Facades\DB::table('permissions')->where('id', $permission->id)->delete();
        }
    }
};
