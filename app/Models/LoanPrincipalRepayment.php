<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 21/09/2019
 * Time: 21:25
 */

namespace App\Models;

use Nicolaslopezj\Searchable\SearchableTrait;

class LoanPrincipalRepayment extends BaseModel
{
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loan_principal_repayments';

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
        'loan_id',
        'period_count',
        'due_date',
        'amount',
        'paid_on',
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
            'loan_principal_repayments.amount' => 1,
        ]
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentFrequency()
    {
        return $this->belongsTo(PaymentFrequency::class, 'payment_frequency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class, 'loan_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interestType()
    {
        return $this->belongsTo(InterestType::class, 'interest_type_id');
    }
}