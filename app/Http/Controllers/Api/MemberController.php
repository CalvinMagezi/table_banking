<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 11:17
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\MemberRequest;
use App\Http\Resources\MemberResource;
use App\SmartMicro\Repositories\Contracts\MemberInterface;

use Illuminate\Http\Request;

class MemberController  extends ApiController
{
    /**
     * @var \App\SmartMicro\Repositories\Contracts\MemberInterface
     */
    protected $memberRepository;

    /**
     * MemberController constructor.
     * @param MemberInterface $memberInterface
     */
    public function __construct(MemberInterface $memberInterface)
    {
        $this->memberRepository   = $memberInterface;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = MemberResource::collection($this->memberRepository->getAllPaginate());

        return $this->respondWithData($data);
    }

    /**
     * @param MemberRequest $request
     * @return mixed
     */
    public function store(MemberRequest $request)
    {
        $save = $this->memberRepository->create($request->all());

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! Member has been created.');

        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $member = $this->memberRepository->getById($uuid);

        if(!$member)
        {
            return $this->respondNotFound('Member not found.');
        }
        return $this->respondWithData(new MemberResource($member));

    }

    /**
     * @param MemberRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(MemberRequest $request, $uuid)
    {
        $save = $this->memberRepository->update($request->all(), $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else

            return $this->respondWithSuccess('Success !! Member has been updated.');

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->memberRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! Member has been deleted');
        }
        return $this->respondNotFound('Member not deleted');
    }
}