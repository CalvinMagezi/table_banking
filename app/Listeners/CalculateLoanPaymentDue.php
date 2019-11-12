<?php

namespace App\Listeners;

use App\Events\Loan\LoanDueChecked;
use App\SmartMicro\Repositories\Contracts\LoanInterface;
use App\Traits\NextDueDate;

class CalculateLoanPaymentDue
{
    use NextDueDate;
    /**
     * @var LoanInterface
     */
    protected $loanRepository;

    /**
     * CalculateLoanPaymentDue constructor.
     * @param LoanInterface $loanInterface
     */
    public function __construct(LoanInterface $loanInterface)
    {
        $this->loanRepository   = $loanInterface;
    }

    /**
     * Fetch all loans due for payment today. Calculate their interest and principal due.
     * Finally update the next calculation date for the loan.
     *
     * @param  LoanDueChecked  $event
     * @return void
     */
    public function handle(LoanDueChecked $event)
    {
        $today = date('Y-m-d');
        $this->loanRepository->calculateLoanRepaymentDue($today);
        $this->loanRepository->calculatePenalties($today);
    }
}
