<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:17
 */

namespace App\Models;

use App\Traits\BranchFilterScope;
use App\Traits\BranchScope;
use App\Traits\NextDueDate;
use Carbon\Carbon;
use Nicolaslopezj\Searchable\SearchableTrait;

class Loan extends BaseModel
{
    use SearchableTrait, BranchScope, NextDueDate, BranchFilterScope;

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
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'member_id',

        'loan_reference_number',
        'loan_application_id',
        'loan_type_id',

        'interest_rate',
        'interest_type_id',
        'repayment_period',

        'loan_status_id',
        'approved_by_user_id',

        'amount_approved',
        'service_fee',
        'penalty_type_id',
        'penalty_value',
        'penalty_frequency_id',

        'loan_disbursed',

        'start_date',
        'next_repayment_date',
        'end_date',

        'payment_frequency_id',

        // 'closed_on',

        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * @param $start_date
     *
     */
    public function setStartDateAttribute($start_date)
    {
        $this->attributes['start_date'] = date('Y-m-d H:i:s', strtotime($start_date));
    }

    /**
     * @param $end_date
     */
    public function setEndDateAttribute($end_date)
    {
        $this->attributes['end_date'] = date('Y-m-d H:i:s', strtotime($end_date));
    }

    /**
     * @param $next_repayment_date
     */
    public function setNextRepaymentDateAttribute($next_repayment_date)
    {
        $this->attributes['next_repayment_date'] = date('Y-m-d H:i:s', strtotime($next_repayment_date));
    }

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'loans.loan_reference_number' => 2,
            'loans.amount_approved' => 1,
        ]
    ];

    static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $latest = $model->latest()->first();

            $now = Carbon::now();

           // $loanCode = 'LN'.$now->year.$now->format('M').$now->weekOfYear.'-';

            if ($latest) {
                $string = preg_replace("/[^0-9\.]/", '', $latest->loan_reference_number);
                $model->loan_reference_number =  'LN' . sprintf('%04d', $string+1);
            }else{
              //  $model->loan_reference_number = 'LN-'.$now->year.'-0001';
                $model->loan_reference_number = 'LN0001';
            }

            // set next repayment date
           //$model->next_repayment_date = NextDueDate::dueDate($model->start_date, $model->payment_frequency_id);
            // Set the next_repayment_date to be equal to loan start date. This enables us to calculate loan dues immediately after loan issue.
           $model->next_repayment_date = $model->start_date;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interestType()
    {
        return $this->belongsTo(InterestType::class, 'interest_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentFrequency()
    {
        return $this->belongsTo(PaymentFrequency::class, 'payment_frequency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penaltyFrequency()
    {
        return $this->belongsTo(PenaltyFrequency::class, 'penalty_frequency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanStatus()
    {
        return $this->belongsTo(LoanStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approveUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }
}