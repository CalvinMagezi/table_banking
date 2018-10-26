<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\SmartMicro\Repositories\Contracts\RoleInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\RoleInterface
     */
    protected $roleRepository;

    /**
     * RoleController constructor.
     * @param RoleInterface $roleInterface
     */
    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleRepository   = $roleInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = RoleResource::collection($this->roleRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param RoleRequest $request
     * @return mixed
     */
    public function store(RoleRequest $request)
    {
        $save = $this->roleRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! Role has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $role = $this->roleRepository->getById($uuid);

        if(!$role)
        {
            return $this->respondNotFound('Role not found.');
        }
        return $this->respondWithData(new RoleResource($role));

    }

    /**
     * @param RoleRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(RoleRequest $request, $uuid)
    {
        $save = $this->roleRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! Role has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->roleRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Role has been deleted');
        }
        return $this->respondNotFound('Role not deleted');
    }

    /**
     * @return mixed
     */
    public function me()
    {
        $role = Auth::role();
        if(isset($role))
            return $role;
        return $this->respondNotFound();
    }
}