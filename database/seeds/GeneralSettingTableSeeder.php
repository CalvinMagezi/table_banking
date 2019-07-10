<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 09/07/2019
 * Time: 12:39
 */

namespace database\seeds;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingTableSeeder extends Seeder
{

    public function run()
    {

        DB::table('general_settings')->delete();

        GeneralSetting::create([
            'business_name' => 'Smart Micro LTD',
            'business_type' => 'Finance',
            'email' => "devtest@devtest.com",
            'contact_first_name' => "Devtest",
            'contact_last_name' => "Devtest"
        ]);

    }

}