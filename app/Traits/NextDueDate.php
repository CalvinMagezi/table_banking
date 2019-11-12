<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 25/09/2019
 * Time: 13:40
 */

namespace App\Traits;


use App\Models\PaymentFrequency;
use Carbon\Carbon;

trait NextDueDate
{
    /**
     * @param $startDate
     * @param $paymentFrequencyId
     * @return false|string
     */
    public static function dueDate($startDate, $paymentFrequencyId) {

        $paymentFrequency = PaymentFrequency::where('id', $paymentFrequencyId)->first()['name'];

        switch ($paymentFrequency) {
            case 'one_time':{
                $nextDueDate = $startDate;
            }
                break;
            case 'daily': {
                $nextDueDate = Carbon::create($startDate)
                    ->addDays(1);
            }
                break;
            case 'weekly': {
                $nextDueDate = Carbon::create($startDate)
                    ->addWeeks(1);
            }
                break;
            case 'monthly': {
                $nextDueDate = Carbon::create($startDate)
                    ->addMonthsNoOverflow(1);
            }
                break;

            default: {
                $nextDueDate = null;
            }
        }
        return date('Y-m-d H:i:s', strtotime($nextDueDate));
    }

}