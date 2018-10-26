<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:26
 */

namespace App\Models;


class LoanApplication extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loan_applications';

    /**
     * Main table primary key
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'reviewed_by_user_id',
        'approved_by_user_id',
        'application_date',
        'amount_applied',
        'repayment_period',
        'date_approved',
        'loan_status_id'
    ];
}