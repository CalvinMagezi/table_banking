<?php
/**
 * Created by PhpStorm.
 * Loan: kevin
 * Date: 26/10/2018
 * Time: 12:17
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\Loan;
use App\SmartMicro\Repositories\Contracts\LoanInterface;

class LoanRepository extends BaseRepository implements LoanInterface {

    protected $model;

    /**
     * LoanRepository constructor.
     * @param Loan $model
     */
    function __construct(Loan $model)
    {
        $this->model = $model;
    }

}