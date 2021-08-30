<?php 

namespace App\Services;

use App\Repository\AccountRepositoryInterface;
use App\Repository\ExtractRepositoryInterface;
use App\Transformers\AccountTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

class AccountService
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    private $fractal;

    /**
     * @var ExtractRepositoryInterface
     */
    private $extractRepository;

    public function __construct(AccountRepositoryInterface $accountRepository, ExtractRepositoryInterface $extractRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->extractRepository = $extractRepository;
        $this->fractal = new Manager();
    }

    private function getId($account)
    {
        return $account->account_id;
    }

    private function getBalance($account)
    {
        return $account->balance;
    }

    /**
     * Valida a requisição para a criação de uma conta
     * 
     * @param \Illuminate\Http\Request $request
     */
    private function validateRequest(Request $request): void
    {
        $rules = [
            'type'          => 'required|string:max:1',
            'balance'       => 'integer',
            'client_id'     => 'required|integer'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            throw new ValidationException($validator);
    }

    private function formatReturn($account)
    {
        $resource = new Item($account, new AccountTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Salva a conta
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function criarAccount(Request $request): array
    {
        $this->validateRequest($request);

        $account = $this->accountRepository->create($request->all());

        if($account){
           return $this->formatReturn($account);
        }

        return ['success' => false, 'code' => 400];
    }

    /**
     * Recupera dados das contas
     * 
     * @return array
     */
    public function recuperarAccounts()
    {
        $paginator = $this->accountRepository->paginate(10);

        $accounts = $paginator->getCollection();

        $resource = new Collection($accounts, new AccountTransformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Faz o depósito do valor na conta do cliente
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function doDeposit(Request $request): array
    {
        $this->validateValue($request);

        $requestData = $request->all();

        $account = $this->accountRepository->findByClient($requestData['client_id']);

        $returnUpdate = $this->accountRepository->update($this->calculateBalance($account, $requestData), $this->getId($account));

        $this->extractRepository->createDeposit($requestData);

        if($returnUpdate) {
            $resource = new Item($this->accountRepository->find($this->getId($account)), new AccountTransformer); 
            return $this->fractal->createData($resource)->toArray();
        }

        return ['success' => false, 'code' => 400];
    }

    /**
     * Calcula as notas retornadas no saque
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function doWithdrawal(Request $request): array
    {
        $this->validateValue($request);

        $requestData = $request->all();

        $account = $this->accountRepository->findByClient($requestData['client_id']);

        $balance = $this->checkBalance($account, $requestData);

        $notes = $this->calculateNote($requestData['value']);

        $returnUpdate = $this->accountRepository->update($balance, $this->getId($account));

        $this->extractRepository->createWithdraw($requestData);

        if($returnUpdate) {
            return $notes;
        }

        return ['success' => false, 'code' => 400];
    }

    /**
     * Calcula as notas resultantes do saque
     * 
     * @param int $value
     * @return array
     */
    private function calculateNote(int $value): array
    {
        $n100 = 0;
        $n50 = 0;
        $n20 = 0;
            
        if($value < 20){
            throw new Exception('Saque não é possível!', 400);
        }else{
            if($value >= 100){
                $n100 = intdiv($value, 100);
                $value = $value - ($n100 * 100);
            }
            if(($value > 0) && ($value % 20) == 0){
                $n20 = intdiv($value, 20);
                $value = 0;
            }
            if(($value > 0) && ($value % 50) == 0){
                $n50 = 1;
                $value = 0;
            }
            if(($value > 0) && ((($value % 50) == 20) || (($value % 50) == 40))){
                $n50 = 1;
                $n20 = intdiv(($value - 50), 20);
                $value = 0;
            }
	   
            if($value == 0){
                return (['n100' => $n100, 'n50' => $n50, 'n20' => $n20]);
            }else{
                throw new Exception('Saque não é possível!', 400);
            }
        }
    }

    /**
     * @param $account
     * @param array $requestData
     * @return array
     */
    private function calculateBalance($account, $requestData)
    {
        $balance = $this->getBalance($account);

        return ['balance' => $balance + $requestData['value']];
    }

    /**
     * Função que verifica se o cliente tem saldo
     * 
     * @param $account
     * @param array $requestData
     * @return array
     */
    private function checkBalance($account, array $requestData): ?array
    {
        $balance = $this->getBalance($account);
        $value = $requestData['value'];

        if($balance < $value) {
            throw new Exception('Saldo insuficiente!', 400);
        }

        return ['balance' => $balance - $value];
    }

    /**
     * Valida a requisição para a criação de um depósito
     * 
     * @param \Illuminate\Http\Request $request
     */
    private function validateValue(Request $request): void
    {
        $rules = [
            'value'          => 'required|integer',
            'client_id'      => 'required|integer'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            throw new ValidationException($validator);
    }
}