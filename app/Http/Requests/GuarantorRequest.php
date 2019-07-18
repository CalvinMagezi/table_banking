<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:39
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class GuarantorRequest extends BaseRequest
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
                        'member_id'             => 'required|unique:guarantors,member_id,NULL,id,deleted_at,NULL',
                        'loan_application_id'   => 'required',
                        'assign_date'           => 'required',
                        'guarantee_amount'      => 'required'
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'member_id'             => [Rule::unique('guarantors')->ignore($this->guarantor, 'id')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],
                        'loan_application_id'   => 'required',
                        'assign_date'           => 'required',
                        'guarantee_amount'      => 'required',
                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}