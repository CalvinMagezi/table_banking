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
        $generalSetting = $this->generalSettingRepository->first();

        if(!$generalSetting)
        {
          // return $this->respondNotFound('General Setting not set.');
            return null;
        }
        return $this->respondWithData(new GeneralSettingResource($generalSetting));
    }

    /**
     * @param GeneralSettingRequest $request
     * @return mixed
     */
    public function store(GeneralSettingRequest $request)
    {
        $data = $request->all();

        // Upload logo
        if($request->hasFile('logo')) {
            $filenameWithExt = $request->file('logo')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();

            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $path = $request->file('logo')->storeAs('logos', $fileNameToStore);

            $data['logo'] = $fileNameToStore;
        }

        $save = $this->generalSettingRepository->create($data);

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
        $data = $request->all();
        $save = $this->generalSettingRepository->update($data, $uuid);
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

    /**
     * @param Request $request
     */
    public function uploadLogo(Request $request) {
        //return $uuid;
        $data = $request->all();
        // Upload logo
        if($request->hasFile('logo')) {
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('logo')->storeAs('logos', $fileNameToStore);
           // $data['logo'] = $fileNameToStore;
            $data['logo'] = $fileNameToStore;
        }
        $this->generalSettingRepository->update($data, $data['id']);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function fetchLogo(Request $request)
    {
        $data = $request->all();
        $setting = $this->generalSettingRepository->getById($data['id']);

        $file_path = $setting->logo;
        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR .'logos'.DIRECTORY_SEPARATOR. $file_path;
        return response()->file($local_path);
    }
}