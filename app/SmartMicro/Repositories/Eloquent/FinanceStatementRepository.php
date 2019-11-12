<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 15/10/2019
 * Time: 12:28
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\FinanceStatement;
use App\SmartMicro\Repositories\Contracts\FinanceStatementInterface;
use Illuminate\Support\Facades\DB;

class FinanceStatementRepository extends BaseRepository implements FinanceStatementInterface
{
    protected $model;

    /**
     * FinanceStatementRepository constructor.
     * @param FinanceStatement $model
     */
    function __construct(FinanceStatement $model)
    {
        $this->model = $model;
    }

    public function balanceSheet($branchId, $startDate, $endDate){

       // $liabilityAccounts = $this->getAccountsByClassName($branchId, LIABILITY);

        $assetAccounts = $this->getAccountsByClassName($branchId, ASSET);
        $equityAccounts = $this->getAccountsByClassName($branchId, LIABILITY);


    }

    public function trialBalancex(){

        //main branch
        $branchId = 'd8def383-31bb-4f10-aa42-eb596c06653a';

        $accountCode = '555';
        $accountCode = MEMBER_ACCOUNT_CODE;

        $allLoanReceivableAccounts = DB::table('accounts')
            ->where('accounts.branch_id', $branchId)
            ->where('accounts.account_code', $accountCode)
            ->join('account_types', function($join) {
                $join->on('account_types.id', '=', 'accounts.account_type_id');
            })
            ->leftJoin('account_classes', 'account_types.account_class_id', '=', 'account_classes.id')
            ->join('account_ledgers', function($join){
                $join->on('account_ledgers.account_id', '=', 'accounts.id');
            })
            ->select(DB::raw(
                'accounts.id as account_id,
                accounts.account_number,
                accounts.account_name,
                accounts.account_code as account_code,
                account_classes.category,
                account_classes.name as account_class_name,
                account_types.name as account_type_name,
                COALESCE(sum(account_ledgers.amount), 0.0) as balance'
            ))
            ->groupBy('accounts.id')
            ->orderBy('account_classes.category')
            ->get();

        $loanReceivableTotal = [];
        if($allLoanReceivableAccounts->count() > 0)
            $loanReceivableTotal = [
                'account_name' => $allLoanReceivableAccounts->first()->account_type_name,
                'account_code' => $allLoanReceivableAccounts->first()->account_code,
                'account_type_name' => $allLoanReceivableAccounts->first()->account_type_name,
                'category' => $allLoanReceivableAccounts->first()->category,
                'account_class_name' => $allLoanReceivableAccounts->first()->account_class_name,
                'balance' => $allLoanReceivableAccounts->sum('balance')
            ];

        $otherAccounts =  DB::table('accounts')
            ->where('accounts.branch_id', $branchId)
            ->where('accounts.account_code', '!=', $accountCode)

            ->join('account_types', function($join) {
                $join->on('account_types.id', '=', 'accounts.account_type_id');
            })
            ->leftJoin('account_classes', 'account_types.account_class_id', '=', 'account_classes.id')
            ->join('account_ledgers', function($join){
                $join->on('account_ledgers.account_id', '=', 'accounts.id');
            })
            ->select(DB::raw(
                'accounts.account_name,
                accounts.account_code as account_code,
                account_classes.category,
                account_classes.name as account_class_name,
                account_types.name as account_type_name,
                COALESCE(sum(account_ledgers.amount), 0.0) as balance'
            ))
            ->groupBy('accounts.id')
            ->orderBy('account_classes.category')
            ->get();

        $combinedCollection = $otherAccounts->push($loanReceivableTotal);

        // Form the trial balance
        $trialBalanceRows = [];
        $trialBalanceRowsx = new \stdClass();

        // Headers
       /* $headerTitle = 'Account';
        $headerDebit = 'DR';
        $headerCredit = 'CR';

        $trialBalanceRows[] = [$headerTitle, $headerDebit, $headerCredit];*/

        $totalDebit = 0;
        $totalCredit = 0;

        $debitBalance = 0;
        $creditBalance = 0;

        foreach ($combinedCollection->sortBy('category', 2, true) as $item){
            if (count((array)$item)<=0){
                break;
            }
            $item = (object)$item;

            $account_name = $item->account_name;

            if($item->category == 'DR'){
                $debitBalance = $item->balance;
                $totalDebit = $totalDebit+$item->balance;
                $creditBalance = 0;
            }
            if($item->category == 'CR'){
                $creditBalance = $item->balance;
                $totalCredit = $totalCredit+$item->balance;
                $debitBalance = 0;
            }
            $trialBalanceRows[] = [ucwords(strtolower($account_name)), $this->formatMoney($debitBalance),  $this->formatMoney($creditBalance)];
        }

        $title = 'Total';
        $totalsDebit = $this->formatMoney($totalDebit);
        $totalsCredit = $this->formatMoney($totalCredit);

        $trialBalanceRows[] = [$title, $totalsDebit, $totalsCredit];

        return $trialBalanceRows;

    }

    public function profitAndLossxx(){

        //main branch
        $branchId = 'd8def383-31bb-4f10-aa42-eb596c06653a';

        // revenue / income accounts
        $revenueAccounts = $this->getAccountsByClassName($branchId, INCOME);
        // calculate income totals
        $incomeAccountsTotal = [];
        if($revenueAccounts->count() > 0)
            $incomeAccountsTotal = [
                'account_name' => $revenueAccounts->first()->account_type_name,
                'account_code' => $revenueAccounts->first()->account_code,
                'account_type_name' => $revenueAccounts->first()->account_type_name,
                'category' => $revenueAccounts->first()->category,
                'account_class_name' => $revenueAccounts->first()->account_class_name,
                'balance' => $revenueAccounts->sum('balance')
            ];

        // Expenditure accounts
        $expenseAccounts = $this->getAccountsByClassName($branchId, EXPENDITURE);
        // calculate expense totals
        $expenseAccountsTotal = [];
        if($expenseAccounts->count() > 0)
            $expenseAccountsTotal = [
                'account_name' => $expenseAccounts->first()->account_type_name,
                'account_code' => $expenseAccounts->first()->account_code,
                'account_type_name' => $expenseAccounts->first()->account_type_name,
                'category' => $expenseAccounts->first()->category,
                'account_class_name' => $expenseAccounts->first()->account_class_name,
                'balance' => $expenseAccounts->sum('balance')
            ];

        $profit = ($incomeAccountsTotal['balance'] * -1) - $expenseAccountsTotal['balance'];

        // revenue Rows
        $revenueStatementRows[] = ['Revenue', ''];
        foreach ($revenueAccounts->sortBy('category', 2, true) as $item){
            if (count((array)$item)<=0){
                break;
            }
            $item = (object)$item;

            $account_name = $item->account_name;
            // Income has CR balance as thus normally comes to this point as a negative
            $account_balance = ($item->balance) * -1;
            $revenueStatementRows[] = [ucwords(strtolower($account_name)), $this->formatMoney($account_balance)];
        }
        $totalRevenueAmount = $this->formatMoney(($incomeAccountsTotal['balance'] * -1));
        $revenueStatementRows[] = ['Total Revenue', $totalRevenueAmount];

        // Expense Rows
        $expenseStatementRows[] = ['Expense', ''];
        foreach ($expenseAccounts->sortBy('category', 2, true) as $item){
            if (count((array)$item)<=0){
                break;
            }
            $item = (object)$item;

            $account_name = $item->account_name;
            $account_balance = $item->balance;

            $expenseStatementRows[] = [ucwords(strtolower($account_name)), $this->formatMoney($account_balance)];
        }
        $totalExpenseAmount = $this->formatMoney($expenseAccountsTotal['balance']);
        $expenseStatementRows[] = ['Total Expenditure', $totalExpenseAmount];

        // combined revenue and expenditure accounts
        $incomeStatement = array_merge($revenueStatementRows, $expenseStatementRows);
        $incomeStatement[] = ['Profit / Loss', $this->formatMoney($profit)];

        return $incomeStatement;
    }

    /**
     * Fetched per branch
     * @param $branchId
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function trialBalance($branchId, $startDate, $endDate){

        $accountCode = MEMBER_ACCOUNT_CODE;

        $allLoanReceivableAccounts = DB::table('accounts')
            ->where('accounts.branch_id', $branchId)
            ->where('accounts.account_code', $accountCode)
            ->join('account_types', function($join) {
                $join->on('account_types.id', '=', 'accounts.account_type_id');
            })
            ->leftJoin('account_classes', 'account_types.account_class_id', '=', 'account_classes.id')
            ->join('account_ledgers', function($join){
                $join->on('account_ledgers.account_id', '=', 'accounts.id');
            })
            ->select(DB::raw(
                'accounts.id as account_id,
                accounts.account_number,
                accounts.account_name,
                accounts.account_code as account_code,
                account_classes.category,
                account_classes.name as account_class_name,
                account_types.name as account_type_name,
                COALESCE(sum(account_ledgers.amount), 0.0) as balance'
            ))
            ->groupBy('accounts.id')
            ->orderBy('account_classes.category')
            ->get();

        $loanReceivableTotal = [];
        if($allLoanReceivableAccounts->count() > 0)
            $loanReceivableTotal = [
                'account_name' => $allLoanReceivableAccounts->first()->account_type_name,
                'account_code' => $allLoanReceivableAccounts->first()->account_code,
                'account_type_name' => $allLoanReceivableAccounts->first()->account_type_name,
                'category' => $allLoanReceivableAccounts->first()->category,
                'account_class_name' => $allLoanReceivableAccounts->first()->account_class_name,
                'balance' => $allLoanReceivableAccounts->sum('balance')
            ];

        $otherAccounts =  DB::table('accounts')
            ->where('accounts.branch_id', $branchId)
            ->where('accounts.account_code', '!=', $accountCode)

            ->join('account_types', function($join) {
                $join->on('account_types.id', '=', 'accounts.account_type_id');
            })
            ->leftJoin('account_classes', 'account_types.account_class_id', '=', 'account_classes.id')
            ->join('account_ledgers', function($join){
                $join->on('account_ledgers.account_id', '=', 'accounts.id');
            })
            ->select(DB::raw(
                'accounts.account_name,
                accounts.account_code as account_code,
                account_classes.category,
                account_classes.name as account_class_name,
                account_types.name as account_type_name,
                COALESCE(sum(account_ledgers.amount), 0.0) as balance'
            ))
            ->groupBy('accounts.id')
            ->orderBy('account_classes.category')
            ->get();

        $combinedCollection = $otherAccounts->push($loanReceivableTotal);

        // Form the trial balance
        $trialBalanceRows = [];
        // Headers
        /* $headerTitle = 'Account';
         $headerDebit = 'DR';
         $headerCredit = 'CR';

         $trialBalanceRows[] = [$headerTitle, $headerDebit, $headerCredit];*/

        $totalDebit = 0;
        $totalCredit = 0;

        $debitBalance = 0;
        $creditBalance = 0;

        foreach ($combinedCollection->sortBy('category', 2, true) as $item){
            if (count((array)$item)<=0){
                break;
            }
            $item = (object)$item;

            $account_name = $item->account_name;

            if($item->category == 'DR'){
                $debitBalance = $item->balance;
                $totalDebit = $totalDebit+$item->balance;
                $creditBalance = 0;
            }
            if($item->category == 'CR'){
                $creditBalance = $item->balance;
                $totalCredit = $totalCredit+$item->balance;
                $debitBalance = 0;
            }
            $trialBalanceRows[] = [ucwords(strtolower($account_name)), $this->formatMoney($debitBalance),  $this->formatMoney($creditBalance)];
        }

        $title = 'Total';
        $totalsDebit = $this->formatMoney($totalDebit);
        $totalsCredit = $this->formatMoney($totalCredit);

        $trialBalanceRows[] = [$title, $totalsDebit, $totalsCredit];

        return $trialBalanceRows;
    }

    /**
     * Fetched per branch
     * @param $branchId
     * @return array
     */
    public function incomeStatement($branchId) {
        // revenue / income accounts
        $revenueAccounts = $this->getAccountsByClassName($branchId, INCOME);
        // calculate income totals
        $incomeAccountsTotal = [];
        if($revenueAccounts->count() > 0)
            $incomeAccountsTotal = [
                'account_name' => $revenueAccounts->first()->account_type_name,
                'account_code' => $revenueAccounts->first()->account_code,
                'account_type_name' => $revenueAccounts->first()->account_type_name,
                'category' => $revenueAccounts->first()->category,
                'account_class_name' => $revenueAccounts->first()->account_class_name,
                'balance' => $revenueAccounts->sum('balance')
            ];

        // Expenditure accounts
        $expenseAccounts = $this->getAccountsByClassName($branchId, EXPENDITURE);
        // calculate expense totals
        $expenseAccountsTotal = [];
        if($expenseAccounts->count() > 0)
            $expenseAccountsTotal = [
                'account_name' => $expenseAccounts->first()->account_type_name,
                'account_code' => $expenseAccounts->first()->account_code,
                'account_type_name' => $expenseAccounts->first()->account_type_name,
                'category' => $expenseAccounts->first()->category,
                'account_class_name' => $expenseAccounts->first()->account_class_name,
                'balance' => $expenseAccounts->sum('balance')
            ];

        $profit = 0;
        if(array_key_exists('balance', $incomeAccountsTotal) && array_key_exists('balance', $expenseAccountsTotal))
            $profit = ($incomeAccountsTotal['balance'] * -1) - $expenseAccountsTotal['balance'];

        // revenue Rows
        $revenueStatementRows[] = ['Revenue', ''];
        foreach ($revenueAccounts->sortBy('category', 2, true) as $item){
            if (count((array)$item)<=0){
                break;
            }
            $item = (object)$item;

            $account_name = $item->account_name;
            // Income has CR balance as thus normally comes to this point as a negative
            $account_balance = ($item->balance) * -1;
            $revenueStatementRows[] = [ucwords(strtolower($account_name)), $this->formatMoney($account_balance)];
        }

        $totalRevenueAmount = 0;
        if(array_key_exists('balance', $incomeAccountsTotal))
            $totalRevenueAmount = $this->formatMoney(($incomeAccountsTotal['balance'] * -1));
        $revenueStatementRows[] = ['Total Revenue', $totalRevenueAmount];

        // Expense Rows
        $expenseStatementRows[] = ['Expenditure', ''];
        foreach ($expenseAccounts->sortBy('category', 2, true) as $item){
            if (count((array)$item)<=0){
                break;
            }
            $item = (object)$item;

            $account_name = $item->account_name;
            $account_balance = $item->balance;

            $expenseStatementRows[] = [ucwords(strtolower($account_name)), $this->formatMoney($account_balance)];
        }

        $totalExpenseAmount = 0;
        if(array_key_exists('balance', $expenseAccountsTotal))
            $totalExpenseAmount = $this->formatMoney($expenseAccountsTotal['balance']);
        $expenseStatementRows[] = ['Total Expenditure', $totalExpenseAmount];

        // combined revenue and expenditure accounts
        $incomeStatement = array_merge($revenueStatementRows, $expenseStatementRows);
        $incomeStatement[] = ['Profit / Loss', $this->formatMoney($profit)];

        return $incomeStatement;
    }

    /**
     * @param $branchId
     * @param $className
     * @return \Illuminate\Support\Collection
     */
    private function getAccountsByClassName($branchId, $className) {
        return DB::table('accounts')
            ->where('accounts.branch_id', $branchId)
            ->join('account_types', function($join) {
                $join->on('account_types.id', '=', 'accounts.account_type_id');
            })
            ->join('account_classes', function($join) use ($className){
                $join->on('account_types.account_class_id', '=', 'account_classes.id');
                $join->where('account_classes.name', '=', $className);
            })
            ->join('account_ledgers', function($join){
                $join->on('account_ledgers.account_id', '=', 'accounts.id');
            })
            ->select(DB::raw(
                'accounts.id as account_id,
                accounts.account_number,
                accounts.account_name,
                accounts.account_code as account_code,
                account_classes.category,
                account_classes.name as account_class_name,
                account_types.name as account_type_name,
                COALESCE(sum(account_ledgers.amount), 0.0) as balance'
            ))
            ->groupBy('accounts.id')
            ->orderBy('account_classes.category')
            ->get();
    }
}