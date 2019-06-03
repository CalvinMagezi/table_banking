<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 03/06/2019
 * Time: 10:49
 */

namespace App\Models;

class GeneralSetting extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'general_settings';

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
        'business_name',
        'business_type',
        'email',
        'contact_first_name',
        'contact_last_name',
        'currency',
        'phone',
        'country',
        'county',
        'town',
        'physical_address',
        'postal_address',
        'kra_pin',
        'logo',
        'favicon',
    ];
}