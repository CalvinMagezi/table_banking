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
     * @var \App\SignalCrm\Repositories\Contracts\RoleInterface
     */
    protected $roleRepository;
    protected $load;

    /**
     * RoleController constructor.
     * @param RoleInterface $roleInterface
     */
    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleRepository   = $roleInterface;
        $this->load = ['permissions'];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->roleRepository->listAll($this->formatFields($select));
        } else
            $data = RoleResource::collection($this->roleRepository->getAllPaginate($this->load));

        return $this->respondWithData($data);
    }

    /**
     * @param RoleRequest $request
     * @return mixed
     */
    public function store(RoleRequest $request)
    {
        // dispatch(new CreateRole($request->all()));
        $role = $this->roleRepository->create($request->all());

        return $this->respondWithSuccess('Success !! Role has been created.');
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $role = $this->roleRepository->getById($uuid, $this->load);

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
        // $this->dispatch(new UpdateRole($request->all(), $uuid));

        $this->roleRepository->update($request->all(), $uuid);

        return $this->respondWithSuccess('Success !! Role has been updated.');
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        //$this->dispatch(new DeleteRole($uuid));

        $this->roleRepository->delete($uuid);

        return $this->respondWithSuccess('Success !! Role has been deleted');
    }
}