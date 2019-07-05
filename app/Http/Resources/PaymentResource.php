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
            'loan_id'           => $this->loan_id,
            'amount'            => $this->amount,
            'method_id'         => $this->method_id,
            'date'              => $this->date,
            'paid_to'           => $this->paid_to,
            'receipt_number'    => $this->receipt_number,
            'attachment'        => $this->attachment,
            'notes'             => $this->notes,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at
        ];
    }
}
