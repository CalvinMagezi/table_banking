<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 10:48
 */

namespace App\SmartMicro\Repositories\Contracts;

interface LoanPenaltyInterface extends BaseInterface
{
    /**
     * @param $penaltyRepaymentId
     * @return mixed
     */
    function paidAmount($penaltyRepaymentId);

    /**
     * @param $paymentId
     * @param $amount
     * @param $loanId
     * @return mixed
     */
    function payDuePenalty($paymentId, $amount, $loanId);

    /**
     * @param $loanPenaltyRepaymentId
     * @param $amount
     * @param $loanId
     * @return mixed
     */
    function waivePenalty($loanPenaltyRepaymentId, $amount, $loanId);
}