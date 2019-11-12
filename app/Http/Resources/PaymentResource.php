<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/12/2018
 * Time: 11:12
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'id'                => $this->id,
            'branch_id'         => $this->branch_id,
            'branch'            => $this->branch,

            'member_id'         => $this->member_id,
            'amount'            => $this->amount,
            'method_id'         => $this->method_id,
            'transaction_id'    => $this->transaction_id,
            'payment_date'      => $this->payment_date,
            'receipt_number'    => $this->receipt_number,
            'attachment'        => $this->attachment,
            'notes'             => $this->notes,

            'cheque_number'     => $this->cheque_number,
            'bank_name'         => $this->bank_name,
            'bank_branch'       => $this->bank_branch,
            'cheque_date'       => $this->cheque_date,

            'member'            => $this->member,
            'paymentMethod'     => $this->paymentMethod,

            'created_by'        => $this->created_by,
            'updated_by'        => $this->updated_by,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at
        ];
    }
}
