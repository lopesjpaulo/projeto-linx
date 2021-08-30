<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AccountRepositoryInterface
{
    /**
     * @param int $client_id
     * @return Model
     */
    public function findByClient(int $client_id): ?Model;
}