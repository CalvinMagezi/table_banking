<?php
/**
 * Created by PhpStorm.
 * Loan: kevin
 * Date: 26/10/2018
 * Time: 12:18
 */

namespace App\Http\Controllers\Api;

use App\Events\Loan\LoanDueChecked;
use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use App\SmartMicro\Repositories\Contracts\FinanceStatementInterface;
use App\SmartMicro\Repositories\Contracts\InterestTypeInterface;
use App\SmartMicro\Repositories\Contracts\JournalInterface;
use App\SmartMicro\Repositories\Contracts\LoanApplicationInterface;
use App\SmartMicro\Repositories\Contracts\LoanInterestRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\LoanInterface;

use App\SmartMicro\Repositories\Contracts\LoanPrincipalRepaymentInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController  extends ApiController
{
    /**
     * @var LoanInterface
     */
    protected $loanRepository, $loanApplicationRepository, $interestTypeRepository,
        $journalRepository, $load, $loanInterestRepayment, $loanPrincipalRepayment, $financeStatement;

    /**
     * LoanController constructor.
     * @param LoanInterface $loanInterface
     * @param LoanApplicationInterface $loanApplicationInterface
     * @param JournalInterface $journalInterface
     * @param LoanInterestRepaymentInterface $loanInterestRepayment
     * @param LoanPrincipalRepaymentInterface $loanPrincipalRepayment
     * @param InterestTypeInterface $interestTypeRepository
     */
    public function __construct(LoanInterface $loanInterface, LoanApplicationInterface $loanApplicationInterface,
                                JournalInterface $journalInterface, LoanInterestRepaymentInterface $loanInterestRepayment,
    LoanPrincipalRepaymentInterface $loanPrincipalRepayment, InterestTypeInterface $interestTypeRepository, FinanceStatementInterface $financeStatement
    )
    {
        $this->loanRepository   = $loanInterface;
        $this->loanApplicationRepository   = $loanApplicationInterface;
        $this->journalRepository   = $journalInterface;

        $this->loanInterestRepayment   = $loanInterestRepayment;
        $this->loanPrincipalRepayment   = $loanPrincipalRepayment;
        $this->interestTypeRepository   = $interestTypeRepository;
        $this->financeStatement   = $financeStatement;

        $this->load = ['loanType', 'member', 'interestType', 'paymentFrequency'];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->loanRepository->listAll($this->formatFields($select));
        }
        $data = $this->loanRepository->getAllPaginate($this->load);

        $data->map(function($item) {
            $item['balance'] =  $this->formatMoney($item['amount_approved'] - $this->loanRepository->paidAmount($item['id']));
            $item['paid_amount'] =  $this->formatMoney($this->loanRepository->paidAmount($item['id']));
            return $item;
        });

        return $this->respondWithData(LoanResource::collection($data));
    }

    /**
     * @param LoanRequest $request
     * @return array|mixed
     * @throws \Exception
     */
    public function store(LoanRequest $request)
    {
        $user = auth('api')->user();
        $data = $request->all();

        // Transaction start
        DB::beginTransaction();
        try
        {
            // Create new Loan
            $newLoan = $this->loanRepository->create($request->all());

            // Update loan application as already reviewed
            if($user && $newLoan) {
                $updateData = [
                    'reviewed_by_user_id' => $user->id,
                    'approved_on' => Carbon::now(),
                    'rejected_on' => null,
                    'reviewed_on' => Carbon::now(),
                ];
                $this->loanApplicationRepository->update($updateData, $data['loan_application_id']);
            }

            // 1. Journal entry for the loan issue
            $this->journalRepository->loanDisburse($newLoan);

            if ( array_key_exists('service_fee', $data) && $data['service_fee'] > 0) {
                // 2.  Entry for Demand of service fee
                $this->journalRepository->serviceFeeDemand($newLoan);

                // 3. Entry for receiving of service fee
                $this->journalRepository->serviceFeeReceived($newLoan);
            }

            DB::commit();
            // Calculate loan dues immediately after loan is issued
            event(new LoanDueChecked());

            return $this->respondWithSuccess('Success !! Loan has been created.');

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
           // return $this->respondNotSaved($e);
        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $loan = $this->loanRepository->getById($uuid, $this->load);

        if(!$loan) {
            return $this->respondNotFound('Loan not found.');
        }

        $loanAmount = $loan->amount_approved;
        $totalPeriods = $loan->repayment_period;
        $rate = $loan->interest_rate;
        $startDate = $loan->start_date;
        $frequency = $loan->paymentFrequency->name;

        switch ($loan->interestType->name) {
            case 'reducing_balance':
                {
                    $amortization = $this->loanRepository
                        ->printReducingBalance($loanAmount, $totalPeriods, $rate, $startDate, $frequency);
                }
                break;
            case 'fixed':
                {
                   $amortization = $this->loanRepository
                       ->printFixedInterest($loanAmount, $totalPeriods, $rate, $startDate, $frequency);
                }
                break;
            default:
                {
                    $amortization = [];
                }
        }

        $loan['amortization'] = $amortization;
        return $this->respondWithData(new LoanResource($loan));
    }

    /**
     * @param LoanRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(LoanRequest $request, $uuid)
    {
        $save = $this->loanRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else
            return $this->respondWithSuccess('Success !! Loan has been updated.');
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        return $this->respondNotFound('Loan can not be deleted');
     /*   if($this->loanRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Loan has been deleted');
        }
        return $this->respondNotFound('Loan not deleted');*/
    }
}