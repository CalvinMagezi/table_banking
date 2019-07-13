<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 11/07/2019
 * Time: 13:24
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            'id'                    => $this->id,
            'member_id'             => $this->member_id,
            'asset_number'          => $this->asset_number,
            'title'                  => $this->title,
            'description'           => $this->description,
            'valuation_date'        => $this->valuation_date,
            'valued_by'             => $this->valued_by,
            'valuer_phone'          => $this->valuer_phone,
            'valuation_amount'      => $this->valuation_amount,
            'location'              => $this->location,
            'registration_number'   => $this->registration_number,
            'registered_to'         => $this->registered_to,
            'condition'             => $this->condition,
            'notes'                 => $this->notes,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
