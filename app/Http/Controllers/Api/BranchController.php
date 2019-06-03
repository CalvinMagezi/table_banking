<?php
/**
 * Created by PhpStorm.
 * Branch: kevin
 * Date: 26/10/2018
 * Time: 21:38
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\SmartMicro\Repositories\Contracts\BranchInterface;

use Illuminate\Http\Request;

class BranchController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\BranchInterface
     */
    protected $branchRepository;

    /**
     * BranchController constructor.
     * @param BranchInterface $branchInterface
     */
    public function __construct(BranchInterface $branchInterface)
    {
        /**
         * System permissions example
         */
        /* $this->middleware('scope:create-branch')->only('index');
         $this->middleware('scope:view-customer')->only('show');
         $this->middleware('scope:view-customer')->only('search');

         $this->middleware('scope:create-customer')->only('store');
         $this->middleware('scope:edit-customer')->only('update');
         $this->middleware('scope:delete-customer')->only('destroy');*/

        $this->branchRepository   = $branchInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->branchRepository->listAll($this->formatFields($select));
        } else
             $data = BranchResource::collection($this->branchRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param BranchRequest $request
     * @return mixed
     */
    public function store(BranchRequest $request)
    {
        $save = $this->branchRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! Branch has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $branch = $this->branchRepository->getById($uuid);

        if(!$branch)
        {
            return $this->respondNotFound('Branch not found.');
        }
        return $this->respondWithData(new BranchResource($branch));

    }

    /**
     * @param BranchRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(BranchRequest $request, $uuid)
    {
        $save = $this->branchRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! Branch has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->branchRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Branch has been deleted');
        }
        return $this->respondNotFound('Error !! Branch not found. Nothing deleted.');
    }
}