<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 10:49
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    /* protected function validationData()
     {
         if(empty($this->json()->all()))
             throw new JsonEncodingException('Invalid JSON received');

         return  $this->all();
     }*/

    /**
     * Overrides response from the FormRequest
     * to not redirect for our API development
     * @param array $errors
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $message = array(
            'message' => "There were validation errors",
            'errors' => $errors
        );

        return new JsonResponse($message, 400);
    }

}