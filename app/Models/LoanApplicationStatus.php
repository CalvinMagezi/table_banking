<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:34
 */

namespace App\Models;

class LoanApplicationStatus extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loan_application_statuses';

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
        'loan_application_status_name',
        'loan_application_status_description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}