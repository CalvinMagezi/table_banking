<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/12/2018
 * Time: 11:13
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\SmartMicro\Repositories\Contracts\PaymentInterface;

use Illuminate\Http\Request;

class PaymentController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\PaymentInterface
     */
    protected $paymentRepository;

    /**
     * PaymentController constructor.
     * @param PaymentInterface $paymentInterface
     */
    public function __construct(PaymentInterface $paymentInterface)
    {
        $this->paymentRepository   = $paymentInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->paymentRepository->listAll($this->formatFields($select));
        } else
            $data = PaymentResource::collection($this->paymentRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param PaymentRequest $request
     * @return mixed
     */
    public function store(PaymentRequest $request)
    {
        $save = $this->paymentRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! Payment has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $payment = $this->paymentRepository->getById($uuid);

        if(!$payment)
        {
            return $this->respondNotFound('Payment not found.');
        }
        return $this->respondWithData(new PaymentResource($payment));

    }

    /**
     * @param PaymentRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(PaymentRequest $request, $uuid)
    {
        $save = $this->paymentRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! Payment has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->paymentRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Payment has been deleted');
        }
        return $this->respondNotFound('Payment not deleted');
    }
}