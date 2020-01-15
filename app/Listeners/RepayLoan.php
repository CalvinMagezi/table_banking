<?php

namespace App\Listeners;

use App\Events\Payment\PaymentReceived;
use App\SmartMicro\Repositories\Contracts\LoanInterestRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\LoanInterface;
use App\SmartMicro\Repositories\Contracts\LoanPenaltyInterface;
use App\SmartMicro\Repositories\Contracts\LoanPrincipalRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\TransactionInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RepayLoan
{
    protected $loanRepository, $transactionRepository, $penaltyRepository,
        $loanInterestRepaymentRepository, $loanPrincipalRepaymentRepository;

    private  $paymentAmount;

    /**
     * RepayLoan constructor.
     * @param TransactionInterface $transactionRepository
     * @param LoanInterface $loanRepository
     * @param LoanPenaltyInterface $penaltyRepository
     * @param LoanInterestRepaymentInterface $loanInterestRepaymentRepository
     * @param LoanPrincipalRepaymentInterface $loanPrincipalRepaymentRepository
     */
    public function __construct(TransactionInterface $transactionRepository,
        LoanInterface $loanRepository, LoanPenaltyInterface $penaltyRepository,
        LoanInterestRepaymentInterface $loanInterestRepaymentRepository,
                                LoanPrincipalRepaymentInterface $loanPrincipalRepaymentRepository
    )
    {
        $this->loanRepository = $loanRepository;
        $this->transactionRepository = $transactionRepository;
        $this->penaltyRepository = $penaltyRepository;
        $this->paymentAmount = 0;

        $this->loanInterestRepaymentRepository = $loanInterestRepaymentRepository;
        $this->loanPrincipalRepaymentRepository = $loanPrincipalRepaymentRepository;
    }

    /**
     *  Handle the PaymentReceived event.
     *
     * As the system is not deposit taking, any payment goes directly to loans account.
     * We assign the amount in the following order
     *
     * 1. Fees e.g pending application fees - not likely
     * 2. Penalties
     * 3. Interests
     * 4. Principal
     * 5. For any remaining amount, we reduce the principal balance for the next repayment cycle calculations
     * @param PaymentReceived $event
     * @throws \Exception
     */
    public function handle(PaymentReceived $event)
    {
        DB::beginTransaction();
        try
        {
            $originalAmount = $event->payment->amount;

        $this->paymentAmount = $event->payment->amount;
        $paymentId = $event->payment->id;
        $paymentDate = $event->payment->payment_date;
        $memberId = $event->payment->member_id;

        // Active loan for the member who just did a payment
        $loan = $this->loanRepository->getActiveLoan($memberId, 'paymentFrequency');
        $loanId = $loan ? $loan->id : null;

        // The loan could have ended (End date isn't null -  after last calculation)
        // ...but still have penalties, interests or principal to pay

        // Check if member has any pending amount to pay
        $allMemberLoans = $this->loanRepository->memberLoans($memberId);
        $totalDue = 0;
        foreach ($allMemberLoans as $loan){
            $penalty = $this->loanRepository->pendingPenalty($loan['id']);
            $interest = $this->loanRepository->pendingInterest($loan['id']);
            $principal = $this->loanRepository->pendingPrincipal($loan['id']);

            $pending = $penalty + $interest + $principal;
            $totalDue = $totalDue + $pending;
        }

       /* if($this->paymentAmount > $totalDue){
           throw new NotFoundHttpException('Rejected !! Amount exceeds active loan balance. We do not accept deposits.');
        }*/
       // && $this->paymentAmount <= $totalDue

            // For the amount received, assign it to different dues (penalty, interest, principal)
            if(!is_null($loan) && null !== $this->paymentAmount &&  $this->paymentAmount > 0){
                // 1. pay penalty
                $penaltyPaid = $this->payPenalty($paymentId, $this->paymentAmount, $loanId);

                if($penaltyPaid < $this->paymentAmount) {
                    $this->paymentAmount = $this->paymentAmount - $penaltyPaid;

                    // 2. Pay interest
                    $interestPaid = $this->payInterest($paymentId, $this->paymentAmount, $loanId);

                    if($interestPaid < $this->paymentAmount){

                        $this->paymentAmount = $this->paymentAmount - $interestPaid;


                        // 3. pay principal
                        $principalPaid = $this->payPrincipal($paymentId, $this->paymentAmount, $loanId);
                        if($principalPaid < $this->paymentAmount){
                            $this->paymentAmount = $this->paymentAmount - $principalPaid;

                            // 4. Any excess funds, reduce loan balance

                            // Means there were no existing records to pay, or some cash remains
                            // So we decide whether to reduce the principal balance or use existing balance to recalculate dues.
                            if($this->paymentAmount > 0){
                                if(property_exists('reduce_principal_early', $loan) && $loan['reduce_principal_early'] == true){
                                    // reduce balance here
                                    //   dd($this->differenceBetweenPaymentDateAndDueDate($paymentDate, $loan));
                                    if($this->differenceBetweenPaymentDateAndDueDate($paymentDate, $loan) > 1){
                                        // reduce balance
                                        $this->transactionRepository->balanceReductionEntry($this->paymentAmount, $paymentId, $loan);
                                        $this->paymentAmount = 0;
                                    }
                                }else {
                                  //  dd($this->paymentAmount);

                                    // recalculate dues and initiate a payment
                                    $this->loanRepository->calculateLoanRepaymentDue($loan['next_repayment_date']);

                                    // Re-initiate this payment process as though a new payment was received
                                    $payment = $event->payment;
                                   // dd($payment['amount']);
                                    $payment['amount'] = $this->paymentAmount;

                                    if($this->paymentAmount == $originalAmount){
                                       throw new NotFoundHttpException('Rejected !! Amount exceeds active loan balance. We do not accept deposits.');
                                    }
                                    event(new PaymentReceived($payment));
                                }
                            }

                        }
                    }

                }
            }else{
                // This member has no loan. We therefore reject their deposit attempt.
                throw new NotFoundHttpException('Rejected !! Member has no active loan. We do not accept deposits.');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param $paymentDate
     * @param $loan
     * @return int
     */
    private function differenceBetweenPaymentDateAndDueDate($paymentDate, $loan) {
        $nextDueDate = $loan['next_repayment_date'];
        $paymentFrequency = $loan->paymentFrequency->name;

        switch ($paymentFrequency){
            case 'monthly': {
                $difference =  Carbon::create($paymentDate)->diffInMonths(Carbon::create($nextDueDate));
            }
                break;
            case 'weekly': {
                $difference =  Carbon::create($paymentDate)->diffInWeeks(Carbon::create($nextDueDate));
            }
                break;
            default: {
                $difference = 0;
            }
        }
        return $difference;
    }

    /**
     * @param $paymentId
     * @param $amount
     * @param $loanId
     * @return mixed
     */
    private function payPenalty($paymentId, $amount, $loanId) {
        return $this->penaltyRepository->payDuePenalty($paymentId, $amount, $loanId);
    }

    /**
     * @param $paymentId
     * @param $amount
     * @param $loanId
     * @return float|int
     */
    private function payInterest($paymentId, $amount, $loanId) {
        return $this->loanInterestRepaymentRepository->payDueInterest($paymentId, $amount, $loanId);
    }

    /**
     * @param $paymentId
     * @param $amount
     * @param $loanId
     * @return mixed
     */
    private function payPrincipal($paymentId, $amount, $loanId) {
        return $this->loanPrincipalRepaymentRepository->payDuePrincipal($paymentId, $amount, $loanId);
    }
}
