<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:18
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LoanRequest extends BaseRequest
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
                        'borrower_id'             => 'required',
                        'approved_by_user_id'   => '',
                        'loan_reference'        => 'required',
                        'amount_applied'        => 'required',
                        'amount_approved'       => 'required',
                        'amount_received'       => 'required',
                        'date_approved'         => 'required',
                        'due_date'              => 'required',
                        'loan_status_id'           => '',
                        'loan_application_id'        => 'required',
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