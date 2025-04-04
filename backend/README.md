# Backend Futside com Supabase e PHP

Este é o backend da aplicação Futside, utilizando Supabase como banco de dados e PHP para a API.

## Configuração

1. Instale o PHP em sua máquina (versão 7.4 ou superior)
2. Instale as extensões necessárias do PHP:
   - curl
   - json
   - pdo

3. Configure o Supabase:
   - Crie uma conta no [Supabase](https://supabase.com)
   - Crie um novo projeto
   - Copie a URL do projeto e a chave anon/public
   - Atualize o arquivo `config.php` com suas credenciais:
     ```php
     define('SUPABASE_URL', 'SUA_URL_DO_SUPABASE');
     define('SUPABASE_KEY', 'SUA_CHAVE_DO_SUPABASE');
     ```

## Estrutura do Banco de Dados

### Tabela: users
- id (uuid, primary key)
- email (string, unique)
- password (string)
- name (string)
- created_at (timestamp)

### Tabela: products
- id (uuid, primary key)
- name (string)
- description (text)
- price (decimal)
- image_url (string)
- created_at (timestamp)

## Endpoints da API

### Usuários
- POST /users.php?action=signup - Criar novo usuário
- POST /users.php?action=login - Login de usuário
- GET /users.php?userId={id} - Obter dados do usuário
- PATCH /users.php - Atualizar dados do usuário

### Produtos
- GET /products.php - Listar todos os produtos
- GET /products.php?id={id} - Obter produto específico
- POST /products.php - Criar novo produto
- PATCH /products.php - Atualizar produto
- DELETE /products.php?id={id} - Deletar produto

## Como Executar

1. Configure um servidor web local (Apache, Nginx, etc.)
2. Coloque os arquivos na pasta do servidor
3. Certifique-se que o PHP está configurado corretamente
4. Acesse a API através do navegador ou usando ferramentas como Postman

## Exemplo de Uso

### Criar um novo usuário
```bash
curl -X POST http://localhost/backend/users.php \
  -H "Content-Type: application/json" \
  -d '{"action":"signup","email":"usuario@email.com","password":"senha123","name":"Nome do Usuário"}'
```

### Listar produtos
```bash
curl http://localhost/backend/products.php
``` 