<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 02/08/2019
 * Time: 11:50
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\EmailSettingRequest;
use App\Http\Resources\EmailSettingResource;
use App\SmartMicro\Repositories\Contracts\EmailSettingInterface;

use Illuminate\Http\Request;

class EmailSettingController extends ApiController
{
    /**
     * @var EmailSettingInterface
     */
    protected $emailSettingRepository;

    /**
     * EmailSettingController constructor.
     * @param EmailSettingInterface $emailSettingInterface
     */
    public function __construct(EmailSettingInterface $emailSettingInterface)
    {
        $this->emailSettingRepository = $emailSettingInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $emailSetting = $this->emailSettingRepository->first();

        if (!$emailSetting) {
            // return $this->respondNotFound('General Setting not set.');
            return null;

        }

        return $this->respondWithData(new EmailSettingResource($emailSetting));
    }

    /**
     * @param EmailSettingRequest $request
     * @return mixed
     */
    public function store(EmailSettingRequest $request)
    {
        $save = $this->emailSettingRepository->create($request->all());

        if ($save['error']) {
            return $this->respondNotSaved($save['message']);
        } else {
            return $this->respondWithSuccess('Success !! EmailSetting has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $emailSetting = $this->emailSettingRepository->getById($uuid);

        if (!$emailSetting) {
            return $this->respondNotFound('EmailSetting not found.');
        }
        return $this->respondWithData(new EmailSettingResource($emailSetting));

    }

    /**
     * @param EmailSettingRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(EmailSettingRequest $request, $uuid)
    {
        $save = $this->emailSettingRepository->update($request->all(), $uuid);

        if ($save['error']) {
            return $this->respondNotSaved($save['message']);
        } else

            return $this->respondWithSuccess('Success !! EmailSetting has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if ($this->emailSettingRepository->delete($uuid)) {
            return $this->respondWithSuccess('Success !! EmailSetting has been deleted');
        }
        return $this->respondNotFound('EmailSetting not deleted');
    }
}