<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 26/10/2018
 * Time: 12:09
 */

namespace App\Models;

class Borrower extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'borrowers';

    /**
     * Main table primary key
     * @var string
     */
    protected $primaryKey = 'uuid';

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
        'id_image',
        'id_number',
        'passport_number',
        'telephone_number',
        'email',
        'postal_address',
        'residential_address',
        'bank_name',
        'bank_account',
        'bank_branch',
        'spouse_type',
        'spouse_name',
        'spouse_id_number',
        'spouse_phone',
        'spouse_address',
        'members_status',
        'passport_photo'
    ];
}