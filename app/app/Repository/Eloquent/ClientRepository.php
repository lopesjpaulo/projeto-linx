<?php 

namespace App\Repository\Eloquent;

use App\Models\Client;
use App\Repository\ClientRepositoryInterface;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * ClienteRepository constructor
     * 
     * @param Client $model
     */
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }
}