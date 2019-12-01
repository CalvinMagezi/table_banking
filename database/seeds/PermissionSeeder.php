<?php

namespace database\seeds;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{

    public function run()
    {
        DB::table('permissions')->delete();

        $permissions = [
            [
                'name'           => 'expense-add',
                'display_name'   => 'Expense Add',
                'description'    => 'Expense Add'
            ],
            [
                'name'           => 'member-add',
                'display_name'   => 'Members Add',
                'description'    => 'Members Add'
            ],
            [
                'name'           => 'loans-view',
                'display_name'   => 'Loans View Details',
                'description'    => 'Loans View Details'
            ],
            [
                'name'           => 'loan-application-add',
                'display_name'   => 'Add Loan Application',
                'description'    => 'Add Loan Application'
            ],
            [
                'name'           => 'loan-application-review',
                'display_name'   => 'Loan Application Review',
                'description'    => 'Ability to Confirm or Reject loans'
            ],
            [
                'name'           => 'payments-add',
                'display_name'   => 'Payments Add-View',
                'description'    => 'Payments Add-View'
            ],
            [
                'name'           => 'settings-general',
                'display_name'   => 'General Settings',
                'description'    => 'General Settings'
            ],
            [
                'name'           => 'settings-accounting',
                'display_name'   => 'Accounting Settings',
                'description'    => 'Accounting Settings'
            ],
            [
                'name'           => 'settings-borrowers',
                'display_name'   => 'Borrowers Settings',
                'description'    => 'Borrowers Settings'
            ],
            [
                'name'           => 'settings-branches',
                'display_name'   => 'Branches Settings',
                'description'    => 'Branches Settings'
            ],
            [
                'name'           => 'settings-communication',
                'display_name'   => 'Communication Settings',
                'description'    => 'Communication Settings'
            ],
            [
                'name'           => 'settings-expenses',
                'display_name'   => 'Expense Settings',
                'description'    => 'Expense Settings'
            ],
            [
                'name'           => 'settings-loans',
                'display_name'   => 'Loan Settings',
                'description'    => 'Loan Settings'
            ],
            [
                'name'           => 'settings-payments',
                'display_name'   => 'Payment Settings',
                'description'    => 'Payment Settings'
            ],
            [
                'name'           => 'settings-users',
                'display_name'   => 'Users - Add-Edit-Delete',
                'description'    => 'Users - Add-Edit-Delete'
            ],
            [
                'name'           => 'view-reports',
                'display_name'   => 'View Reports',
                'description'    => 'View Reports'
            ],
            [
                'name'           => 'my-profile',
                'display_name'   => 'Edit Own Profile',
                'description'    => 'Edit Own Profile'
            ]
        ];

        foreach ($permissions as $key => $value){
            Permission::create($value);
        }

     /*   $entities = [
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

        });*/

    }

}