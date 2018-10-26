<?php

namespace database\seeds;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{

    public function run()
    {

        DB::table('roles')->delete();

        $admin = Role::create([
            'role_name' => 'Admin',
            'role_display_name' => 'Admin',
            'role_description' => "site admin"
        ]);

        DB::table('permission_role')->delete();

        $permissions = Permission::select('uuid')->get();

        if (!is_null($admin) && (!is_null($permissions))){
            $admin->permissions()->sync($permissions);
        }

    }

}