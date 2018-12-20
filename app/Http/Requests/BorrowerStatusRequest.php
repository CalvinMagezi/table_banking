<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:51
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BorrowerStatusRequest extends BaseRequest
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
                        'borrower_status_name'          => 'required|unique:borrower_statuses,borrower_status_name,NULL,uuid,deleted_at,NULL',
                        'borrower_status_description'   => ''
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'borrower_status_name'                 => ['borrower_status_name', Rule::unique('borrower_statuses')->ignore($this->borrower_status, 'uuid')
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