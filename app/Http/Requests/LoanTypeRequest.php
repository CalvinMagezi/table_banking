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
                        'loan_type_name'        => 'required|unique:loan_types,loan_type_name,NULL,uuid,deleted_at,NULL',
                        'loan_type_description' => '',
                        'max_loan_period'       => '',
                        'status'                => '',
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'loan_type_name'                 => ['loan_type_name', Rule::unique('loan_types')->ignore($this->user, 'uuid')
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