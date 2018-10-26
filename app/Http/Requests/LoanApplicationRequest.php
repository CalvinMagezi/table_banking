<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:26
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LoanApplicationRequest extends BaseRequest
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
                        'member_id'             => 'required',
                        'reviewed_by_user_id'   => 'required',
                        'approved_by_user_id'   => 'required',
                        'application_date'      => 'required',
                        'amount_applied'        => 'required',
                        'repayment_period'      => 'required',
                        'date_approved'         => 'required',
                        'loan_status_id'        => 'required',
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