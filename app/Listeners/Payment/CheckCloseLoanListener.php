<?php

namespace App\Listeners\Payment;

use App\Events\Payment\PaidLoan;
use App\Models\Loan;
use App\SmartMicro\Repositories\Contracts\LoanInterface;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckCloseLoanListener
{
    /**
     * @var LoanInterface
     */
    protected $loanRepository;

    /**
     * CheckCloseLoanListener constructor.
     * @param LoanInterface $loanInterface
     */
    public function __construct(LoanInterface $loanInterface)
    {
        $this->loanRepository   = $loanInterface;
    }

    /**
     * Handle the event.
     *
     * @param  PaidLoan  $event
     * @return void
     */
    public function handle(PaidLoan $event)
    {
        $today = date('Y-m-d');

        $loanId = $event->loanId;
        $loan = $this->loanRepository->getById($loanId);
        $next_repayment_date = $loan['next_repayment_date'];

        $calculatedPendingAmount = $this->loanRepository->totalPendingAmount($loanId);

         // The period calculations are over also,
        // All calculated amount have been cleared
        if(is_null($next_repayment_date) && $calculatedPendingAmount == 0){
            Loan::where('id', $loanId)->update([
                'closed_on' => $today
            ]);
        }

    }
}
