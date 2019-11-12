<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:22
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LoanTypeRequest extends BaseRequest
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
                        'name'                  => 'required|unique:loan_types,name,NULL,id,deleted_at,NULL',
                        'description'           => '',
                        'active_status'         => '',
                        'interest_rate'         => '',
                        'interest_type_id'      => 'required|exists:interest_types,id',
                        'payment_frequency_id'  => 'required|exists:payment_frequencies,id',
                        'repayment_period'      => '',
                        'service_fee'           => '',

                        'penalty_type_id'       => 'exists:penalty_types,id',
                        'penalty_value'         => '',
                        'penalty_frequency_id'  => 'exists:penalty_frequencies,id'
                    ];
                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'name'                 => ['required', Rule::unique('loan_types')->ignore($this->loan_type, 'id')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],
                        'description'           => '',
                        'active_status'         => '',
                        'interest_rate'         => '',
                        'interest_type_id'      =>'required|exists:interest_types,id',
                        'payment_frequency_id'  => 'required|exists:payment_frequencies,id',
                        'repayment_period'      => '',
                        'service_fee'           => '',

                        'penalty_type_id'       => 'exists:penalty_types,id',
                        'penalty_value'         => '',
                        'penalty_frequency_id'  => 'exists:penalty_frequencies,id'
                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}