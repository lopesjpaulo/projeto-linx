<?php 

namespace App\Repository\Eloquent;

use App\Models\Account;
use App\Repository\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    /**
     * AccountRepository constructor
     * 
     * @param Account $model
     */
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $client_id
     * @return Model
     */
    public function findByClient(int $client_id): ?Model
    {
        return $this->model->where(['client_id' => $client_id])->firstOrFail();
    }
}