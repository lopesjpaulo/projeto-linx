<?php 

namespace App\Repository\Eloquent;

use App\Repository\ExtractRepositoryInterface;

class ExtractRepository implements ExtractRepositoryInterface
{
    /**
     * @param array $attributes
     */
    public function createWithdraw(array $attributes): void
    {
        $attributes['data'] = date('Y-m-d h:i:s');
        $attributes['type'] = 'withdraw';

        app('redis')->set($attributes['client_id'], json_encode($attributes));
    }

    /**
     * @param array $attributes
     */
    public function createDeposit(array $attributes): void
    {
        $attributes['data'] = date('Y-m-d h:i:s');
        $attributes['type'] = 'deposit';

        app('redis')->set($attributes['client_id'], json_encode($attributes));
    }
}