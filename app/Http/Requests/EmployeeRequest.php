<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 11:11
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class EmployeeRequest extends BaseRequest
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
                        'first_name'            => 'required|min:2',
                        'email'                 => 'required|unique:users,email,NULL,uuid,deleted_at,NULL',
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'name'              => 'min:2',
                        'email'             => ['email', Rule::unique('users')->ignore($this->user, 'uuid')
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