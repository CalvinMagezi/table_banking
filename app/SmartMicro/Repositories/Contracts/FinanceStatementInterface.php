<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 15/10/2019
 * Time: 12:28
 */

namespace App\SmartMicro\Repositories\Contracts;

interface FinanceStatementInterface extends BaseInterface
{
    function trialBalance($branchId, $startDate, $endDate);

    function incomeStatement($branchId);

    function balanceSheet($branchId, $startDate, $endDate);

    function trialBalancex();

    function profitAndLossxx();
}