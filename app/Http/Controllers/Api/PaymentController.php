<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/12/2018
 * Time: 11:13
 */

namespace App\Http\Controllers\Api;

use App\Events\Payment\PaymentReceived;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\SmartMicro\Repositories\Contracts\JournalInterface;
use App\SmartMicro\Repositories\Contracts\PaymentInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController  extends ApiController
{
    /**
     * @var PaymentInterface
     */
    protected $paymentRepository, $load, $journalRepository;

    /**
     * PaymentController constructor.
     * @param PaymentInterface $paymentInterface
     * @param JournalInterface $journalInterface
     */
    public function __construct(PaymentInterface $paymentInterface, JournalInterface $journalInterface)
    {
        $this->paymentRepository   = $paymentInterface;
        $this->load = ['member', 'paymentMethod', 'branch'];
        $this->journalRepository   = $journalInterface;
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
            $data = PaymentResource::collection($this->paymentRepository->getAllPaginate($this->load));

        return $this->respondWithData($data);
    }

    /**
     * @param PaymentRequest $request
     * @return array|mixed
     * @throws \Exception
     */
    public function store(PaymentRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $data = $request->all();
            if(array_key_exists('bank_fields', $data)){
                $data['cheque_number'] = $data['bank_fields']['cheque_number'];
                $data['bank_name'] = $data['bank_fields']['bank_name'];
                $data['bank_branch'] = $data['bank_fields']['bank_branch'];
                $data['cheque_date'] = $data['bank_fields']['cheque_date'];
            }
            // payment record
            $newPayment = $this->paymentRepository->create($data);

            // journal entry
            $this->journalRepository->paymentReceivedEntry($newPayment);

            // Handle transactions
            if($newPayment)
                event(new PaymentReceived($newPayment));

            DB::commit();
            return $this->respondWithSuccess('Success !! Payment received.');

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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
}