<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Sistema de Cadastro de Clientes e Livros com Autenticação JWT (FEBACAPITAL)</h1>
    <br>
</p>

## Configuração do Ambiente
Para rodar o sistema localmente, siga as instruções abaixo:

### Instalação de Dependências:
```bash
composer install
```

### Configuração do Banco de Dados:
- Defina as configurações de banco de dados no arquivo `config/db.php`.

```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=db_febacapital',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8'
];

```

### Rodar Migrações:
```bash
php yii migrate
```

### Rodar o Servidor Local:
```bash
php yii serve
```

O servidor estará disponível em `http://localhost:8080`.

## 1. Autenticação
O sistema utiliza autenticação baseada em JWT (JSON Web Token). Para acessar as rotas protegidas, é necessário obter um token de acesso enviando credenciais de login (usuário e senha).

### Endpoints:
- **POST** `/auth/login`: Autentica um usuário e retorna um token JWT.
    - **Parâmetros**: 
        - `username`: Nome de usuário
        - `password`: Senha do usuário
    - **Retorno**: 
        - `token`: Bearer Token para ser usado nas requisições subsequentes.

- **Cabeçalho de Autenticação**: 
    - Todas as requisições às rotas protegidas devem conter o cabeçalho:
      ```
      Authorization: Bearer <token>
      ```

### Comando de Cadastro de Usuário:
- A criação de usuários é feita através de um comando CLI que recebe os seguintes argumentos:
    - `username`: Nome de usuário
    - `password`: Senha do usuário
  
```bash
 php yii user/create <username> <password>  
```

### Comando de Editar de Usuário:
- A edição de usuários é feita através de um comando CLI que recebe os seguintes argumentos:
    - `id`: Id de usuário
    - `username`: Nome de usuário
    - `password`: Senha do usuário
  
```bash
 php yii user/update <id> <username> [<password>]
```

### Comando de Eliminação de Usuário:
- A eliminação de usuários é feita através de um comando CLI que recebe os seguintes argumentos:
    - `id`: Id de usuário
  
```bash
php yii user/delete <id>
```


# API de Clientes e Livros

## Endpoints:

### 1. **Clientes**

#### - **POST** `/clients`: Cadastra um novo cliente.
   - **Parâmetros**: 
     - `name`: Nome do cliente (obrigatório)
     - `cpf`: CPF do cliente (validado via regex, obrigatório)
     - `address`: Endereço completo do cliente, contendo:
       - `zip`: CEP (obrigatório)
       - `street`: Logradouro (obrigatório)
       - `number`: Número (obrigatório)
       - `city`: Cidade (obrigatório)
       - `state`: Estado (obrigatório)
       - `complement`: Complemento (opcional)
     - `sex`: Sexo do cliente (M/F, obrigatório)
   - **Retorno**: 
     - Dados do cliente cadastrado ou erros de validação.

#### - **GET** `/clients`: Lista todos os clientes.
   - **Parâmetros** (query string, opcionais):
     - `limit`: Limite de registros por página (padrão: 10)
     - `offset`: Deslocamento para paginação (padrão: 0)
     - `sort`: Ordenação dos resultados (ex: `name`, `cpf`, `city`)
     - `filter`: Filtros para nome ou CPF (ex: `name=John`, `cpf=123.456.789-00`)
   - **Retorno**:
     - Lista paginada de clientes.

#### - **GET** `/clients/{id}`: Retorna os detalhes de um cliente específico.
   - **Parâmetros**:
     - `id`: ID do cliente (obrigatório, via URL)
   - **Retorno**:
     - Dados completos do cliente, incluindo o endereço, ou erro se o cliente não for encontrado.

#### - **PUT** `/clients/{id}`: Atualiza os dados de um cliente específico.
   - **Parâmetros**:
     - `id`: ID do cliente (obrigatório, via URL)
     - Dados semelhantes ao endpoint **POST** `/clients` para atualização:
       - `name`: Nome (opcional)
       - `cpf`: CPF (opcional)
       - `address`: Endereço completo (opcional)
       - `sex`: Sexo do cliente (opcional)
   - **Retorno**:
     - Dados atualizados do cliente ou erros de validação.

#### - **DELETE** `/clients/{id}`: Exclui um cliente específico.
   - **Parâmetros**:
     - `id`: ID do cliente (obrigatório, via URL)
   - **Retorno**:
     - Sucesso ou erro caso o cliente não seja encontrado.

---

### 2. **Livros**

#### - **POST** `/books`: Cadastra um novo livro.
   - **Parâmetros**:
     - `isbn`: ISBN do livro (obrigatório)
     - `title`: Título do livro (obrigatório)
     - `author`: Autor do livro (obrigatório)
     - `price`: Preço do livro (obrigatório)
     - `stock`: Quantidade disponível em estoque (obrigatório)
   - **Retorno**:
     - Dados do livro cadastrado ou erros de validação.

#### - **GET** `/books`: Lista todos os livros.
   - **Parâmetros** (query string, opcionais):
     - `limit`: Limite de registros por página (padrão: 10)
     - `offset`: Deslocamento para paginação (padrão: 0)
     - `sort`: Ordenação dos resultados (ex: `title`, `price`)
     - `filter`: Filtros para título, autor ou ISBN (ex: `title=SomeBook`, `author=John`, `isbn=1234567890123`)
   - **Retorno**:
     - Lista paginada de livros.

#### - **GET** `/books/{id}`: Retorna os detalhes de um livro específico.
   - **Parâmetros**:
     - `id`: ID do livro (obrigatório, via URL)
   - **Retorno**:
     - Dados completos do livro ou erro se o livro não for encontrado.

#### - **PUT** `/books/{id}`: Atualiza os dados de um livro específico.
   - **Parâmetros**:
     - `id`: ID do livro (obrigatório, via URL)
     - Dados semelhantes ao endpoint **POST** `/books` para atualização:
       - `isbn`: ISBN (opcional)
       - `title`: Título (opcional)
       - `author`: Autor (opcional)
       - `price`: Preço (opcional)
       - `stock`: Estoque (opcional)
   - **Retorno**:
     - Dados atualizados do livro ou erros de validação.

#### - **DELETE** `/books/{id}`: Exclui um livro específico.
   - **Parâmetros**:
     - `id`: ID do livro (obrigatório, via URL)
   - **Retorno**:
     - Sucesso ou erro caso o livro não seja encontrado.


## Documentação de API
As rotas da API seguem o padrão REST e todas as requisições e respostas são feitas no formato JSON. Para mais detalhes sobre as respostas e status HTTP, consulte os exemplos de cada endpoint.
