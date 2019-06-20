<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:32
 */

namespace App\Models;

class LoanStatus extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loan_statuses';

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
            'loan_status_name',
            'loan_status_description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}