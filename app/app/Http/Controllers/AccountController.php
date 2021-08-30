<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Traits\ApiResponser;

class AccountController extends Controller
{
    use ApiResponser;

    public $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Cria uma conta
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $response = $this->accountService->criarAccount($request);

        if (isset($response)) {
            return $this->successResponse(['success' => true, 'data' => $response]);
        }

        return $this->errorResponse($response, $response['code']);
    }

    /**
     * Recupera lista das contas
     * 
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $response = $this->accountService->recuperarAccounts();

        if(isset($response)) {
            return $this->successResponse(['success' => true, 'data' => $response]);
        }

        return $this->errorResponse($response, $response['code']);
    }

    /**
     * Realiza um depósito
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request)
    {
        $response = $this->accountService->doDeposit($request);

        if(isset($response['success'])) {
            return $this->errorResponse('Houve um problema ao realizar o depósito', $response['code']);
        }

        return $this->successResponse($response);
    }

    /**
     * Realiza um saque
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Request $request)
    {
        $response = $this->accountService->doWithdrawal($request);

        if(isset($response['success'])) {
            return $this->errorResponse('Houve um problema ao realizar o saque', $response['code']);
        }

        return $this->successResponse($response);
    }
}
