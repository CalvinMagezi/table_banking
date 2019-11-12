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
use App\Models\Member;
use App\SmartMicro\Repositories\Contracts\AccountInterface;
use App\SmartMicro\Repositories\Contracts\MemberInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController  extends ApiController
{
    /**
     * @var MemberInterface
     */
    protected $memberRepository, $accountRepository, $load;

    /**
     * MemberController constructor.
     * @param MemberInterface $memberInterface
     * @param AccountInterface $accountInterface
     */
    public function __construct(MemberInterface $memberInterface, AccountInterface $accountInterface)
    {
        $this->memberRepository   = $memberInterface;
        $this->accountRepository   = $accountInterface;
        $this->load = ['branch', 'assets', 'account', 'guaranteedLoans'];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
       // return Member::withoutGlobalScopes()->get();

        if ($select = request()->query('list')) {
           // return $this->memberRepository->listAll($this->formatFields($select));
            return $this->memberRepository->listAll($this->formatFields($select), ['account']);

        } else
            $data = MemberResource::collection($this->memberRepository->getAllPaginate($this->load));

        return $this->respondWithData($data);
    }

    /**
     * @param MemberRequest $request
     * @return mixed
     */
    public function store(MemberRequest $request)
    {
        $data = $request->all();

        // Upload national id
        if($request->hasFile('national_id_image')) {
            // return $this->respondWithData($data);
            // Get filename with extension
            $filenameWithExt = $request->file('national_id_image')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('national_id_image')->getClientOriginalExtension();

            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // Upload Image
            // $path = $request->file('attach_application_form')->storeAs('public/cover_images', $fileNameToStore);
            $path = $request->file('national_id_image')->storeAs('member_ids', $fileNameToStore);

            $data['national_id_image'] = $fileNameToStore;
        }

        // Upload passport photo
        if($request->hasFile('passport_photo')) {
          // return $this->respondWithData($data);
            // Get filename with extension
            $filenameWithExt = $request->file('passport_photo')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('passport_photo')->getClientOriginalExtension();

            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // Upload Image
            // $path = $request->file('attach_application_form')->storeAs('public/cover_images', $fileNameToStore);
            $path = $request->file('passport_photo')->storeAs('members', $fileNameToStore);

            $data['passport_photo'] = $fileNameToStore;
        }

        $save = $this->memberRepository->create($data);

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

    /**
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function profilePic(Request $request)
    {
        $data = $request->all();

        if( array_key_exists('file_path', $data) ) {
            $file_path = $data['file_path'];

         /*   if (!Storage::disk('local')->exists($file_path)) {
                return $this->respondNotFound('Image not found');
            }*/

            $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR .'members'.DIRECTORY_SEPARATOR. $file_path;

            return response()->file($local_path);
        }

        return $this->respondNotFound('file_path not provided');
    }
}