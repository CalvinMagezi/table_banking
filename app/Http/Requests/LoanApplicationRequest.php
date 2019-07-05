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
                        'reviewed_by_user_id'   => '',
                        'approved_by_user_id'   => '',
                        'application_date'      => 'required',
                        'amount_applied'        => 'required',
                        'repayment_period'      => '',
                        'date_approved'         => '',
                        'application_notes'     => '',
                        'status_id'             => '',
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'member_id'             => 'required',
                        'reviewed_by_user_id'   => '',
                        'approved_by_user_id'   => '',
                        'application_date'      => '',
                        'amount_applied'        => '',
                        'repayment_period'      => '',
                        'date_approved'         => '',
                        'application_notes'     => '',
                        'status_id'             => '',

                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}