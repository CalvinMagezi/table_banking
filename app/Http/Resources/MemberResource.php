<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 11:17
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'first_name'            => $this->first_name,
            'middle_name'           => $this->middle_name,
            'last_name'             => $this->last_name,
            'nationality'           => $this->nationality,
            'county'                => $this->county,
            'city'                  => $this->city,
            'id_image'              => $this->id_image,
            'id_number'             => $this->id_number,
            'passport_number'       => $this->passport_number,
            'phone'                 => $this->phone,
            'email'                 => $this->email,
            'postal_address'        => $this->postal_address,
            'residential_address'   => $this->residential_address,
            'bank_name'             => $this->bank_name,
            'bank_account'          => $this->bank_account,
            'bank_branch'           => $this->bank_branch,

            'status_id'             => $this->status_id,
            'passport_photo'        => $this->passport_photo,

            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
