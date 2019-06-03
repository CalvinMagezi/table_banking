<?php

namespace database\seeds;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{

    public function run()
    {

        $entities = [
            'user',
            'borrower',
            'loan',
            'loan-type',
            'loan-application',
            'branch',
            'role',
            'permission',
            'payment',
            'payment-method',
            'employee',
            'member',
            'guarantor',
            'loan-status',
            'loan-application-status',
            'borrower-status'
        ];

        DB::table('permissions')->delete();

        array_walk($entities, function($entity) {

            $permissions = [

                [
                    'permission_name'           => 'create-'.$entity.'',
                    'permission_display_name'   => 'Create '.$entity.'',
                    'permission_description'    => 'Ability to create new '.$entity.''
                ],
                [
                    'permission_name'           => 'edit-'.$entity.'',
                    'permission_display_name'   => 'Edit '.$entity.'',
                    'permission_description'    => 'Ability to edit '.$entity.''
                ],
                [
                    'permission_name'           => 'view-'.$entity.'',
                    'permission_display_name'   => 'View '.$entity.'',
                    'permission_description'    => 'Ability to view '.$entity.''
                ],
                [
                    'permission_name'           => 'delete-'.$entity.'',
                    'permission_display_name'   => 'Delete '.$entity.'',
                    'permission_description'    => 'Ability to delete '.$entity.''
                ],
            ];

            foreach ($permissions as $key => $value){
                Permission::create($value);
            }

        });

    }

}