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
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'borrower_id',
        'loan_application_id',
        'loan_type_id',
        'loan_status_id',
        'branch_id',
        'approved_by_user_id',
        'loan_reference',
        'amount_applied',
        'amount_approved',
        'amount_received',
        'date_approved',
        'due_date',
        'loan_witness_name',
        'loan_witness_phone',
        'loan_witness_relationship'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
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
    public function branch()
    {
        return $this->belongsTo(Branch::class);
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