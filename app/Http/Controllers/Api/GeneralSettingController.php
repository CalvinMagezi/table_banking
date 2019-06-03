<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 03/06/2019
 * Time: 10:58
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\GeneralSettingRequest;
use App\Http\Resources\GeneralSettingResource;
use App\SmartMicro\Repositories\Contracts\GeneralSettingInterface;

use Illuminate\Http\Request;

class GeneralSettingController  extends ApiController
{
    /**
     * @var GeneralSettingInterface
     */
    protected $generalSettingRepository;

    /**
     * GeneralSettingController constructor.
     * @param GeneralSettingInterface $generalSettingInterface
     */
    public function __construct(GeneralSettingInterface $generalSettingInterface)
    {
        $this->generalSettingRepository   = $generalSettingInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->generalSettingRepository->listAll($this->formatFields($select));
        } else
            $data = GeneralSettingResource::collection($this->generalSettingRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param GeneralSettingRequest $request
     * @return mixed
     */
    public function store(GeneralSettingRequest $request)
    {
        $save = $this->generalSettingRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! GeneralSetting has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $generalSetting = $this->generalSettingRepository->getById($uuid);

        if(!$generalSetting)
        {
            return $this->respondNotFound('GeneralSetting not found.');
        }
        return $this->respondWithData(new GeneralSettingResource($generalSetting));

    }

    /**
     * @param GeneralSettingRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(GeneralSettingRequest $request, $uuid)
    {
        $save = $this->generalSettingRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! GeneralSetting has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->generalSettingRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! GeneralSetting has been deleted');
        }
        return $this->respondNotFound('GeneralSetting not deleted');
    }
}