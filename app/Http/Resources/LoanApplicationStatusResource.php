<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:45
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationStatusResource extends JsonResource
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
            'uuid'                                  => $this->uuid,
            'loan_application_status_name'          => $this->loan_application_status_name,
            'loan_application_status_description'   => $this->middle_name,

            'created_at'                            => $this->created_at,
            'updated_at'                            => $this->updated_at,
        ];
    }
}
