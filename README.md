
# Projeto Backend Laravel com Docker

Este projeto é um backend desenvolvido em Laravel, configurado para rodar em um ambiente Docker. O objetivo é oferecer uma API funcional com autenticação, gestão de carteiras e operações de transferência.

## Como iniciar o projeto

Para iniciar o projeto, basta executar o seguinte comando:

```bash
docker-compose up --build
```

Isso irá configurar e iniciar todos os serviços necessários, incluindo o Laravel e o banco de dados.

A API estará disponível publicamente na porta `8585` e o acesso ao banco de dados estará configurado com as seguintes credenciais:

- **Usuário:** root
- **Senha:** Cobuccio2025!!@@

## Rotas disponíveis

### Autenticação

**POST** `/auth/login`

```json
{
    "login": "admin",
    "password": "Cobuccio2025!!@@"
}
```

### Depósito na carteira

**POST** `/wallet/deposit`

```json
{
    "amount": 1.00
}
```

### Criação de usuário

**POST** `/open/user`

```json
{
    "name": "Douglas Dantas",
    "document": "085.107.734-08",
    "phone": "9812983",
    "billing_address": "@Aa12345678",
    "login": "douglas",
    "password": "@Aa12345678",
    "user_type": "admin"
}
```

### Transferência entre carteiras

**POST** `/wallet/transfer`

```json
{
    "sender_id": "01jhegpksamcgbd16rd6h5sz6b",
    "receiver_id": "01jhemagwvbbfh50w2vgn8zev2",
    "amount": 1.00
}
```

### Reversão de transferência

**POST** `/wallet/revert`

```json
{
    "transactionId": "19"
}
```

### Listagem de transferências

**GET** `/wallet/list-transfers`

### Listagem de usuários

**GET** `/users`

## Usuários padrão

Ao iniciar o sistema, os seguintes usuários serão criados automaticamente no banco de dados:

1. **Login:** user  
   **Senha:** Cobuccio2025!!@@

2. **Login:** admin  
   **Senha:** Cobuccio2025!!@@

## Observação

Certifique-se de que a porta `8585` esteja livre no ambiente onde o Docker será executado para evitar conflitos com outros serviços.
