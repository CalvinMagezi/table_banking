<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 28/08/2019
 * Time: 14:16
 */

namespace App\SmartMicro\Repositories\Contracts;

interface JournalInterface extends BaseInterface
{
    /**
     * @param $capitalData
     * @return mixed
     */
    function capitalReceivedEntry($capitalData);

    /**
     * @param $loan
     * @return mixed
     */
    function loanDisburse($loan);

    /**
     * @param $loan
     * @return mixed
     */
    function serviceFeeDemand($loan);

    /**
     * @param $loan
     * @return mixed
     */
    function serviceFeeReceived($loan);

    /**
     * @param $loan
     * @param $interestAmount
     * @param $interestDueId
     * @return mixed
     */
    function interestDue($loan, $interestAmount, $interestDueId);

    /**
     * @param $loan
     * @param $penaltyAmount
     * @param $penaltyDueId
     * @return mixed
     */
    function penaltyDue($loan, $penaltyAmount, $penaltyDueId);

    /**
     * @param $paymentData
     * @return mixed
     */
    function paymentReceivedEntry($paymentData);

    /**
     * @param $loan
     * @param $waivedAmount
     * @param $penaltyDueId
     * @return mixed
     */
    function penaltyWaiver($loan, $waivedAmount, $penaltyDueId);

    /**
     * @param $expense
     * @return mixed
     */
    function expenseEntry($expense);

    /**
     * @param $expense
     * @return mixed
     */
    function expenseReverse($expense);

    /**
     * @param $original
     * @return mixed
     */
    function expenseDelete($original);
}