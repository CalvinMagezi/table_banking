<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 10:48
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\LoanPenalty;
use App\SmartMicro\Repositories\Contracts\LoanPenaltyInterface;
use App\SmartMicro\Repositories\Contracts\TransactionInterface;
use Illuminate\Support\Facades\DB;

class LoanPenaltyRepository extends BaseRepository implements LoanPenaltyInterface
{
    protected $model, $transactionRepository, $loanPenaltyRepository;

    /**
     * LoanPenaltyRepository constructor.
     * @param LoanPenalty $model
     * @param TransactionInterface $transactionRepository
     */
    function __construct(LoanPenalty $model, TransactionInterface $transactionRepository)
    {
        $this->model = $model;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Take a loan, pay any due penalty
     * @param $paymentId
     * @param $amount isn't necessarily total amount for this paymentId.
     *         Some amount from the payment could have been used before this method is called
     * @param $loanId
     * @return float|int total amount assigned for penalty payment
     */
    public function payDuePenalty($paymentId, $amount, $loanId) {

        $paidPenaltyAmount = 0;

        $loanPenaltyPaymentDueRecords = $this->model
            ->where('loan_id', $loanId)
            ->where('paid_on', null)
            ->orderBy('created_at', 'asc')
            ->get()->toArray();

        if (!is_null($loanPenaltyPaymentDueRecords) && count($loanPenaltyPaymentDueRecords) > 0) {

            foreach ($loanPenaltyPaymentDueRecords as $dueRecord){

                $penaltyDue = $dueRecord['amount'];

                // Past partial payments
                $paidInterest = DB::table('transactions')
                    ->select(DB::raw('SUM(amount) as paid'))
                    ->where('loan_penalties_id', $dueRecord['id'])->get()->toArray();

                // Actual penalty amount due
                foreach ($paidInterest as $paidAmount) {
                    if (null !== $paidAmount) {
                        $penaltyDue = $penaltyDue - ($paidAmount->paid);
                    }
                }
                // Now pay
                if($amount > 0)
                    $paidPenaltyAmount = $paidPenaltyAmount + $this->transactPayment($loanId, $penaltyDue, $amount, $paymentId, $dueRecord['id']);
                $amount = $amount - $paidPenaltyAmount;
            }
        }

        return $paidPenaltyAmount;
    }

    /**
     * Pay pending Penalty
     * @param $loanId
     * @param $penaltyDue
     * @param $walletAmount
     * @param $paymentId
     * @param $loanPenaltyPaymentDueId
     * @return int
     */
    private function transactPayment($loanId, $penaltyDue, $walletAmount, $paymentId, $loanPenaltyPaymentDueId) {

        $penaltyPaid = 0;

        if( $penaltyDue > 0 ) {
            switch ($walletAmount) {
                // pay all interest
                case  $walletAmount >= $penaltyDue:
                    {
                        $penaltyPaid = $penaltyDue;
                        $this->update(
                            ['paid_on' => now()],
                            $loanPenaltyPaymentDueId
                        );
                    }
                    break;
                // pay partial interest
                case  $walletAmount < $penaltyDue:
                    {
                        $penaltyPaid =  $walletAmount;
                    }
                    break;
                default: {
                    $penaltyPaid = 0;
                }
            }
            $this->transactionRepository->penaltyPaymentEntry($penaltyPaid, $loanPenaltyPaymentDueId, $paymentId, $loanId);
        }
        return $penaltyPaid;
    }

}