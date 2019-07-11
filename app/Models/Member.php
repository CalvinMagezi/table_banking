<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 11:17
 */

namespace App\Models;

use Nicolaslopezj\Searchable\SearchableTrait;

class Member extends BaseModel
{
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'members';

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
        'first_name',
        'middle_name',
        'last_name',
        'nationality',
        'county',
        'city',
        'national_id_image',
        'id_number',
        'passport_number',
        'phone',
        'email',
        'postal_address',
        'residential_address',
        'bank_name',
        'bank_account',
        'bank_branch',
        'status_id',
        'passport_photo'
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
            'members.first_name' => 2,
            'members.middle_name' => 1,
        ]
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function borrower()
    {
        return $this->hasOne(Borrower::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function guarantor()
    {
        return $this->hasOne(Guarantor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}