<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:10
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowerResource extends JsonResource
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
            'member_id'             => $this->member_id,
            'credit_score'          => $this->credit_score,
            'borrower_status_id'    => $this->borrower_status_id,
            'spouse_type'           => $this->spouse_type,
            'spouse_name'           => $this->spouse_name,
            'spouse_id_number'      => $this->spouse_id_number,
            'spouse_phone'          => $this->spouse_phone,
            'spouse_address'        => $this->spouse_address,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
