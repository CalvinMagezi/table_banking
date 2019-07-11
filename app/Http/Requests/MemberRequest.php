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
                        'county'                => '',
                        'city'                  => '',
                        'national_id_image'     => '',
                        'id_number'             => 'required',
                        'passport_number'       => '',
                        'phone'                 => 'required',
                        'email'                 => 'email',
                        'postal_address'        => '',
                        'residential_address'   => '',
                        'bank_name'             => '',
                        'bank_account'          => '',
                        'bank_branch'           => '',
                        'status_id'             => '',
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