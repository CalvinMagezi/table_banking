<?php
/**
 * Created by PhpStorm.
 * LoanApplication: kevin
 * Date: 26/10/2018
 * Time: 12:26
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\LoanApplication;
use App\SmartMicro\Repositories\Contracts\LoanApplicationInterface;

class LoanApplicationRepository extends BaseRepository implements LoanApplicationInterface {

    protected $model;

    /**
     * LoanApplicationRepository constructor.
     * @param LoanApplication $model
     */
    function __construct(LoanApplication $model)
    {
        $this->model = $model;
    }

}