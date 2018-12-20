<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:17
 */

namespace App\Http\Resources;

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
            'uuid'                  => $this->uuid,

            'borrower_id'             => $this->borrower_id,
            'approved_by_user_id'   => $this->approved_by_user_id,
            'loan_reference'        => $this->loan_reference,
            'amount_applied'        => $this->amount_applied,
            'amount_approved'       => $this->amount_approved,
            'amount_received'       => $this->amount_received,
            'date_approved'         => $this->date_approved,
            'due_date'              => $this->due_date,
            'loan_status_id'        => $this->loan_status_id,
            'loan_application_id'   => $this->loan_application_id,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
