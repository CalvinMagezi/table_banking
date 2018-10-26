<?php
/**
 * Created by PhpStorm.
 * Client: kevin
 * Date: 26/10/2018
 * Time: 12:10
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\SmartMicro\Repositories\Contracts\ClientInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\ClientInterface
     */
    protected $clientRepository;

    /**
     * ClientController constructor.
     * @param ClientInterface $clientInterface
     */
    public function __construct(ClientInterface $clientInterface)
    {
        $this->clientRepository   = $clientInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = ClientResource::collection($this->clientRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param ClientRequest $request
     * @return mixed
     */
    public function store(ClientRequest $request)
    {
        $save = $this->clientRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! Client has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $client = $this->clientRepository->getById($uuid);

        if(!$client)
        {
            return $this->respondNotFound('Client not found.');
        }
        return $this->respondWithData(new ClientResource($client));

    }

    /**
     * @param ClientRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(ClientRequest $request, $uuid)
    {
        $save = $this->clientRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! Client has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->clientRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Client has been deleted');
        }
        return $this->respondNotFound('Client not deleted');
    }

    /**
     * @return mixed
     */
    public function me()
    {
        $client = Auth::client();
        if(isset($client))
            return $client;
        return $this->respondNotFound();
    }
}