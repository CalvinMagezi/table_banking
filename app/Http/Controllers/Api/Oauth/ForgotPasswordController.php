<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 06/11/2019
 * Time: 03:59
 */

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends ApiController
{
    use SendsPasswordResetEmails;

    /**
     * ForgotPasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function __invoke(Request $request)
    {
        $this->validateEmail($request);
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
      /*  return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.', 'status' => true], 201)
            : response()->json(['message' => 'Unable to send reset link', 'status' => false], 401);*/

        return $response == Password::RESET_LINK_SENT
            ? $this->respondWithSuccess('Success !! Reset link sent to your email..')
            : $this->respondNotFound('Unable to send reset link.');
    }

    /**
     * @param ForgotPasswordRequest $request
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // TODO
        // Check if email is for valid user. Send password reset code to the email.
    }
}