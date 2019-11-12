<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 21:24
 */

namespace App\SmartMicro\Repositories\Contracts;

interface LoanPrincipalRepaymentInterface extends BaseInterface
{
    /**
     * @param $paymentId
     * @param $amount
     * @param $loanId
     * @return mixed
     */
    function payDuePrincipal($paymentId, $amount, $loanId);
}