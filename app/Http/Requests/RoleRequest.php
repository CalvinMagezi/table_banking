<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RoleRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
                break;
            }
            case 'POST':
            {
                $rules = [

                    'role_name'         => 'required|unique:roles,role_name,NULL,uuid,deleted_at,NULL',
                    'role_display_name' => 'required|unique:roles,role_display_name,NULL,uuid,deleted_at,NULL',
                    'role_description'  =>''
                ];

                break;
            }
            case 'PUT':
            case 'PATCH':
            {
                $rules = [
                    'role_name'                 => ['role_name', Rule::unique('roles')->ignore($this->role, 'uuid')
                        ->where(function ($query) {
                            $query->where('deleted_at', NULL);
                        })],

                    'role_display_name'                 => ['role_display_name', Rule::unique('roles')->ignore($this->role, 'uuid')
                        ->where(function ($query) {
                            $query->where('deleted_at', NULL);
                        })],
                ];
                break;
            }
            default:break;
        }

        return $rules;

    }
}