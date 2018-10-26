<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'uuid'              => $this->uuid,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'salutation'        => $this->salutation,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'profile_picture'   => $this->profile_picture,
            'country'           => $this->country,
            'state'             => $this->state,
            'city'              => $this->city,
            'postal_code'       => $this->postal_code,
            'confirmed'         => (bool) $this->confirmed,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
