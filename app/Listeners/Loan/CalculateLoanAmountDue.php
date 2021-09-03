<?php

namespace App\Listeners\Loan;

use App\Events\Loan\LoanDueChecked;
use App\SmartMicro\Repositories\Contracts\LoanInterface;
use App\Traits\NextDueDate;

/**
 * Any loan whose next due date is this given date, calculate due principal and interest to pay.
 *
 * Class CalculateLoanPaymentDue
 * @package App\Listeners\Loan
 */
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
     * Fetch all loans whose next due date is today. Calculate their interest and principal to pay.
     * Finally update the next calculation date for the loan.
     *
     * @param  LoanDueChecked  $event
     * @return void
     */
    public function handle(LoanDueChecked $event)
    {
        $today = date('Y-m-d');
       // $today = '2020-02-05';
        $this->loanRepository->calculateLoanRepaymentDue($today);
    }
}
