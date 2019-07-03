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
                    'name'           => 'create-'.$entity.'',
                    'display_name'   => 'Create '.$entity.'',
                    'description'    => 'Ability to create new '.$entity.''
                ],
                [
                    'name'           => 'edit-'.$entity.'',
                    'display_name'   => 'Edit '.$entity.'',
                    'description'    => 'Ability to edit '.$entity.''
                ],
                [
                    'name'           => 'view-'.$entity.'',
                    'display_name'   => 'View '.$entity.'',
                    'description'    => 'Ability to view '.$entity.''
                ],
                [
                    'name'           => 'delete-'.$entity.'',
                    'display_name'   => 'Delete '.$entity.'',
                    'description'    => 'Ability to delete '.$entity.''
                ],
            ];

            foreach ($permissions as $key => $value){
                Permission::create($value);
            }

        });

    }

}