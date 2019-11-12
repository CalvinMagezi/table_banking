<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 15/10/2019
 * Time: 12:29
 */

namespace App\Http\Controllers\Api;

use App\Http\Requests\FinanceStatementRequest;
use App\Http\Resources\FinanceStatementResource;
use App\SmartMicro\Repositories\Contracts\ReportInterface;

use App\SmartMicro\Repositories\Contracts\FinanceStatementInterface;
use Illuminate\Http\Request;

class FinanceStatementController extends ApiController
{
    /**
     * @var ReportInterface
     */
    protected $reportRepository, $load, $financeStatementRepository;

    /**
     * ReportController constructor.
     * @param ReportInterface $reportInterface
     * @param FinanceStatementInterface $financeStatementRepository
     */
    public function __construct(ReportInterface $reportInterface, FinanceStatementInterface $financeStatementRepository)
    {
        $this->reportRepository = $reportInterface;
        $this->financeStatementRepository = $financeStatementRepository;
        $this->load = [];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($select = request()->query('list')) {
            return $this->financeStatementRepository->listAll($this->formatFields($select));
        } else
            $data = FinanceStatementResource::collection($this->financeStatementRepository->getAllPaginate($this->load));

        return $this->respondWithData($data);
    }

    /**
     * @param FinanceStatementRequest $request
     * @return bool
     */
    public function store(FinanceStatementRequest $request)
    {
        $branchId = auth()->user()->branch_id;
        $startDate = null;
        $endDate = null;
        $reportType = null;
        $data = $request->all();

       // $branchId = 'd8def383-31bb-4f10-aa42-eb596c06653a';

        if ( array_key_exists('branch_id', $data)) {
            $branchId = $data['branch_id'];
        }
        if ( array_key_exists('start_date', $data)) {
            $startDate = $data['start_date'];
        }
        if ( array_key_exists('end_date', $data)) {
            $endDate = $data['end_date'];
        }
        if ( array_key_exists('statement_type_id', $data)) {
            $reportType = $this->financeStatementRepository->getById($data['statement_type_id'])->name;
        }

        switch ($reportType){
            case 'balance_sheet': {
                return $this->financeStatementRepository->balanceSheet($branchId, $startDate, $endDate);
            }
                break;
            case 'trial_balance': {
                return $this->financeStatementRepository->trialBalance($branchId, $startDate, $endDate);
            }
                break;
            case 'income_statement': {
                return $this->financeStatementRepository->incomeStatement($branchId);
            }
                break;
            default:{
                return false;
            }
        }

    }
}