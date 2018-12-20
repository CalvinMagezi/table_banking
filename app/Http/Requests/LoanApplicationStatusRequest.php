<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:45
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LoanApplicationStatusRequest extends BaseRequest
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
                        'loan_application_status_name'                 => 'required|unique:loan_application_statuses,loan_application_status_name,NULL,uuid,deleted_at,NULL',
                        'loan_application_status_description'   => '',
                        ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'loan_application_status_name'                 => ['loan_application_status_name', Rule::unique('loan_application_statuses')->ignore($this->loan_application_status, 'uuid')
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