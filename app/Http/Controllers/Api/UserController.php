<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 25/10/2018
 * Time: 11:28
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\SmartMicro\Repositories\Contracts\EmployeeInterface;
use App\SmartMicro\Repositories\Contracts\UserInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\UserInterface
     */
    protected $userRepository, $employeeRepository;

    /**
     * UserController constructor.
     * @param UserInterface $userInterface
     * @param EmployeeInterface $employeeRepository
     */
    public function __construct(UserInterface $userInterface, EmployeeInterface $employeeRepository)
    {
        $this->userRepository   = $userInterface;
        $this->employeeRepository   = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $load = ['employee', 'role'];

        if ($select = request()->query('list')) {
            return $this->userRepository->listAll($this->formatFields($select));
        } else
            $data = UserResource::collection($this->userRepository->getAllPaginate($load));

        return $this->respondWithData($data);
    }

    /**
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $save = $this->userRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
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
        return $this->respondWithData(new UserResource($user));

    }

    /**
     * @param UserRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(UserRequest $request, $uuid)
    {

        $save = $this->userRepository->update(array_filter($request->all()), $uuid);

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
     * @return mixed
     */
    public function me()
    {
        $user = Auth::user();

        if(isset($user)){
            return $this->employeeRepository->getById($user->employee_id);
        }
           // return $user;
        return $this->respondNotFound();
    }
}