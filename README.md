# API Tasks — Laravel REST API

API RESTful para gerenciamento de tarefas, desenvolvida com Laravel e autenticação por token utilizando Laravel Sanctum.

## 🚀 Tecnologias

- PHP 8.1+ | Laravel 12
- Laravel Sanctum (autenticação stateless)
- MySQL | Eloquent ORM
- Pest PHP (testes automatizados)
- php-cs-fixer (padronização de código)

---

## 📦 Funcionalidades

- Autenticação via token com geração e revogação de tokens
- CRUD completo de tarefas
- Isolamento de dados por usuário via Policies
- Soft delete para exclusão lógica
- Versionamento de rotas em /api/v1
- Respostas padronizadas via ApiResponse service

---

## 🧱 Arquitetura e Padrões

- Form Requests para validação desacoplada dos controllers
- Policies para autorização centralizada
- API Resources para formatação consistente do JSON
- Controllers magros com responsabilidades únicas
- ApiResponse service centralizando todos os formatos de resposta

---

## 📋 Endpoints (v1)

| Método | Endpoint           | Descrição                            | Auth |
| ------ | ------------------ | ------------------------------------ | ---- |
| POST   | /api/v1/login      | Login e geração de token             | ❌   |
| POST   | /api/v1/logout     | Logout e revogação do token          | ✅   |
| GET    | /api/v1/tasks      | Lista tarefas do usuário autenticado | ✅   |
| POST   | /api/v1/tasks      | Cria nova tarefa                     | ✅   |
| GET    | /api/v1/tasks/{id} | Exibe uma tarefa                     | ✅   |
| PUT    | /api/v1/tasks/{id} | Atualiza tarefa                      | ✅   |
| DELETE | /api/v1/tasks/{id} | Soft delete de tarefa                | ✅   |

## 🧪 Testes Automatizados

Suíte de testes de integração com Pest cobrindo:

- Login com credenciais válidas e inválidas
- Logout e revogação de token
- Rotas protegidas retornando 401 sem token
- Isolamento entre usuários (403 ao tentar acessar recurso alheio)
- CRUD completo com cenários de sucesso, validação e not found
- Verificação de que user_id malicioso no payload é ignorado

## ℹ️ Nota técnica

Durante os testes de logout, foi necessário lidar com o cache interno do Auth Guard do Laravel. Um helper `resetAuthCache` foi criado no `Pest.php` para forçar a revalidação do token no mesmo processo de teste.

## ⚙️ Instalação

```bash
git clone https://github.com/BrunoMendes20/API-tasks
cd API-tasks
composer install
cp .env.example .env
php artisan key:generate
```

Configure o banco de dados no `.env` e execute:

```bash
php artisan migrate --seed
php artisan serve
```

## 🔐 Autenticação

```bash
POST /api/v1/login
{
    "email": "appconsumer_001@api.com",
    "password":
}
```

As credenciais de acesso são definidas no seeder. Consulte `database/seeders/DatabaseSeeder.php`.

Use o token retornado no header das requisições protegidas:

```
Authorization: Bearer {token}
Accept: application/json
```

## 📐 Padrão de Resposta

```json
{
    "success": true,
    "message": "Operação realizada com sucesso",
    "data": { ... }
}
```

## ▶️ Executar Testes

```bash
php artisan test
php artisan test --filter=Task
php artisan test --filter=Login
```

## 📌 Sobre o Projeto

Desenvolvido para prática e estudo de arquitetura REST com Laravel.
Serve como referência para projetos futuros mais complexos.

## 👨‍💻 Autor

Bruno Mendes

- [brunomendes.tech](https://brunomendes.tech)
- [brunomendesteck@gmail.com](mailto:brunomendesteck@gmail.com)
