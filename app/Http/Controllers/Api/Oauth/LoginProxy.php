<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Events\Oauth\LoginFailed;
use App\Events\Oauth\LoginSuccess;
use App\Events\Oauth\Logout;
use App\SmartMicro\Repositories\Contracts\GeneralSettingInterface;
use App\SmartMicro\Repositories\Contracts\RoleInterface;
use App\SmartMicro\Repositories\Contracts\UserInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Application;
//use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    private $apiConsumer;


    private $auth;

    private $cookie;

    private $db;

    private $request;

    private $userRepository, $roleRepository, $generalSettingRepository;

    /**
     * LoginProxy constructor.
     * @param Application $app
     * @param UserInterface $userRepository
     * @param RoleInterface $roleRepository
     * @param GeneralSettingInterface $generalSettingRepository
     */
    public function __construct(Application  $app, UserInterface $userRepository,
                                RoleInterface $roleRepository, GeneralSettingInterface $generalSettingRepository) {

        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->generalSettingRepository = $generalSettingRepository;

        $this->apiConsumer = $app->make('apiconsumer');

        $this->auth = $app->make('auth');
        $this->cookie = $app->make('cookie');
        $this->db = $app->make('db');
        $this->request = $app->make('request');
    }


    /**
     * @param $email
     * @param $password
     * @return array
     */
    public function attemptLogin($email, $password)
    {
        $user = $this->userRepository->getWhere('email', $email);
        if (!is_null($user)) {

            $scope = trim($this->checkPermissions($user->role_id));
           // $scope = trim($this->checkRole($user->role_id));
          //  $scope = 'admin';

            return $this->proxy('password', [
                'username'  => $email,
                'password'  => $password,
                'scope'     => $scope ? $scope : 'null'
            ], $user);
        }
        // event login failed
        event(new LoginFailed($email));
        throw new UnauthorizedHttpException("", Exception::class, null, 0);
    }


    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie
     */
    public function attemptRefresh()
    {
        try{
            $refreshToken = $this->request->cookie(self::REFRESH_TOKEN);

            return $this->proxy('refresh_token', [
                'refresh_token' => decrypt($refreshToken)
            ]);

        }catch (DecryptException $e){

            throw new DecryptException($e);

        }
    }


    /**
     * @param $grantType
     * @param array $credentials
     * @param array $user
     * @return array
     */
    public function proxy($grantType, array $credentials = [], $user = array())
    {
        $data = array_merge($credentials, [
            'client_id'     => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type'    => $grantType
        ]);

        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            // event login failed
             event(new LoginFailed($credentials['username']));

           // throw new InvalidCredentialsException('Invalid Credentials..');
            throw new UnauthorizedHttpException("", Exception::class, null, 0);

        }

        $data = json_decode($response->getContent());

        // Create a refresh token cookie
        $this->cookie->queue(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            864000, // 10 days
            null,
            null,
            false,
            true // HttpOnly
        );

        // event login success
        event(new LoginSuccess($credentials['username']));

        return [
            'access_token' 	=> $data->access_token,
            'expires_in' 	=> $data->expires_in,
            'settings'      => $this->generalSettingRepository->getFirst(),
            'branch_id'     => $user ? $user['branch_id'] : null,
			//'scope' 		=> $credentials['scope']
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = $this->auth->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        // event logout success
        event(new Logout($this->auth->user()));

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }

    /**
     * @param $roleId
     * @return string
     */
    private function checkPermissions($roleId)
    {
        $role = $this->roleRepository->getWhere('id', $roleId, ['permissions']);

        if(!$role)
            return '';

        $role_permissions = $role->permissions()->get()->toArray();
        $data = [];
        foreach ($role_permissions as $key => $value){
            $data[] = trim($value['name']);
        }

        return implode(' ', $data);
    }

    /**
     * @param $roleId
     * @return string
     */
    private function checkRole($roleId)
    {
        $role = $this->roleRepository->getWhere('id', $roleId, ['permissions']);
        $data[] = trim(strtolower($role->role_name));

        return implode(' ', $data);
    }

}