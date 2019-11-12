<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 02/08/2019
 * Time: 10:41
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'branch_id'         => $this->branch_id,
            // 'accountBalance'    => AccountBalanceResource::make($this->accountBalance),
            'accountBalance'    => $this->accountBalance,

           /* 'member'            => $this->member,
            'branch'            => $this->branch,
            'payments'          => $this->payments,
            'loans'             => $this->loans,*/
            'account_number'    => $this->account_number,
            'account_code'      => $this->account_code,
            'account_name'      => $this->account_name,
            'member'      => $this->member,
            'account_type_id'   => $this->account_type_id,
            'accountType'   => AccountTypeResource::make($this->accountType),
            'account_status_id' => $this->account_status_id,
            'other_details'     => $this->other_details,
            'closed_on'         => $this->closed_on,

            'statement'         => $this->statement,

            'created_by'        => $this->created_by,
            'updated_by'        => $this->updated_by,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at
        ];
    }
}
