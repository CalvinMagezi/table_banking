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
                        'name'         => 'required|unique:roles,name,NULL,uuid,deleted_at,NULL',
                        'display_name' => 'required|unique:roles,display_name,NULL,uuid,deleted_at,NULL',
                        'description'  => ''
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'name'                 => ['required', Rule::unique('roles')->ignore($this->role, 'uuid')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],

                        'display_name'                 => ['required', Rule::unique('roles')->ignore($this->role, 'uuid')
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