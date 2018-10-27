<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:51
 */

namespace App\Http\Requests;

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
                        'borrower_status_name'          => 'required',
                        'borrower_status_description'   => 'required'
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