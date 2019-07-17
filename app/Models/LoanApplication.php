<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:26
 */

namespace App\Models;


use Nicolaslopezj\Searchable\SearchableTrait;

class LoanApplication extends BaseModel
{
    use SearchableTrait;

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
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'loan_type_id',
        'interest_rate',
        'repayment_period',
        'amount_applied',
        'monthly_payments',
        'application_date',
        'disburse_method_id',
        'mpesa_number',
        'bank_name',
        'bank_branch',
        'bank_account',
        'other_banking_details',
        'witness_type_id',
        'witness_first_name',
        'witness_last_name',
        'witness_country',
        'witness_county',
        'witness_city',
        'witness_national_id',
        'witness_phone',
        'witness_email',
        'witness_postal_address',
        'witness_residential_address',
        'status_id',
        'witnessed_by_user_id',
        'approved_by_user_id',
        'attach_application_form'
    ];

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
            'loan_applications.member_id' => 2,
            'loan_applications.amount_applied' => 1,
        ]
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanApplicationStatus()
    {
        return $this->belongsTo(LoanApplicationStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function witnessUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approveUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loan()
    {
        return $this->hasOne(Loan::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}