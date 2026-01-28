# Task API - Laravel

API RESTful para gerenciamento de tarefas, desenvolvida com Laravel e autenticação por token utilizando Laravel Sanctum.

Projeto criado para demonstrar boas práticas de desenvolvimento, segurança e arquitetura pensada para evolução futura.

---

## 🚀 Tecnologias

- **PHP 8.1 ou superior** | **Laravel 12**
- **Sanctum** (Autenticação Stateless)
- **MySQL** | **Eloquent ORM**
- **Pest PHP** (Testes automatizados)

---

## 📦 Funcionalidades

- 🔐 **Autenticação**: Login seguro com geração de tokens.
- 📋 **CRUD de Tarefas**: Gerenciamento completo (Listar, Criar, Ver, Editar e Deletar).
- 🛡️ **Privacidade**: Cada usuário visualiza e interage apenas com suas próprias tarefas.
- 📅 **Soft Deletes**: Exclusão lógica para evitar perda acidental de dados.
- 🚦 **Versionamento**: API estruturada em `/api/v1`.

---

## 🧱 Arquitetura e Padrões

Este projeto foca em código limpo e manutenibilidade:

- **Form Requests**: Validações isoladas da lógica de negócio.
- **Policies**: Autorização centralizada para garantir a segurança dos dados.
- **API Resources**: Formatação padronizada de saída dos dados (JSON).
- **Separation of Concerns**: Controllers magros delegando responsabilidades.

---

## 🧪 Testes Automatizados

A API conta com testes de integração (Feature Tests) implementados com Pest, cobrindo autenticação, segurança e regras de negócio.

### 🔍 Cobertura de Testes

#### 🔐 Autenticação

- Login com credenciais válidas
- Login com senha ou email inválidos
- Validação de campos obrigatórios
- Logout autenticado
- Logout sem autenticação

#### 🛡️ Segurança

- Rotas protegidas retornam 401 sem token
- Usuários não acessam recursos de outros usuários (403)
- Tokens revogados não concedem acesso após logout

## ℹ️ Nota técnica

Durante os testes de logout, foi necessário lidar com o cache interno do Auth Guard do Laravel.
Para garantir a revalidação correta do token no mesmo processo de teste, foi criado um helper (resetAuthCache) no Pest.php, responsável por limpar os guards e forçar a autenticação a consultar novamente o banco de dados.

### 📋 Tarefas (Tasks)

- Listagem de tarefas do usuário autenticado
- Criação de tarefa
- Visualização de tarefa específica
- Atualização de tarefa
- Exclusão lógica (soft delete)
- Tentativas de acesso sem autenticação

## ▶️ Executando os Testes

Para rodar todos os testes:

```bash
php artisan test
```

Ou apenas um grupo específico:

```bash
php artisan test --filter=Task
php artisan test --filter=Login
php artisan test --filter=Logout
```

---

## ⚙️ Requisitos

Antes de iniciar, você precisa ter instalado:

- PHP 8.1 ou superior
- Composer
- MySQL

---

## 🛠️ Como rodar o projeto

1. **Clone o repositório:**

```bash
git clone https://github.com/seu-usuario/task-api-laravel.git
cd task-api-laravel
composer install
```

2. **Ambiente:**

```bash
cp .env.example .env
php artisan key:generate
```

Nota: Configure as credenciais do seu banco de dados no arquivo .env.

3. **Banco de Dados & Servidor:**

```bash
php artisan migrate --seed
php artisan serve
```

API disponível em: http://localhost:8000/api/v1

## 👤 Usuário de Teste e Autenticação

Após executar as migrations com --seed, utilize as credenciais abaixo para realizar o login:

```json
{
    "email": "appconsumer_001@api.com",
    "password": "Aa123456"
}
```

Login
POST /api/v1/login

Body:

Utilize as mesmas credenciais informadas acima.

A resposta conterá o token de acesso, que deve ser utilizado nas rotas protegidas.

Uso do Token
Authorization: Bearer SEU_TOKEN
Accept: application/json

## 📋 Endpoints Principais (v1)

| Método | Endpoint           | Descrição                | Protegido |
| ------ | ------------------ | ------------------------ | --------- |
| GET    | /api/v1/status     | Saúde da API             | ✅        |
| POST   | /api/v1/login      | Login e geração de token | ❌        |
| POST   | /api/v1/logout     | Logout                   | ✅        |
| GET    | /api/v1/tasks      | Lista tarefas do usuário | ✅        |
| POST   | /api/v1/tasks      | Cria nova tarefa         | ✅        |
| PUT    | /api/v1/tasks/{id} | Atualiza tarefa          | ✅        |
| DELETE | /api/v1/tasks/{id} | Soft delete de tarefa    | ✅        |

## 🔐 Exemplo de Autenticação

Faça login para receber o token.

Adicione-o ao Header das requisições protegidas: Authorization: Bearer {token}

## 📐 Padrão de Resposta

As respostas seguem uma estrutura previsível.

Sucesso (201 Created):

```json
{
    "success": true,
    "message": "Tarefa criada com sucesso",
    "data": {
        "id": 1,
        "title": "Estudar Laravel",
        "is_done": false
    }
}
```

Erro de Validação (422):

```json
{
    "success": false,
    "message": "Erro de validação",
    "errors": {
        "title": "O campo título é obrigatório."
    }
}
```

## 🧪 Testes Manuais (Postman)

Os testes manuais foram utilizados como apoio inicial durante o desenvolvimento.

A API foi validada manualmente utilizando Postman, cobrindo:

- Fluxo completo de autenticação por token
- Proteção de rotas com auth:sanctum
- Restrições de acesso entre usuários (Policies)

Respostas para:

- IDs inexistentes (404)
- Acesso não autorizado (401 / 403)
- Erros de validação (422)

## 🎯 Objetivo do Projeto

Este projeto foi desenvolvido com fins educacionais e de prática, com foco em:

- Consolidação de boas práticas em APIs com Laravel
- Uso de autenticação stateless com Sanctum
- Estruturação de uma API REST moderna

Não se trata de um projeto de produção, mas de uma base de aprendizado que servirá como referência para projetos futuros mais complexos.

## 👨‍💻 Autor

Bruno Mendes
