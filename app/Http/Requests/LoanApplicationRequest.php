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
                        'member_id'                     => 'required',
                        'loan_type_id'                  => 'required',
                        'interest_rate'                 => '',
                        'repayment_period'              => '',
                        'amount_applied'                => 'required',
                        'monthly_payments'              => '',
                        'application_date'              => 'required',
                        'disburse_method_id'            => '',
                        'mpesa_number'                  => '',
                        'bank_name'                     => '',
                        'bank_branch'                   => '',
                        'bank_account'                  => '',
                        'other_banking_details'         => '',
                        'witness_type_id'               => '',
                        'witness_first_name'            => '',
                        'witness_last_name'             => '',
                        'witness_country'               => '',
                        'witness_county'                => '',
                        'witness_city'                  => '',
                        'witness_national_id'           => '',
                        'witness_phone'                 => '',
                        'witness_email'                 => '',
                        'witness_postal_address'        => '',
                        'witness_residential_address'   => '',
                        'status_id'                     => '',
                        'witnessed_by_user_id'          => '',
                        'approved_by_user_id'           => '',
                        'attach_application_form'       => ''
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'member_id'                     => 'required',
                        'loan_type_id'                  => 'required',
                        'interest_rate'                 => '',
                        'repayment_period'              => '',
                        'amount_applied'                => 'required',
                        'monthly_payments'              => '',
                        'application_date'              => 'required',
                        'disburse_method_id'            => '',
                        'mpesa_number'                  => '',
                        'bank_name'                     => '',
                        'bank_branch'                   => '',
                        'bank_account'                  => '',
                        'other_banking_details'         => '',
                        'witness_type_id'               => '',
                        'witness_first_name'            => '',
                        'witness_last_name'             => '',
                        'witness_country'               => '',
                        'witness_county'                => '',
                        'witness_city'                  => '',
                        'witness_national_id'           => '',
                        'witness_phone'                 => '',
                        'witness_email'                 => '',
                        'witness_postal_address'        => '',
                        'witness_residential_address'   => '',
                        'status_id'                     => '',
                        'witnessed_by_user_id'          => '',
                        'approved_by_user_id'           => '',
                        'attach_application_form'       => ''
                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}