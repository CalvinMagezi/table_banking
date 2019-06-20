<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 27/10/2018
 * Time: 11:10
 */

namespace App\Models;

class Employee extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employees';

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
        'last_name',
        'salutation',
        'country',
        'national_id_number',
        'passport_number',
        'email',
        'telephone_number',
        'address',
        'postal_code',
        'county',
        'city',
        'nhif_number',
        'nssf_number',
        'kra_pin',
        'gender',
        'job_group',
        'designation_id',
        'department_id',
        'staff_no',
        'profile_picture',
        'national_id_image'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
}