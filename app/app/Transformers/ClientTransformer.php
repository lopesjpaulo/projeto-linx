<?php
namespace App\Transformers;

use App\Models\Client;
use League\Fractal;

class ClientTransformer extends Fractal\TransformerAbstract
{
	public function transform(Client $client)
	{
	    return [
	        'client_id'      => (int) $client->client_id,
	        'nome'   => $client->name,
	        'nascimento'    =>  $client->birthday,
			'cpf'    =>  $client->cpf,
	        'created_at'    =>  $client->created_at->format('d-m-Y'),
	        'updated_at'    =>  $client->updated_at != null ? $client->updated_at->format('d-m-Y') : null
	    ];
	}
}