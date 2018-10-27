<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:10
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BorrowerRequest extends BaseRequest
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
                        'id_image'              => 'required',
                        'id_number'             => 'required',
                        'passport_number'       => 'required',
                        'telephone_number'      => 'required',
                        'email'                 => 'required',
                        'postal_address'        => 'required',
                        'residential_address'   => 'required',
                        'bank_name'             => 'required',
                        'bank_account'          => 'required',
                        'bank_branch'           => 'required',
                        'spouse_type'           => 'required',
                        'spouse_name'           => 'required',
                        'spouse_id_number'      => 'required',
                        'spouse_phone'          => 'required',
                        'spouse_address'        => 'required',
                        'members_status'        => 'required',
                        'passport_photo'        => 'required'
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'name'              => 'min:2',
                        'email'             => ['email', Rule::unique('users')->ignore($this->user, 'uuid')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],

                        'password'              => 'min:3|confirmed',
                        'password_confirmation' => 'required_with:password'

                    ];
                    break;
                }
            default:break;
        }

        return $rules;

    }
}