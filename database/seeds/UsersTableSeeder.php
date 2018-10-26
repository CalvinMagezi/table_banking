<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 25/10/2018
 * Time: 12:20
 */

namespace database\seeds;

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
            'first_name' => 'Devtest',
            'role_id' => Role::inRandomOrder()->select('uuid')->first()['uuid'],

            'last_name' => 'Devtest Last',
            'email' => 'devtest@devtest.com',
            'password' => 'devtest',
        ]);

        User::create([
            'first_name' => 'Devtest10 ',
            'role_id' => Role::inRandomOrder()->select('uuid')->first()['uuid'],

            'last_name' => 'Devtest10 Last',
            'email' => 'devtest10@devtest.com',
            'password' => 'devtest',
        ]);

        User::create([
            'first_name' => 'Devtest20 ',
            'role_id' => Role::inRandomOrder()->select('uuid')->first()['uuid'],

            'last_name' => 'Devtest20 Last',
            'email' => 'devtest20@devtest.com',
            'password' => 'devtest',
        ]);

    }
}