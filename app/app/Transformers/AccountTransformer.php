<?php
namespace App\Transformers;

use App\Models\Account;
use League\Fractal;

class AccountTransformer extends Fractal\TransformerAbstract
{
	public function transform(Account $account)
	{
	    return [
	        'account_id'      => (int) $account->account_id,
	        'type'   => $account->type,
            'balance'    =>  $account->balance,
            'client'     =>  $account->client->name,
	        'created_at'    =>  $account->created_at->format('d-m-Y'),
	        'updated_at'    =>  $account->updated_at != null ? $account->updated_at->format('d-m-Y') : null
	    ];
	}
}