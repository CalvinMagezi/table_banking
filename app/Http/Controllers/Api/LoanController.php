<?php
/**
 * Created by PhpStorm.
 * Loan: kevin
 * Date: 26/10/2018
 * Time: 12:18
 */

namespace App\Http\Controllers\Api;

use App\Events\Loan\LoanDueChecked;
use App\Events\Payment\PaidLoan;
use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use App\Notifications\PaymentReceivedNotification;
use App\Notifications\PaymentReceivedSms;
use App\SmartMicro\Repositories\Contracts\FinanceStatementInterface;
use App\SmartMicro\Repositories\Contracts\InterestTypeInterface;
use App\SmartMicro\Repositories\Contracts\JournalInterface;
use App\SmartMicro\Repositories\Contracts\LoanApplicationInterface;
use App\SmartMicro\Repositories\Contracts\LoanInterestRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\LoanInterface;

use App\SmartMicro\Repositories\Contracts\LoanPrincipalRepaymentInterface;
use App\SmartMicro\Repositories\Contracts\MemberInterface;
use App\SmartMicro\Repositories\Contracts\SmsSendInterface;
use App\Traits\CommunicationMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LoanController  extends ApiController
{
    /**
     * @var LoanInterface
     */
    protected $loanRepository, $loanApplicationRepository, $interestTypeRepository, $memberRepository,
        $journalRepository, $load, $loanInterestRepayment, $loanPrincipalRepayment, $financeStatement, $smsSend;

    /**
     * LoanController constructor.
     * @param LoanInterface $loanInterface
     * @param LoanApplicationInterface $loanApplicationInterface
     * @param JournalInterface $journalInterface
     * @param LoanInterestRepaymentInterface $loanInterestRepayment
     * @param LoanPrincipalRepaymentInterface $loanPrincipalRepayment
     * @param InterestTypeInterface $interestTypeRepository
     * @param FinanceStatementInterface $financeStatement
     * @param SmsSendInterface $smsSend
     * @param MemberInterface $memberRepository
     */
    public function __construct(LoanInterface $loanInterface, LoanApplicationInterface $loanApplicationInterface,
                                JournalInterface $journalInterface, LoanInterestRepaymentInterface $loanInterestRepayment,
    LoanPrincipalRepaymentInterface $loanPrincipalRepayment, InterestTypeInterface $interestTypeRepository,
                                FinanceStatementInterface $financeStatement, SmsSendInterface $smsSend, MemberInterface $memberRepository
    )
    {
        $this->loanRepository   = $loanInterface;
        $this->loanApplicationRepository   = $loanApplicationInterface;
        $this->journalRepository   = $journalInterface;

        $this->loanInterestRepayment   = $loanInterestRepayment;
        $this->loanPrincipalRepayment   = $loanPrincipalRepayment;
        $this->interestTypeRepository   = $interestTypeRepository;
        $this->financeStatement   = $financeStatement;
        $this->smsSend   = $smsSend;
        $this->memberRepository   = $memberRepository;

        $this->load = ['loanType', 'member', 'interestType', 'paymentFrequency', 'loanOfficer'];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
      //  $user = auth()->user();

       // $user->notify(new PaymentReceivedNotification());

      //  $user->email = 'gikure.mungai@gmail.com';

       // return $this->smsSend->send('+254724475357', 'hallo there');

      //  Notification::send($user, new PaymentReceivedSms($user));
      // Notification::send($user, new PaymentReceivedNotification($user));

        // event(new PaidLoan('2a0822f4-d44f-4598-a389-e5721d0c6e78'));


        // $loan = $this->loanRepository->getActiveLoan('d814720e-1377-4459-bec8-ea2fe2fae8d6', 'paymentFrequency');

        //return $loan;

       // return $this->loanRepository->memberLoans('3cc71001-ebb8-49d3-b407-e7e993081678');
       // return $this->loanRepository->pendingPenalty('52177b38-2dbf-4118-ad94-4dedfb61a079');
      //  return $this->loanRepository->overDue();
      //  return $this->loanRepository->dueOnDate();
      //  return $this->loanRepository->dueLoans();
     //   return $this->loanRepository->dueLoans();
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

            // New loan email / sms
            $member = $this->memberRepository->getWhere('id', $newLoan['member_id']);
            if(!is_null($member) && !is_null($newLoan))
                CommunicationMessage::send('loan_application_approved', $member, $newLoan);

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

        if(!is_null($save) && $save['error']){
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