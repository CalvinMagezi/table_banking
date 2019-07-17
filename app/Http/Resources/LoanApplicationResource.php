<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:26
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
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
            'id'                            => $this->id,

            'member_id'                     => $this->member_id,
            'loan_type_id'                  => $this->loan_type_id,
            'interest_rate'                 => $this->interest_rate,
            'repayment_period'              => $this->repayment_period,
            'amount_applied'                => $this->amount_applied,
            'monthly_payments'              => $this->monthly_payments,
            'application_date'              => $this->application_date,

            'disburse_method_id'            => $this->disburse_method_id,
            'mpesa_number'                  => $this->mpesa_number,
            'bank_name'                     => $this->bank_name,
            'bank_branch'                   => $this->bank_branch,
            'bank_account'                  => $this->bank_account,
            'other_banking_details'         => $this->other_banking_details,

            'witness_type_id'               => $this->witness_type_id,
            'witness_first_name'            => $this->witness_first_name,
            'witness_last_name'             => $this->witness_last_name,
            'witness_country'               => $this->witness_country,
            'witness_county'                => $this->witness_county,
            'witness_city'                  => $this->witness_city,
            'witness_national_id'           => $this->witness_national_id,
            'witness_phone'                 => $this->witness_phone,
            'witness_email'                 => $this->witness_email,
            'witness_postal_address'        => $this->witness_postal_address,
            'witness_residential_address'   => $this->witness_residential_address,

            'status_id'                     => $this->status_id,
            'witnessed_by_user_id'          => $this->witnessed_by_user_id,
            'approved_by_user_id'           => $this->approved_by_user_id,
            'attach_application_form'       => $this->attach_application_form,

            'created_at'                    => $this->created_at,
            'updated_at'                    => $this->updated_at,
        ];
    }
}
