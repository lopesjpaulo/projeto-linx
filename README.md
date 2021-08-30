# Instruções para executar o projeto

## Clonar o projeto

## Rodar os comandos após o clone

```
docker-compose up -d
```
```
docker exec -ti projetolinx_app_1 bash -c "cd /var/www/html && composer install -vvv && cp .env.example .env && php artisan migrate"
```

## Requisição para o projeto

Utilizando o postman ou qualquer outro aplicativo do tipo

- URL BASE:

    http://localhost

    Header: 

    ```Authorization: key_secret```

- CLIENTE:

    /client

- POST:

    Criar um novo cliente

    Payload:

    ```
    {
        "name": "Jose",
        "birthday": "01/01/1990",
        "cpf": "50000-000",
    }
    ```
- GET:

    Recuperar todos os clientes

- GET

    Buscar um cliente pelo id

    /{id}

- PUT 

    Editar os dados de um cliente

    /{id}

    Payload:

    ```
    {
        "name": "Jose",
        "birthday": "01/01/1990",
        "cpf": "50000-000",
    }
    ```
- DELETE

    Excluir um cliente pelo id

    /{id}

## Testes

Executar com o PHPUnit:

```
vendor/bin/phpunit --filter=createEstabelecimento
vendor/bin/phpunit --filter=getEstabelecimento
vendor/bin/phpunit --filter=deleteEstabelecimento
```

## Demonstração

Dentro da pasta imagem, foram colocadas algumas imagens de requisições para os endpoints, usando o postman, para exemplificar a parte da api.