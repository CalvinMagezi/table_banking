<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 26/09/2019
 * Time: 13:11
 */

namespace App\Http\Controllers\Api;

use App\Models\FailedLogin;
use App\Models\Loan;
use App\Models\LoanPrincipalRepayment;
use App\Models\LoginEvent;
use App\SmartMicro\Repositories\Contracts\BranchInterface;
use App\SmartMicro\Repositories\Contracts\LoanApplicationInterface;
use App\SmartMicro\Repositories\Contracts\LoanInterface;
use App\SmartMicro\Repositories\Contracts\LoanPrincipalRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\PaymentInterface;
use Illuminate\Http\Request;

class SummaryController extends ApiController
{
    protected $branchRepository, $load, $loanRepository, $loanApplicationRepository,
        $paymentsRepo, $loanPrincipalRepaymentInterface;

    /**
     * SummaryController constructor.
     * @param BranchInterface $branchRepository
     * @param LoanInterface $loanRepository
     * @param LoanApplicationInterface $loanApplicationRepository
     * @param LoanPrincipalRepaymentInterface $loanPrincipalRepaymentInterface
     * @param PaymentInterface $paymentsRepo
     */
    public function __construct(BranchInterface $branchRepository, LoanInterface $loanRepository,
                                LoanApplicationInterface $loanApplicationRepository,
                                LoanPrincipalRepaymentInterface $loanPrincipalRepaymentInterface,
                                PaymentInterface $paymentsRepo)
    {
        $this->branchRepository = $branchRepository;
        $this->loanRepository = $loanRepository;
        $this->loanApplicationRepository = $loanApplicationRepository;
        $this->paymentsRepo = $paymentsRepo;
        $this->loanPrincipalRepaymentInterface = $loanPrincipalRepaymentInterface;

        $this->load = ['assets', 'employees', 'loans', 'loanApplications', 'members', 'payments', 'users'];

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        // branches summary
        $branches = $this->branchRepository->getAll();
        $branchesCount = count($branches);

        $branchId = auth('api')->user()->branch_id;

        $data = [];

                $x = new \stdClass();
                $x = [];

                $currentBranch = $this->branchRepository->getById($branchId, $this->load);
           //     $currentBranch = $this->branchRepository->getById('d50dcd86-7930-4dc9-8c82-55f08b6b3b1c', $this->load);
                $x['current_branch'] = $currentBranch;

                $x['count_assets'] = count($currentBranch->assets);
                $x['count_employees'] = count($currentBranch->employees);
                $x['count_loans'] = count($currentBranch->loans);
                $x['count_loanApplications'] = count($currentBranch->loanApplications);
                $x['count_members'] = count($currentBranch->members);
                $x['count_users'] = count($currentBranch->users);
                $x['count_payments'] = count($currentBranch->payments);

                // active loans
                $activeLoans = $this->loanRepository->getActiveLoansPerBranch($currentBranch->id);
                $x['active_loans'] = $activeLoans;
                $x['count_loans'] = count($activeLoans);
                $x['loans_sum'] = $this->formatMoney($this->loanRepository->getSum('amount_approved'));

                foreach ($activeLoans as $loan){
                    if(!is_null($loan)){
                        // fetch paid amount from loan_principal_repayments
                        $amountPaid = LoanPrincipalRepayment::where('loan_id', $loan->id)->sum('amount');
                    }
                }
                // todo total amount of loans paid
                // todo total loans balance

                $today = date('Y-m-d');

                // Loans due today
                $paymentsDueToday = LoanPrincipalRepayment::where('due_date', '=', $today)->get('loan_id');
                $x['payments_due_today'] = $paymentsDueToday;
                $x['loans_due_today'] = $this->loanRepository->getByIds($paymentsDueToday);

                // Overdue Loans
                $paymentsOverDue = LoanPrincipalRepayment::where('due_date', '<', $today)
                    ->where('paid_on', null)->get('loan_id');
                $x['payments_over_due'] = $paymentsOverDue;
                $x['loans_over_due'] = $this->loanRepository->getByIds($paymentsOverDue);
                // todo total overdue loans
                // todo count overdue loans

                // loan Applications
                $pendingApplications = $this->loanApplicationRepository->getAll(['member', 'loanType']);
                $x['pending_applications'] = $pendingApplications;
                $x['count_pending_applications'] = count($pendingApplications);
                $x['applications_sum'] = $this->formatMoney($this->loanApplicationRepository->getSum('amount_applied'));

                // maybe all records for the last 24 hrs ?
                // latest 5 payments
                $x['latest_payments'] = $this->paymentsRepo->getLatest(5, ['member', 'paymentMethod']);

                // todo latest 5 logins - out
                $latestLogins = LoginEvent::latest()->limit(5)->get();
                // todo latest 5 failed login attempts
                $latestFailedLogins = FailedLogin::latest()->limit(5)->get();

                // Finance
                // Todo -  balance -  // bank account ?? accounts

                // todo scheduler - Loan calculations - loan number, interest due, principal due, as calculated this week/today?
                // fetch latest records from loan_principal_repayments table

                // todo communication - sms sent, email sent

               // $data[] = $x;

              //  dd($data);
               // $members = $this->branchRepository->members();

          return $x;
    }
}