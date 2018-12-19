<?php
/**
 * Created by PhpStorm.
 * Loan: kevin
 * Date: 26/10/2018
 * Time: 12:18
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use App\SmartMicro\Repositories\Contracts\LoanInterface;

use Illuminate\Http\Request;

class LoanController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\LoanInterface
     */
    protected $loanRepository;

    /**
     * LoanController constructor.
     * @param LoanInterface $loanInterface
     */
    public function __construct(LoanInterface $loanInterface)
    {
        $this->loanRepository   = $loanInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->loanRepository->listAll($this->formatFields($select));
        } else
            $data = LoanResource::collection($this->loanRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param LoanRequest $request
     * @return mixed
     */
    public function store(LoanRequest $request)
    {
        $save = $this->loanRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! Loan has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $loan = $this->loanRepository->getById($uuid);

        if(!$loan)
        {
            return $this->respondNotFound('Loan not found.');
        }
        return $this->respondWithData(new LoanResource($loan));

    }

    /**
     * @param LoanRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(LoanRequest $request, $uuid)
    {
        $save = $this->loanRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! Loan has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->loanRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Loan has been deleted');
        }
        return $this->respondNotFound('Loan not deleted');
    }
}