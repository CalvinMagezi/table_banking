<?php


namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
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
                        'first_name'            => 'required',
                        'last_name'             => '',
                        'role_id'               => 'required|exists:roles,id',
                        'employee_id'           => 'required|exists:employees,id|unique:users,employee_id,NULL,id,deleted_at,NULL',
                        'email'                 => 'required|unique:users,email,NULL,id,deleted_at,NULL',
                        'password'              => 'required|min:3|confirmed',
                        'password_confirmation' => 'required_with:password'
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'first_name'            => '',
                        'last_name'             => '',
                        'role_id'               => 'exists:roles,id',
                        'email'                 => ['email', Rule::unique('users')->ignore($this->user, 'id')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],

                        'employee_id'                 => ['employee_id|exists:employees,id', Rule::unique('employees')->ignore($this->user, 'id')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],

                        'password'              => 'min:3|confirmed',
                        'password_confirmation' => 'required_with:password'

                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}