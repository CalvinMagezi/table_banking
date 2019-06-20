<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:32
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanStatusResource extends JsonResource
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
            'loan_status_name'  => $this->loan_status_name,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
