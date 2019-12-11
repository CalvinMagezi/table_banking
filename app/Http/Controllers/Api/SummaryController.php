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
use App\SmartMicro\Repositories\Contracts\UserInterface;
use Illuminate\Http\Request;

class SummaryController extends ApiController
{
    protected $branchRepository, $userRepository, $load, $loanRepository, $loanApplicationRepository,
        $paymentsRepo, $loanPrincipalRepaymentInterface;

    /**
     * SummaryController constructor.
     * @param BranchInterface $branchRepository
     * @param UserInterface $userRepository
     * @param LoanInterface $loanRepository
     * @param LoanApplicationInterface $loanApplicationRepository
     * @param LoanPrincipalRepaymentInterface $loanPrincipalRepaymentInterface
     * @param PaymentInterface $paymentsRepo
     */
    public function __construct(BranchInterface $branchRepository, UserInterface $userRepository, LoanInterface $loanRepository,
                                LoanApplicationInterface $loanApplicationRepository,
                                LoanPrincipalRepaymentInterface $loanPrincipalRepaymentInterface,
                                PaymentInterface $paymentsRepo)
    {
        $this->branchRepository = $branchRepository;
        $this->userRepository = $userRepository;
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
        //TODO branches scope when viewing members


        // branches summary
        $branches = $this->branchRepository->getAll();
        $users = $this->userRepository->getAll();
        $branchesCount = count($branches);

        $branchId = auth('api')->user()->branch_id;

        $data = [];

                $x = new \stdClass();
                $x = [];

                $currentBranch = $this->branchRepository->getById($branchId, $this->load);
           //     $currentBranch = $this->branchRepository->getById('d50dcd86-7930-4dc9-8c82-55f08b6b3b1c', $this->load);

                // Admin only
                $x['count_branches'] = count($branches);
                $x['current_branch'] = $currentBranch;
                $x['count_users'] = count($users);

                $x['count_assets'] = count($currentBranch->assets);
                $x['count_employees'] = count($currentBranch->employees);
                $x['count_loans'] = count($currentBranch->loans);
                $x['count_loan_applications'] = count($currentBranch->loanApplications);
                $x['count_members'] = count($currentBranch->members);
                $x['count_payments'] = count($currentBranch->payments);

                // active loans
                $activeLoans = $this->loanRepository->getActiveLoansPerBranch($currentBranch->id);
                $x['active_loans'] = $activeLoans;
                $x['count_loans'] = count($activeLoans);
                $x['loans_sum'] = $this->formatMoney($this->loanRepository->getSum('amount_approved'));

                // For all branches
                // All loans
                $allActiveLoans = $this->loanRepository->getAllActiveLoans();
                $x['count_loans'] = count($allActiveLoans);
                $x['loans_sum'] = $this->formatMoney($allActiveLoans->sum('amount_approved'));





        // todo total amount of loans paid
                // todo total loans balance

                $today = date('Y-m-d');

                // Loans due today
                $loanDueToday = $this->loanRepository->dueOnDate($today);
                $x['loans_due_today'] = $loanDueToday;
                $x['count_loans_due_today'] = count($loanDueToday);

                // Overdue Loans
                $loansOverDue = $this->loanRepository->overDue();
                $x['loans_over_due'] = $loansOverDue;
                $x['count_loans_over_due'] = count($loansOverDue);

                // Total loan amount overdue
                $total = 0;
                foreach ($loansOverDue as $loan){
                    $total = $total + $loan->totalDue;
                }
                $x['total_amount_over_due'] = $this->formatMoney($total);

                // loan Applications
                $pendingApplications = $this->loanApplicationRepository->getAllPending(['member', 'loanType']);
                $x['pending_applications'] = $pendingApplications;
                $x['count_pending_applications'] = count($pendingApplications);
              //  $x['applications_sum'] = $this->formatMoney($this->loanApplicationRepository->getSum('amount_applied'));
                $x['applications_sum'] = $this->formatMoney($pendingApplications->sum('amount_applied'));

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