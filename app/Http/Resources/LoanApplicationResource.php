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
            'uuid'                  => $this->uuid,

            'member_id'             => $this->member_id,
            'reviewed_by_user_id'   => $this->reviewed_by_user_id,
            'approved_by_user_id'   => $this->approved_by_user_id,
            'application_date'      => $this->application_date,
            'amount_applied'        => $this->amount_applied,
            'repayment_period'      => $this->repayment_period,
            'date_approved'         => $this->date_approved,
            'loan_status_id'        => $this->loan_status_id,

            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
