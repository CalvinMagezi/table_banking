<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/12/2018
 * Time: 11:13
 */

namespace App\Http\Requests;

class PaymentRequest extends BaseRequest
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
                        'branch_id'         => 'exists:branches,id',
                        'member_id'        => 'required|exists:members,id',
                        'amount'            => 'required|numeric|min:0|not_in:0',
                        'method_id'         => 'required|exists:payment_methods,id',
                        'transaction_id'    => '',
                       // 'payment_date'      => 'required|date_format:"DD-MM-YYYY"',
                        'payment_date'      => 'required',
                        'receipt_number'    => '',
                        'attachment'        => '',
                        'notes'             => ''
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'branch_id'         => 'exists:branches,id',
                        'member_id'         => 'required|exists:members,id',
                        'amount'            => 'required|numeric|min:0|not_in:0',
                        'method_id'         => 'required|exists:payment_methods,id',
                        'transaction_id'    => '',
                        'payment_date'      => 'required',
                        'receipt_number'    => '',
                        'attachment'        => '',
                        'notes'             => ''
                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}