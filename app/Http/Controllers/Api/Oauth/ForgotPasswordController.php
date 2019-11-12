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
use App\SmartMicro\Repositories\Contracts\UserInterface;


class ForgotPasswordController extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\UserInterface
     */
    protected $userRepository, $employeeRepository, $load;

    /**
     * ForgotPasswordController constructor.
     * @param UserInterface $userInterface
     */
    public function __construct(UserInterface $userInterface)
    {
        $this->userRepository = $userInterface;
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