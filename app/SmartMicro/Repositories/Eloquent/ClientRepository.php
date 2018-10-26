<?php
/**
 * Created by PhpStorm.
 * Client: kevin
 * Date: 26/10/2018
 * Time: 12:11
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\Models\Client;
use App\SmartMicro\Repositories\Contracts\ClientInterface;

class ClientRepository extends BaseRepository implements ClientInterface {

    protected $model;

    /**
     * ClientRepository constructor.
     * @param Client $model
     */
    function __construct(Client $model)
    {
        $this->model = $model;
    }

}