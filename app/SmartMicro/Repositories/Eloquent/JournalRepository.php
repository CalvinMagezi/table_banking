<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 28/08/2019
 * Time: 14:17
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\Account;
use App\Models\Journal;
use App\SmartMicro\Repositories\Contracts\AccountLedgerInterface;
use App\SmartMicro\Repositories\Contracts\JournalInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JournalRepository extends BaseRepository implements JournalInterface
{
    protected $model, $accountLedgerRepository;

    /**
     * JournalRepository constructor.
     * @param Journal $model
     * @param AccountLedgerInterface $accountLedgerRepository
     */
    function __construct(Journal $model, AccountLedgerInterface $accountLedgerRepository)
    {
        $this->model = $model;
        $this->accountLedgerRepository = $accountLedgerRepository;
    }

    /**
     * Active user's branch
     * @return mixed
     */
    private function branchId() {
        return auth()->check() ? auth('api')->user()->branch_id : null;
    }

    /**
     * Get account from active or provided branch
     * @param $accountName
     * @param $branchId
     * @return mixed
     * @throws \Exception
     */
    private function accountId($accountName, $branchId = null) {
        if(is_null($branchId) ) {
            $branchId = $this->branchId();
        }
        $accountId = Account::where('branch_id', $branchId)
            ->where('account_name', $accountName)
            ->select('id')->first()['id'];
        if(is_null($accountId))
            throw new \Exception('Null Account ID');
        return $accountId;
    }

    /**
     * XYZ A/C = Member Account
     * @param $loan
     * @param string $memberId
     * @return mixed
     */
    private function getMemberAccount($loan, $memberId = '') {
        if(!is_null($loan)){
            return Account::where('account_name', $loan['member_id'])
                ->select('id')
                ->first()['id']; // xyc a/c
        }else {
            return Account::where('account_name', $memberId)
                ->select('id')
                ->first()['id']; // xyc a/c
        }
    }

    /**
     * @param array $data
     * @return null
     */
    public function create(array $data)
    {
        try{
            $journalEntry = $this->model->create($data);
            // Create an entry into the ledger too.
            $this->accountLedgerRepository->ledgerEntry($journalEntry['id']);
        }catch (\Exception $exception){
            report($exception);
        }
        return null;
    }

    /**
     * @param $capitalData
     * @return mixed|void
     * @throws \Exception
     */
    public function capitalReceivedEntry($capitalData) {
        $data = [
            'narration'             => 'Capital to bank',
            'amount'                => $capitalData['amount'],
            'transaction_id'        => $capitalData['id'],
            'debit_account_id'      => $this->accountId(BANK_ACCOUNT_NAME, $capitalData['branch_id']), // bank a/c
            'credit_account_id'     => $this->accountId(CAPITAL_ACCOUNT_NAME, $capitalData['branch_id']), // capital a/c
        ];
        $this->create($data);
    }

    /**
     * Journal entry for the loan issue
     * @param $loanId
     * @param $amount
     * @param $debitAccountId
     * @param $creditAccountId
     */
    public function loanIssuedEntry($loanId, $amount, $debitAccountId, $creditAccountId) {
        $data = [
            'narration'             => 'Loan Issued #'.$loanId,
            'amount'                => $amount,
            'transaction_id'        => $loanId,
            'debit_account_id'      => $debitAccountId,
            'credit_account_id'     => $creditAccountId,
        ];
        $this->create($data);
    }


    /**
     * Disburse loan. Like a withdrawal by the borrower / member
     * @param $loan
     * @return mixed|void
     * @throws \Exception
     */
    public function loanDisburse($loan) {

        if ($loan !== null){
            $data = [
                'narration'             => 'Loan Disbursed #'.$loan['loan_reference_number'],
                'amount'                => $loan['amount_approved'],
                'transaction_id'        => $loan['id'],
                'debit_account_id'      => $this->getMemberAccount($loan), // xyz a/c
                'credit_account_id'     => $this->accountId(BANK_ACCOUNT_NAME) // bank a/c
            ];
            $this->create($data);
        }
    }

    /**
     * Journal entry for the service fee demand
     * @param $loan
     * @return mixed|void
     * @throws \Exception
     */
    public function serviceFeeDemand($loan) {
        if ($loan !== null) {
            $data = [
                'narration' => 'Service Fee Bill #' . $loan['loan_reference_number'],
                'amount' => $loan['service_fee'],
                'transaction_id' => $loan['id'],
                'debit_account_id' => $this->getMemberAccount($loan), // xyz a/c
                'credit_account_id' => $this->accountId(SERVICE_FEE_ACCOUNT_NAME), // service fee a/c
            ];
            $this->create($data);
        }
    }

    /**
     * Journal entry for service fee paid
     * @param $loan
     * @return mixed|void
     * @throws \Exception
     */
    public function serviceFeeReceived($loan) {
        $data = [
            'narration'             => 'Service Fee Received #'.$loan['loan_reference_number'],
            'amount'                => $loan['service_fee'],
            'transaction_id'        => $loan['id'],
            'debit_account_id'      => $this->accountId(BANK_ACCOUNT_NAME), // our bank a/c
            'credit_account_id'     => $this->getMemberAccount($loan) // xyz a/c a/c
        ];
        $this->create($data);
    }

    /**
     * Journal entry for interest due
     * @param $loan
     * @param $interestAmount
     * @param $interestDueId
     * @return mixed|void
     * @throws \Exception
     */
    public function interestDue($loan, $interestAmount, $interestDueId) {
        $data = [
            'narration'             => 'Interest Due #'.$loan['loan_reference_number'],
            'amount'                => $interestAmount,
            'transaction_id'        => $interestDueId,
            'debit_account_id'      => $this->getMemberAccount($loan), // xyz a/c
            'credit_account_id'     => $this->accountId(INTEREST_ACCOUNT_NAME, $loan['branch_id']), // interest a/c
            'branch_id'             => $loan['branch_id'], // NOTE: There will be no logged in user for the scheduled calculations.
            'created_by'            => 'system'
        ];
        $this->create($data);
    }

    /**
     * @param $loan
     * @param $penaltyAmount
     * @param $penaltyDueId
     * @throws \Exception
     */
    public function penaltyDue($loan, $penaltyAmount, $penaltyDueId) {
        $data = [
            'narration'             => 'Penalty Due #'.$loan['loan_reference_number'],
            'amount'                => $penaltyAmount,
            'transaction_id'        => $penaltyDueId,
            'debit_account_id'      => $this->getMemberAccount($loan), // xyz a/c
            'credit_account_id'     => $this->accountId(PENALTY_ACCOUNT_NAME, $loan['branch_id']), // penalty a/c
            'branch_id'             => $loan['branch_id'], // NOTE: There will be no logged in user for the scheduled calculations.
            'created_by'            => 'system'
        ];
        $this->create($data);
    }

    /**
     * @param $loan
     * @param $waivedAmount
     * @param $penaltyDueId
     * @throws \Exception
     */
    public function penaltyWaiver($loan, $waivedAmount, $penaltyDueId) {
        $data = [
            'narration'             => 'Penalty Waived #'.$loan['loan_reference_number'],
            'amount'                => $waivedAmount,
            'transaction_id'        => $penaltyDueId,
            'debit_account_id'      => $this->accountId(PENALTY_ACCOUNT_NAME, $loan['branch_id']), // penalty a/c
            'credit_account_id'     => $this->getMemberAccount($loan), // xyz a/c
            'branch_id'             => $loan['branch_id'], // NOTE: There will be no logged in user for the scheduled calculations.
            'created_by'            => 'system'
        ];
        $this->create($data);
    }

    /**
     * Journal entry for payment received
     * @param $paymentData
     * @return mixed|void
     * @throws \Exception
     */
    public function paymentReceivedEntry($paymentData) {
        $data = [
            'narration'             => 'Payment Received #'.$paymentData['receipt_number'],
            'amount'                => $paymentData['amount'],
            'transaction_id'        => $paymentData['id'],
            'debit_account_id'      => $this->accountId(BANK_ACCOUNT_NAME), // bank a/c
            'credit_account_id'     => $this->getMemberAccount(null, $paymentData->member_id), // xyz a/c
        ];
        $this->create($data);
    }

    /**
     * Jounal Entry for a branch expenditure
     * @param $expense
     * @return mixed|void
     * @throws \Exception
     */
    public function expenseEntry($expense) {
        $data = [
            'narration'             => $expense['title'],
            'amount'                => $expense['amount'],
            'transaction_id'        => $expense['id'],
            'debit_account_id'      => $expense['category_id'], // expense a/c
            'credit_account_id'     => $this->accountId(BANK_ACCOUNT_NAME), // bank a/c
        ];
        $this->create($data);
    }

    /**
     * Reverse an expense. An example is during edits.
     * @param $expense
     * @return mixed|void
     * @throws \Exception
     */
    public function expenseReverse($expense){
        $data = [
            'narration'             => $expense['title'] . ' - (Edited)',
            'amount'                => $expense['amount'],
            'transaction_id'        => $expense['id'],
            'debit_account_id'      => $this->accountId(BANK_ACCOUNT_NAME), // bank a/c
            'credit_account_id'     => $expense['category_id'], // expense a/c
        ];
        $this->create($data);
    }

    /**
     * @param $expense
     * @return mixed|void
     * @throws \Exception
     */
    public function expenseDelete($expense){
        $data = [
            'narration'             => $expense['title'] . ' - (Deleted)',
            'amount'                => $expense['amount'],
            'transaction_id'        => $expense['id'],
            'debit_account_id'      => $this->accountId(BANK_ACCOUNT_NAME), // bank a/c
            'credit_account_id'     => $expense['category_id'], // expense a/c
        ];
        $this->create($data);
    }

}