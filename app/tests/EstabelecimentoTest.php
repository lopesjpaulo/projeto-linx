<?php

class EstabelecimentoTest extends TestCase
{
    private $header;

    public function __construct()
    {
        $this->header = [
            'HTTP_Authorization' => env('ACCEPTED_KEYS'),
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Teste de criação de estabelecimento
     *
     * @return void
     */
    public function createEstabelecimento()
    {
        $this->json('POST', '/estabelecimento', 
            [
                "nome" => "Estabelecimento teste",
                "cep" => "59123-321",
                "logradouro" => "Rua Teste",
                "numero" => "100",
                "bairro" => "Bairro Teste",
                "cidade" => "Cidade Teste",
                "estado" => "CE",
                "complemento" => "Complemento Teste"
            ],
            $this->header
        );
        $this->seeStatusCode(200);
    }

    /**
     * Teste de recuperação de estabelecimento
     * 
     * @return void
     */
    public function getEstabelecimento()
    {
        $this->json('GET', '/estabelecimento/1', [], $this->header);
        $this->seeStatusCode(200);
    }

    /**
     * Teste de exclusão de estabelecimento
     * 
     * @return void
     */
    public function deleteEstabelecimento()
    {
        $this->json('DELETE', '/estabelecimento/1', [], $this->header);
        $this->seeStatusCode(200);
    }
}