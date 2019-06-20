<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 12:33
 */

namespace App\Models;

class BorrowerStatus extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'borrower_statuses';

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
        'borrower_status_name',
        'borrower_status_description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowers()
    {
        return $this->hasMany(Borrower::class);
    }
}