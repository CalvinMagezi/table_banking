<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 21:18
 */

namespace App\SmartMicro\Repositories\Contracts;

interface LoanInterestRepaymentInterface extends BaseInterface
{
    /**
     * @param $paymentId
     * @param $amount
     * @param $loanId
     * @return float|int
     */
    function payDueInterest($paymentId, $amount, $loanId);
}