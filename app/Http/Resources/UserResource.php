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
            'id'                => $this->id,
            'role_id'           => $this->role_id,
            // 'role'              => $this->role,
            'role'              => RoleResource::make($this->role),
            'employee_id'       => $this->employee_id,
           // 'employee'          => $this->employee,
           // 'employee'          => EmployeeResource::collection($this->employee),
            'employee'          => EmployeeResource::make($this->employee),
            'email'             => $this->email,
            'confirmed'         => $this->confirmed,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
