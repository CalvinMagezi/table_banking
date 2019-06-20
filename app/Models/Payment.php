<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/12/2018
 * Time: 11:12
 */

namespace App\Models;

class Payment extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

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
        'payment_amount',
        'payment_method_id',
        'payment_date',
        'paid_to',
        'receipt_number',
        'attachment',
        'payment_notes'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}