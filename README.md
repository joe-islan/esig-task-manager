# ESIG Task Manager

Uma aplicação Laravel 12 para gerenciamento de tarefas com interface web moderna (Tailwind CSS) e API RESTful documentada via Swagger. Inclui integração com OpenAI ChatGPT para sugestões de execução de tarefas e cobertura completa de testes unitários e de feature.

---

## 🚀 Tecnologias & Requisitos

- PHP ^8.2
- Laravel Framework ^12.0
- Banco de dados PostgreSQL
- darkaonline/l5-swagger ^9.0 (Swagger/OpenAPI)
- laravel/sanctum ^4.0 (API authentication)
- laravel/tinker ^2.10.1
- Tailwind CSS (frontend)
- PHPUnit (testes)

### Extensões PHP necessárias

- pdo_pgsql
- mbstring
- openssl
- curl

---

## 📥 Clonando o Projeto

```bash
git clone https://github.com/joe-islan/esig-task-manager.git
cd esig-task-manager
```

## ⚙️ Instalação

1. Instale dependências Composer:

   ```bash
   composer install
   ```

2. Copie o `.env.example` para `.env`:

   ```bash
   cp .env.example .env
   ```

3. Gere a chave da aplicação:

   ```bash
   php artisan key:generate
   ```

4. Configure conexão com PostgreSQL no `.env`:

   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=esig
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

5. Execute migrações:

   ```bash
   php artisan migrate
   ```

---

## 💻 Executando a Aplicação

```bash
php artisan serve
```

Acesse: http://localhost:8000 para interface web.

---

## 📖 Documentação da API (Swagger)

Após iniciar o servidor, a documentação Swagger estará disponível em:

```
http://localhost:8000/api/documentation
```

Para regenerar a documentação:

```bash
php artisan l5-swagger:generate
```

---

## 🤖 Integração ChatGPT

Para testar o endpoint ChatGPT (`POST /api/v1/tasks/{id}/chatgpt`), adicione sua chave OpenAI no `.env`:

```dotenv
OPENAI_API_KEY=sk-your-openai-key
```

---

## ✅ Testes

Execute todos os testes (unitários + feature) com:

```bash
php artisan migrate --env=testing
php artisan test
```

Você verá cobertura completa dos serviços, controladores de API e interface.

---

## 🎨 Frontend

O layout foi construído com **Tailwind CSS** para uma melhor performance e design moderno ao invés de Bootstrap(Mas poderia ter usado tranquilamente).

---

## 📂 Estrutura de Pastas

- **app/Http/Controllers** — Controllers API e web
- **app/Services** — Lógica de negócio
- **app/Repositories** — Acesso a dados
- **resources/views** — Templates Blade (Tailwind)
- **routes/api.php** — Endpoints RESTful
- **routes/web.php** — Rotas da interface

---

## 💡 Observações

- Caso queira usar Laravel Sail, crie o arquivo `docker-compose.yml` conforme documentação oficial.
- Em produção, proteja a rota `/api/documentation` via middleware.

---

Made with ❤️ by Joe Islan
