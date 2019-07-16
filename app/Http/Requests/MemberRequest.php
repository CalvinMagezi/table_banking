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
                        'branch_id'             => 'required',
                        'first_name'            => 'required',
                        'middle_name'           => 'required',
                        'last_name'             => '',
                        'date_of_birth'         => 'required',
                        'date_became_member'    => 'required',
                        'nationality'           => '',
                        'county'                => '',
                        'city'                  => '',
                        'national_id_image'     => '',
                        'id_number'             => '',
                        'passport_number'       => '',
                        'phone'                 => 'required',
                        'email'                 => 'email',
                        'postal_address'        => '',
                        'residential_address'   => '',
                        'status_id'             => '',
                        'passport_photo'        => ''
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'branch_id'             => 'required',
                        'first_name'            => 'required',
                        'middle_name'           => 'required',
                        'last_name'             => '',
                        'date_of_birth'         => 'required',
                        'date_became_member'    => 'required',
                        'nationality'           => '',
                        'county'                => '',
                        'city'                  => '',
                        'national_id_image'     => '',
                        'id_number'             => '',
                        'passport_number'       => '',
                        'phone'                 => 'required',
                        'email'                 => 'email',
                        'postal_address'        => '',
                        'residential_address'   => '',
                        'status_id'             => '',
                        'passport_photo'        => ''
                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}