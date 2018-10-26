<?php
/**
 * Created by PhpStorm.
 * LoanApplication: kevin
 * Date: 26/10/2018
 * Time: 12:27
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoanApplicationRequest;
use App\Http\Resources\LoanApplicationResource;
use App\SmartMicro\Repositories\Contracts\LoanApplicationInterface;

use Illuminate\Http\Request;

class LoanApplicationController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\LoanApplicationInterface
     */
    protected $loanApplicationRepository;

    /**
     * LoanApplicationController constructor.
     * @param LoanApplicationInterface $loanApplicationInterface
     */
    public function __construct(LoanApplicationInterface $loanApplicationInterface)
    {
        $this->loanApplicationRepository   = $loanApplicationInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = LoanApplicationResource::collection($this->loanApplicationRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param LoanApplicationRequest $request
     * @return mixed
     */
    public function store(LoanApplicationRequest $request)
    {
        $save = $this->loanApplicationRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! LoanApplication has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $loanApplication = $this->loanApplicationRepository->getById($uuid);

        if(!$loanApplication)
        {
            return $this->respondNotFound('LoanApplication not found.');
        }
        return $this->respondWithData(new LoanApplicationResource($loanApplication));

    }

    /**
     * @param LoanApplicationRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(LoanApplicationRequest $request, $uuid)
    {
        $save = $this->loanApplicationRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! LoanApplication has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->loanApplicationRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! LoanApplication has been deleted');
        }
        return $this->respondNotFound('LoanApplication not deleted');
    }
}