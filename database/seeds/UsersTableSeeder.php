<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 25/10/2018
 * Time: 12:20
 */

namespace database\seeds;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'role_id' => Role::inRandomOrder()->select('id')->first()['id'],
            'employee_id' => Employee::inRandomOrder()->select('id')->first()['id'],
            'email' => 'devtest@devtest.com',
            'password' => 'devtest',
        ]);

    }
}