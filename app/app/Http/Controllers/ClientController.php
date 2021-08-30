<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Traits\ApiResponser;
use App\Traits\ConsumesExternalServices;

class ClientController extends Controller
{
    use ApiResponser, ConsumesExternalServices;

    public $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Cria um cliente
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $response = $this->clientService->criarClient($request);

        if (isset($response)) {
            return $this->successResponse(['success' => true, 'data' => $response]);
        }

        return $this->errorResponse($response, $response['code']);
    }

    /**
     * Recupera lista dos clientes
     * 
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $response = $this->clientService->recuperarClients();

        if(isset($response)) {
            return $this->successResponse(['success' => true, 'data' => $response]);
        }

        return $this->errorResponse($response, $response['code']);
    }

    /**
     * Atualiza um cliente pelo id
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $response = $this->clientService->atualizaClient($request, $id);

        if(isset($response['success'])) {
            return $this->errorResponse('Houve um problema ao atualizar', $response['code']);
        }

        return $this->successResponse($response);
    }

    /**
     * Exclui um cliente pelo id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        $response = $this->clientService->excluiClient($id);

        if($response['success']) {
            return $this->successResponse(['success' => true, 'id' => $response]);
        }

        return $this->errorResponse('Houve um problema ao excluir', $response['code']);
    }
}
