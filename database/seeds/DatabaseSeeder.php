<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    protected $tables = [
        // 'oauth_clients',
        // 'oauth_scopes',
        //'users'
    ];


    protected $seeders = [
        'database\seeds\EmployeeTableSeeder',
        'database\seeds\PermissionSeeder',
        'database\seeds\RoleSeeder',
        'database\seeds\UsersTableSeeder',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //   Eloquent::unguard();

        $this->cleanDatabase();

        foreach ($this->seeders as $seedClass) {
            $this->call($seedClass);
        }
    }

    /**
     * Clean out the database for a new seed generation
     */
    private function cleanDatabase()
    {
        foreach ($this->tables as $table) {
            DB::statement('TRUNCATE TABLE ' . $table . ' CASCADE;');
        }

    }

}

