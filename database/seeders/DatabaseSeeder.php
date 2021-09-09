<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    protected $tables = [

    ];

    protected $seeders = [
        'Database\Seeders\AccountClassSeeder',
        'Database\Seeders\AccountTypeSeeder',
        'Database\Seeders\BranchTableSeeder',
        'Database\Seeders\EmployeeTableSeeder',
        'Database\Seeders\PermissionSeeder',
        'Database\Seeders\RoleSeeder',
        'Database\Seeders\GeneralSettingTableSeeder',
        'Database\Seeders\EmailSettingTableSeeder',
        'Database\Seeders\SmsSettingSeeder',
        'Database\Seeders\InterestTypeSeeder',
        'Database\Seeders\PaymentMethodSeeder',
        'Database\Seeders\PaymentFrequencySeeder',
        'Database\Seeders\ReportTypeSeeder',
        'Database\Seeders\FinanceStatementSeeder',
        'Database\Seeders\PenaltyTypeSeeder',
        'Database\Seeders\PenaltyFrequencySeeder',
        'Database\Seeders\EmailTemplateSeeder',
        'Database\Seeders\SmsTemplateSeeder',
        'Database\Seeders\WitnessTypeSeeder',
        'Database\Seeders\CommunicationSettingSeeder',
        'Database\Seeders\UsersTableSeeder'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

