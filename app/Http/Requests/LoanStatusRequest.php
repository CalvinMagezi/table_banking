<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:32
 */

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class LoanStatusRequest extends BaseRequest
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
                        'loan_status_name'  => 'required|unique:loan_statuses,loan_status_name,NULL,uuid,deleted_at,NULL'
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'loan_status_name'             => ['loan_status_name', Rule::unique('loan_statuses')->ignore($this->user, 'uuid')
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