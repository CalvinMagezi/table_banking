<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 11/07/2019
 * Time: 13:24
 */

namespace App\Http\Requests;

use Illuminate\Database\Schema\Builder;
use Illuminate\Validation\Rule;

class AssetRequest extends BaseRequest
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
                        'title'                 => 'required|min:2',
                        'location'              => 'required|min:2',
                        'member_id'             => 'required|exists:members,id',
                        'asset_number'          => 'required|min:2',
                        'description'           => 'required|min:2',
                        'valuation_date'        => 'required|min:2',
                        'valued_by'             => 'required|min:2',
                        'valuer_phone'          => 'required|min:2',
                        'valuation_amount'      => 'required|min:2',
                        'registration_number'   => '',
                        'registered_to'         => '',
                        'condition'             => 'required|min:2',
                        'notes'                 => 'required|min:2',
                    ];

                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $rules = [
                        'title'                 => 'required|min:2',
                        'location'              => 'required|min:2',
                        'member_id'             => 'required|exists:members,id',
                        'asset_number'          => ['required', Rule::unique('assets')->ignore($this->asset, 'id')
                            ->where(function ($query) {
                                $query->where('deleted_at', NULL);
                            })],
                        'description'           => 'required',
                        'valuation_date'        => 'required',
                        'valued_by'             => 'required',
                        'valuer_phone'          => 'required',
                        'valuation_amount'      => 'required',
                        'registration_number'   => '',
                        'registered_to'         => '',
                        'condition'             => 'required',
                        'notes'                 => 'required',
                    ];
                    break;
                }
            default:
                break;
        }

        return $rules;

    }
}