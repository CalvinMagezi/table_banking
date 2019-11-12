<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 10:49
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanPenaltyResource extends JsonResource
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
            'id' => $this->id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
