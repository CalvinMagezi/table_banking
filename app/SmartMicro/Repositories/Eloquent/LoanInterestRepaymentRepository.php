<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 21:18
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Events\Payment\PaidLoan;
use App\Models\Loan;
use App\Models\LoanInterestRepayment;
use App\Models\Transaction;
use App\SmartMicro\Repositories\Contracts\LoanInterestRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\TransactionInterface;
use Illuminate\Support\Facades\DB;

class LoanInterestRepaymentRepository extends BaseRepository implements LoanInterestRepaymentInterface
{
    protected $model, $transactionRepository, $loanInterestRepaymentRepository;

    /**
     * LoanInterestRepaymentRepository constructor.
     * @param LoanInterestRepayment $model
     * @param TransactionInterface $transactionRepository
     */
    function __construct(LoanInterestRepayment $model, TransactionInterface $transactionRepository)
    {
        $this->model = $model;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param $interestRepaymentId
     * @return mixed
     */
    public function paidAmount($interestRepaymentId) {
        return DB::table('transactions')
            ->select(DB::raw('COALESCE(sum(transactions.amount), 0.0) as totalPaid'))
            ->where('loan_interest_repayments_id', $interestRepaymentId)
            ->where(function($query) {
                $query->where('transaction_type', 'interest_payment');
            })
            ->first()->totalPaid;
    }

    /**
     * Take a loan, pay any due interest
     * @param $paymentId
     * @param $amount isn't necessarily total amount for this paymentId.
     *         Some amount from the payment could have been used before this method is called
     * @param $loanId
     * @return float|int total amount assigned for interest payment
     */
    public function payDueInterest($paymentId, $amount, $loanId) {

        $paidInterestAmount = 0;

        $loanInterestRepaymentDueRecords = $this->model
            ->where('loan_id', $loanId)
            ->where('paid_on', null)
            ->orderBy('created_at', 'asc')
            ->get()->toArray();

        if (!is_null($loanInterestRepaymentDueRecords) && count($loanInterestRepaymentDueRecords) > 0) {

            foreach ($loanInterestRepaymentDueRecords as $dueRecord){

                 $interestDue = $dueRecord['amount'];

                // Past partial payments
                $paidInterest = DB::table('transactions')
                    ->select(DB::raw('SUM(amount) as paid'))
                    ->where('loan_interest_repayments_id', $dueRecord['id'])->get()->toArray();

                // Actual interest amount due
                foreach ($paidInterest as $paidAmount) {
                    if (null !== $paidAmount) {
                        $interestDue = $interestDue - ($paidAmount->paid);
                    }
                }
                // Now pay
                if($amount > 0)
                    $paidInterestAmount = $paidInterestAmount + $this->transactPayment($loanId, $interestDue, $amount, $paymentId, $dueRecord['id']);
                $amount = $amount - $paidInterestAmount;
            }
        }
        event(new PaidLoan($loanId));
        return $paidInterestAmount;
    }

    /**
     * Pay pending interest
     * @param $loanId
     * @param $interestDue
     * @param $walletAmount
     * @param $paymentId
     * @param $loanInterestRepaymentDueId
     * @return int
     */
    private function transactPayment($loanId, $interestDue, $walletAmount, $paymentId, $loanInterestRepaymentDueId) {

        $interestPaid = 0;

        if( $interestDue > 0 ) {
            switch ($walletAmount) {
                // pay all interest
                case  $walletAmount >= $interestDue:
                    {
                        $interestPaid = $interestDue;
                        $this->update(
                            ['paid_on' => now()],
                            $loanInterestRepaymentDueId
                        );
                    }
                    break;
                // pay partial interest
                case  $walletAmount < $interestDue:
                    {
                        $interestPaid =  $walletAmount;
                    }
                    break;
                default: {
                    $interestPaid = 0;
                }
            }
            $this->transactionRepository->interestPaymentEntry($interestPaid, $loanInterestRepaymentDueId, $paymentId, $loanId);
        }
        return $interestPaid;
    }

}