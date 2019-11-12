<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 03/06/2019
 * Time: 11:00
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralSettingResource extends JsonResource
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
            'business_name'         => $this->business_name,
            'business_type'         => $this->business_type,
            'email'                 => $this->email,
            'currency'              => $this->currency,
            'phone'                 => $this->phone,
            'country'               => $this->country,
            'county'                => $this->county,
            'town'                  => $this->town,
            'physical_address'      => $this->physical_address,
            'postal_address'        => $this->postal_address,
            'postal_code'           => $this->postal_code,
            'logo'                  => $this->logo,
            'favicon'               => $this->favicon,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,




        ];
    }
}