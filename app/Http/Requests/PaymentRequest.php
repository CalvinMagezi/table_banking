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
                        'loan_id'           => 'required',
                        'payment_amount'    => 'required',
                        'payment_method_id' => 'required',
                        'payment_date'      => 'required',
                        'paid_to'           => '',
                        'receipt_number'    => '',
                        'attachment'        => '',
                        'payment_notes'     => ''
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