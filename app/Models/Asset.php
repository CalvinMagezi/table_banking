<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 11/07/2019
 * Time: 12:45
 */

namespace App\Models;

use Nicolaslopezj\Searchable\SearchableTrait;

class Asset extends BaseModel
{
    use SearchableTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'assets';

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
        'member_id',
        'asset_number',
        'title',
        'description',
        'valuation_date',
        'valued_by',
        'valuer_phone',
        'valuation_amount',
        'location',
        'registration_number',
        'registered_to',
        'condition',
        'notes',
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
            'assets.title' => 2,
            'assets.description' => 1,
        ]
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(AssetPhoto::class, 'asset_id');
    }
}