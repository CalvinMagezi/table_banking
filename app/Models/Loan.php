<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:17
 */

namespace App\Models;

class Loan extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loans';

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
        'approved_by_user_id',
        'loan_ref',
        'amount_applied',
        'amount_approved',
        'amount_received',
        'date_approved',
        'due_date',
        'loan_status',
        'application_id'
    ];
}