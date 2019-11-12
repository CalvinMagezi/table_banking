<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:17
 */

namespace App\Http\Resources;

use App\SmartMicro\Repositories\Eloquent\LoanRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'branch_id'             => $this->branch_id,

            'loan_reference_number' => $this->loan_reference_number,
            'loan_application_id'   => $this->loan_application_id,
            'member_id'             => $this->member_id,
            'loan_type_id'          => $this->loan_type_id,
            'loanType'              => $this->loanType,

            'balance'               => $this->balance,
            'paid_amount'           => $this->paid_amount,

            'member'                => MemberResource::make($this->member),

            'interest_rate'         => $this->interest_rate,
            'interest_type_id'      => $this->interest_type_id,
          //  'interestType'      => $this->interestType,
            'repayment_period'      => $this->repayment_period,
            'loan_status_id'        => $this->loan_status_id,
            'approved_by_user_id'   => $this->approved_by_user_id,
            'amount_approved'       => $this->amount_approved,
            'service_fee'           => $this->service_fee,

            // penalties
            'penalty_type_id'       => $this->penalty_type_id,
            'penalty_value'         => $this->penalty_value,
            'penalty_frequency_id'  => $this->penalty_frequency_id,

            'loan_disbursed'        => $this->loan_disbursed,
            'start_date'            => $this->start_date,
            'end_date'              => $this->end_date,
            'payment_frequency_id'  => $this->payment_frequency_id,
            'paymentFrequency'      => $this->paymentFrequency,
            /*'total_installments'    => $this->total_installments,
            'service_fee'           => $this->service_fee,
            'other_charges'         => $this->other_charges,
            'date_approved'         => $this->date_approved,*/
            'next_repayment_date'   => $this->next_repayment_date,

            'amortization'          => $this->amortization,

            'created_by'            => $this->created_by,
            'updated_by'            => $this->updated_by,

            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
