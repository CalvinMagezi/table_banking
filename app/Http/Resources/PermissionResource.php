<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 21:50
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'uuid'                      => $this->uuid,
            'permission_name'           => $this->permission_name,
            'permission_display_name'   => $this->permission_display_name,
            'permission_description'    => $this->permission_description,

            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
        ];
    }
}
