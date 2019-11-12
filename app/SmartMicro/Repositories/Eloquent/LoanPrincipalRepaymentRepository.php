<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 21:24
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\LoanPrincipalRepayment;
use App\SmartMicro\Repositories\Contracts\LoanPrincipalRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\TransactionInterface;
use Illuminate\Support\Facades\DB;

class LoanPrincipalRepaymentRepository extends BaseRepository implements LoanPrincipalRepaymentInterface
{
    protected $model, $transactionRepository;

    /**
     * LoanPrincipalRepaymentRepository constructor.
     * @param LoanPrincipalRepayment $model
     * @param TransactionInterface $transactionRepository
     */
    function __construct(LoanPrincipalRepayment $model, TransactionInterface $transactionRepository)
    {
        $this->model = $model;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Take a loan, pay any due Principal
     * @param $paymentId
     * @param $amount isn't necessarily total amount for this paymentId.
     *         Some amount from the payment could have been used before this method is called
     * @param $loanId
     * @return float|int total amount assigned for Principal payment
     */
    public function payDuePrincipal($paymentId, $amount, $loanId) {
        $paidPrincipalAmount = 0;

        $loanPrincipalRepaymentDueRecords = $this->model
            ->where('loan_id', $loanId)
            ->where('paid_on', null)
            ->orderBy('created_at', 'asc')
            ->get()->toArray();

        if (!is_null($loanPrincipalRepaymentDueRecords) && count($loanPrincipalRepaymentDueRecords) > 0) {

            foreach ($loanPrincipalRepaymentDueRecords as $dueRecord){

                $principalDue = $dueRecord['amount'];

                // Past partial payments
                $paidPrincipal = DB::table('transactions')
                    ->select(DB::raw('SUM(amount) as paid'))
                    ->where('loan_principal_repayments_id', $dueRecord['id'])->get()->toArray();

                // Actual principal amount due
                foreach ($paidPrincipal as $paidAmount) {
                    if (null !== $paidAmount) {
                        $principalDue = $principalDue - ($paidAmount->paid);
                    }
                }
                // Now pay
                if($amount > 0)
                    $paidPrincipalAmount = $paidPrincipalAmount + $this->transactPayment($loanId, $principalDue, $amount, $paymentId, $dueRecord['id']);
                $amount = $amount - $paidPrincipalAmount;
            }
        }

        return $paidPrincipalAmount;
    }

    /**
     * Pay pending principal
     * @param $loanId
     * @param $principalDue
     * @param $walletAmount
     * @param $paymentId
     * @param $loanPrincipalRepaymentDueId
     * @return int
     */
    private function transactPayment($loanId, $principalDue, $walletAmount, $paymentId, $loanPrincipalRepaymentDueId) {

        $principalPaid = 0;

        if( $principalDue > 0 ) {
            switch ($walletAmount) {
                // pay all principal
                case  $walletAmount >= $principalDue:
                    {
                        $principalPaid = $principalDue;
                        $this->update(
                            ['paid_on' => now()],
                            $loanPrincipalRepaymentDueId
                        );
                    }
                    break;
                // pay partial principal
                case  $walletAmount < $principalDue:
                    {
                        $principalPaid =  $walletAmount;
                    }
                    break;
                default: {
                    $principalPaid = 0;
                }
            }
            $this->transactionRepository->principalPaymentEntry($principalPaid, $loanPrincipalRepaymentDueId, $paymentId, $loanId);
        }
        return $principalPaid;
    }

}