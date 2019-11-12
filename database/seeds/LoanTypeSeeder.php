<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 10/11/2019
 * Time: 15:20
 */

namespace database\seeds;

use App\Models\LoanType;
use App\Models\WitnessType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanTypeSeeder extends Seeder
{

    public function run()
    {
      /*  DB::table('witness_types')->delete();

        LoanType::create([
            'name' => 'relative',
            'display_name' => 'Relative',
            'description' => "Family Relative"
        ]);

        WitnessType::create([
            'name' => 'friend',
            'display_name' => 'Friend',
            'description' => "Close Friend"
        ]);

        WitnessType::create([
            'name' => 'business_partner',
            'display_name' => 'Business Partner',
            'description' => "Business Partner"
        ]);*/

    }

}