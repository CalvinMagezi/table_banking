<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 25/10/2018
 * Time: 11:28
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\SmartMicro\Repositories\Contracts\UserInterface;

//use App\SmartMicro\Transformers\UserTransformer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\UserInterface
     */
    protected $userRepository, $userTransformer;

    /**
     * @param UserInterface $userRepository
     * @param UserTransformer $userTransformer
     */
    public function __construct()
    {
        //$this->userRepository   = $userRepository;
       // $this->userTransformer  = $userTransformer;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {


        return User::all();

        $filteredData = $this->doFilter($request, $this->userRepository, $this->userTransformer);

        if($filteredData){
            return $filteredData;
        }

        $load = ['role', 'properties', 'bookings'];

        $data = $this->userRepository->getAll($load);

        return $this->respondWithPagination($data, [
            'data' => $this->userTransformer->transformCollection($data->all())
        ]);
    }

    /**
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        if( ! array_key_exists('password', $data )){
            $hashed_random_password = str_random(8);
            $data['password'] = $hashed_random_password;
        }

        $save = $this->userRepository->create($data);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{

            $user = $this->userRepository->getWhere('uuid', $save['message']['uuid']);

            if( null != $user )
                event(new UserCreated($user));

            return $this->respondWithSuccess('Success !! User has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $user = $this->userRepository->getById($uuid);

        if(!$user)
        {
            return $this->respondNotFound('User not found.');
        }

        return $this->respond([
            'data' => $this->userTransformer->transform($user)
        ]);
    }

    /**
     * @param UserRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(UserRequest $request, $uuid)
    {
        $save = $this->userRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! User has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->userRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! User has been deleted');
        }
        return $this->respondNotFound('User not deleted');
    }

    /**
     * JSON POST data is provided
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->generalSearch($request, $this->userRepository, $this->userTransformer);

    }

    /**
     * @return mixed
     */
    public function me()
    {
        $user = Auth::user();
        if(isset($user))
            return $this->respond([
                'data' => $this->userTransformer->transform($user)
            ]);
        return $this->respondNotFound();
    }
}