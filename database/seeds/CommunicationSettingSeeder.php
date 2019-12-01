<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 24/11/2019
 * Time: 22:12
 */

namespace database\seeds;

use App\Models\CommunicationSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicationSettingSeeder extends Seeder
{

    public function run()
    {
        DB::table('communication_settings')->delete();

        // 1.
        CommunicationSetting::create([
            'name'              => 'new_member_welcome',
            'display_name'      => 'New Member Welcome',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 2.
        CommunicationSetting::create([
            'name'              => 'new_user_welcome',
            'display_name'      => 'New User Welcome',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 3.
        CommunicationSetting::create([
            'name'              => 'reset_password',
            'display_name'      => 'Reset Password',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 4.
        CommunicationSetting::create([
            'name'              => 'new_loan_application',
            'display_name'      => 'New Loan Application',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 5.
        CommunicationSetting::create([
            'name'              => 'loan_application_approved',
            'display_name'      => 'Loan Application Approved',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 6.
        CommunicationSetting::create([
            'name'              => 'loan_application_rejected',
            'display_name'      => 'Loan Application Rejected',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 7.
        CommunicationSetting::create([
            'name'              => 'payment_received',
            'display_name'      => 'Payment Received',
            'email_template' => false,
            'sms_template'   => false
        ]);

        // 8.
        CommunicationSetting::create([
            'name'              => 'system_summary',
            'display_name'      => 'Payment Received',
            'email_template' => false,
            'sms_template'   => false
        ]);
    }

}