<?php

namespace App\Services;


use App\Repository\ClientRepositoryInterface;
use App\Transformers\ClientTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClientService
{
    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;

    private $fractal;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->fractal = new Manager();
    }

    private function getId($client)
    {
        return $client->client_id;
    }

    /**
     * Valida a requisição para a criação de um cliente
     * 
     * @param \Illuminate\Http\Request $request
     */
    private function validateRequest(Request $request): void
    {
        $rules = [
            'name'          => 'required|string',
            'birthday'      => 'required|string|max:10',
            'cpf'           => 'required|string|max:14'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            throw new ValidationException($validator);
    }

    /**
     * Valida a requisição para a atualização de um cliente
     * 
     * @param \Illuminate\Http\Request $request
     */
    private function validateUpdate(Request $request): void
    {
        $rules = [
            'name'          => 'string',
            'birthday'      => 'string|max:10',
            'cpf'           => 'string|max:14'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            throw new ValidationException($validator);
    }

    /**
     * Salva o cliente
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function criarClient(Request $request): array
    {
        $this->validateRequest($request);

        $client = $this->clientRepository->create($request->all());

        if($client){
            $resource = new Item($client, new ClientTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        return ['success' => false, 'code' => 400];
    }

    /**
     * Atualiza o cliente
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function atualizaClient(Request $request, int $id): array
    {
        $this->validateUpdate($request);

        $client = $this->clientRepository->find($id);

        if(!$client) return ['success'  => false, 'code' => 404];

        $returnUpdate = $this->clientRepository->update($request->all(), $this->getId($client));

        if($returnUpdate) {
            $resource = new Item($this->clientRepository->find($id), new ClientTransformer); 
            return $this->fractal->createData($resource)->toArray();
        }

        return ['success' => false, 'code' => 400];
    }

    /**
     * Exclui um cliente
     */
    public function excluiClient(int $id)
    {
        $client = $this->clientRepository->find($id);

        if(!$client) return ['success'  => false, 'code' => 404];

        $client_id = $this->clientRepository->delete($id);

        if($client_id) {
            return ['success' => true, 'code' => 200];
        }

        return ['success' => false, 'code' => 400];
    }

    /**
     * Recupera dados dos clientes
     * 
     * @return array
     */
    public function recuperarClients()
    {
        $paginator = $this->clientRepository->paginate(10);

        $clients = $paginator->getCollection();

        $resource = new Collection($clients, new ClientTransformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->fractal->createData($resource)->toArray();
    }
}
