<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 29/09/2019
 * Time: 14:31
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * @param $amount
     * @return string
     */
    function formatMoney($amount) {
        return number_format($amount, 2, '.', ',');
    }

    /**
     * @param $date
     * @return mixed
     */
    function formatDateTime($date) {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @param $date
     * @return mixed
     */
    function formatDate($date) {
        return $date->format('Y-m-d');
    }

}