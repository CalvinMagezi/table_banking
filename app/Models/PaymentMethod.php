<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/12/2018
 * Time: 11:23
 */

namespace App\Models;

class PaymentMethod extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_methods';

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
        'method_name',
        'method_description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
}