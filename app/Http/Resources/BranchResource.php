<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 21:38
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'branch_name'       => $this->branch_name,
            'branch_location'   => $this->branch_location,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
