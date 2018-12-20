<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 11:17
 */

namespace App\Http\Requests;

class MemberRequest extends BaseRequest
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
                        'first_name'            => 'required',
                        'middle_name'           => 'required',
                        'last_name'             => 'required',
                        'nationality'           => 'required',
                        'national_id_image'     => '',
                        'national_id_number'    => 'required',
                        'passport_number'       => 'required',
                        'telephone_number'      => 'required',
                        'email'                 => 'required|email',
                        'postal_address'        => 'required',
                        'residential_address'   => 'required',
                        'bank_name'             => 'required',
                        'bank_account'          => 'required',
                        'bank_branch'           => 'required',
                        'members_status_id'     => 'required',
                        'passport_photo'        => ''

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