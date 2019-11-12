<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 02/11/2019
 * Time: 20:30
 */

namespace App\Http\Controllers\Api;

use App\Events\Payment\PaymentReceived;
use App\Http\Requests\CapitalRequest;
use App\Http\Resources\CapitalResource;
use App\Models\Capital;
use App\SmartMicro\Repositories\Contracts\CapitalInterface;

use App\SmartMicro\Repositories\Contracts\JournalInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapitalController extends ApiController
{
    /**
     * @var CapitalInterface
     */
    protected $capitalRepository, $load, $journalRepository;

    /**
     * CapitalController constructor.
     * @param CapitalInterface $capitalInterface
     * @param JournalInterface $journalInterface
     */
    public function __construct(CapitalInterface $capitalInterface, JournalInterface $journalInterface)
    {
        $this->capitalRepository = $capitalInterface;
        $this->load = ['branch'];
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
            return $this->capitalRepository->listAll($this->formatFields($select));
        } else
            $data = CapitalResource::collection($this->capitalRepository->getAllPaginate($this->load));

        return $this->respondWithData($data);
    }

    /**
     * @param CapitalRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(CapitalRequest $request)
    {

      //  return $this->capitalRepository->create($request->all());
        DB::beginTransaction();
        try
        {
            $data = $request->all();
            // capital record
            $newCapital = $this->capitalRepository->create($data);

            // journal entry
            $this->journalRepository->capitalReceivedEntry($newCapital);

            DB::commit();
            return $this->respondWithSuccess('Success !! Capital Registered.');

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
        $capital = $this->capitalRepository->getById($uuid);

        if (!$capital) {
            return $this->respondNotFound('Capital not found.');
        }
        return $this->respondWithData(new CapitalResource($capital));

    }

    /**
     * @param CapitalRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(CapitalRequest $request, $uuid)
    {
        $save = $this->capitalRepository->update($request->all(), $uuid);

        if ($save['error']) {
            return $this->respondNotSaved($save['message']);
        } else

            return $this->respondWithSuccess('Success !! Capital has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if ($this->capitalRepository->delete($uuid)) {
            return $this->respondWithSuccess('Success !! Capital has been deleted');
        }
        return $this->respondNotFound('Capital not deleted');
    }
}