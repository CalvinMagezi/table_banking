<?php
/**
 * Created by PhpStorm.
 * LoanApplication: kevin
 * Date: 26/10/2018
 * Time: 12:27
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoanApplicationRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use App\SmartMicro\Repositories\Contracts\LoanApplicationInterface;

use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanApplicationController  extends ApiController
{
    /**
     * @var LoanApplicationInterface
     */
    protected $loanApplicationRepository, $load;

    /**
     * LoanApplicationController constructor.
     * @param LoanApplicationInterface $loanApplicationInterface
     */
    public function __construct(LoanApplicationInterface $loanApplicationInterface)
    {
        $this->loanApplicationRepository   = $loanApplicationInterface;
        $this->load = ['member', 'guarantors', 'assets', 'guarantors', 'loanType', 'interestType', 'loan', 'paymentFrequency'];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
       /* $request['whereField'] = 'member_id';
        $request['whereValue'] = '44144c89-f28f-4c8a-96ac-7d54c6681de5';*/

       // $request['whereField'] = 'reviewed_on';
     //   $request['whereValue'] = null;

        if ($select = request()->query('list')) {
            return $this->loanApplicationRepository->listAll($this->formatFields($select));
        } else
        $data = LoanApplicationResource::collection($this->loanApplicationRepository->getAllPaginate($this->load));

        return $this->respondWithData($data);
    }

    /**
     * @param LoanApplicationRequest $request
     * @return mixed
     */
    public function store(LoanApplicationRequest $request)
    {
        $data = $request->all();

        if($request->hasFile('attach_application_form')) {
            // Get filename with extension
           $filenameWithExt = $request->file('attach_application_form')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('attach_application_form')->getClientOriginalExtension();

            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // Upload Image
           // $path = $request->file('attach_application_form')->storeAs('public/cover_images', $fileNameToStore);
            $path = $request->file('attach_application_form')->storeAs('loan_application_forms', $fileNameToStore);

            $data['attach_application_form'] = $fileNameToStore;

        } else {
            $fileNameToStore = 'nofile.pdf';
        }

        $save = $this->loanApplicationRepository->create($request->all());

      //  dd($save);


        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! LoanApplication has been created.');
        }

    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        $loanApplication = $this->loanApplicationRepository->getById($uuid);

        if(!$loanApplication)
        {
            return $this->respondNotFound('LoanApplication not found.');
        }
        return $this->respondWithData(new LoanApplicationResource($loanApplication));

    }

    /**
     * @param LoanApplicationRequest $request
     * @param $uuid
     * @return mixed
     */
    public function update(LoanApplicationRequest $request, $uuid)
    {
        $data = $request->all();

        if( array_key_exists('review', $data)){
            $user = auth('api')->user();
            $data['reviewed_by_user_id'] = $user->id;
            $data['reviewed_on'] = Carbon::now();
            $data['rejected_on'] = Carbon::now();
            $data['approved_on'] = null;
        }

        $save = $this->loanApplicationRepository->update($data, $uuid);

        if($save['error']){
            return $this->respondNotSaved($save['message']);
        }else{
            return $this->respondWithSuccess('Success !! LoanApplication has been updated.');
        }
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid)
    {
        if($this->loanApplicationRepository->delete($uuid)){
            return $this->respondWithSuccess('Success !! LoanApplication has been deleted');
        }
        return $this->respondNotFound('LoanApplication not deleted');
    }

    /**
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function applicationForm(Request $request)
    {
        $data = $request->all();
        if( array_key_exists('file_path', $data) ) {
            $file_path = $data['file_path'];
            $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR .'loan_application_forms'.DIRECTORY_SEPARATOR. $file_path;
            return response()->file($local_path);
        }
        return $this->respondNotFound('file_path not provided');
    }
}