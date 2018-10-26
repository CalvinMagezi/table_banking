<?php

namespace App\Http\Requests;

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
                    'role_name'         =>'required',
                    'role_display_name' =>'required',
                    'role_description'  =>'required'

                ];

                break;
            }
            case 'PUT':
            case 'PATCH':
            {
                $rules = [

                ];
                break;
            }
            default:break;
        }

        return $rules;

    }
}