<?php
/**
 * Created by PhpStorm.
 * Employee: kevin
 * Date: 27/10/2018
 * Time: 11:06
 */

namespace database\seeds;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->delete();

        Employee::create([
            'first_name' => 'Devtest',
            'last_name' => 'Devtest Last',
        ]);

        Employee::create([
            'first_name' => 'Devtest10 ',
            'last_name' => 'Devtest10 Last',

        ]);

        Employee::create([
            'first_name' => 'Devtest20 ',
            'last_name' => 'Devtest20 Last',

        ]);

    }
}