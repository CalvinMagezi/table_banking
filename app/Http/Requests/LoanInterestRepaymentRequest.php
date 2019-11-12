<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 21:19
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LoanInterestRepaymentRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                    break;
                }
            case 'POST':
                {
                    $rules = [
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
            default:
                break;
        }

        return $rules;

    }
}