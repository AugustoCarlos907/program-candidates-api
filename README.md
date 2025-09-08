# Inscrições & Seleção API (Laravel)

Mini-plataforma de **Inscrições & Seleção** para gerir **Programas, Candidatos e Candidaturas**, com autenticação por **Laravel Sanctum**, CRUDs e filtros.

> Este README traz **setup completo**, **comandos** e **instruções detalhadas** para levantar o projeto do zero, executar as migrations/seeders, e consumir a API com exemplos práticos de requests.

---

##  Stack & Requisitos

- **Laravel**: 11/12
- **PHP**: 8.3+
- **Banco de Dados**:  MySQL
- **Autenticação**: Laravel Sanctum (tokens Bearer)
- **Documentação/Tests de API**: Postman Collection (pasta `postman/`)


---

##  Setup Rápido

### 1) Clonar o projeto e instalar dependências
```bash
git clone <https://github.com/AugustoCarlos907/program-candidates-api.git > inscricoes-api
composer install
```

### 2) Variáveis de ambiente
```bash
cp .env.example .env
php artisan key:generate
```

Edite `.env` e configure o **banco** ( MySql):

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=programa_candidatos_api
DB_USERNAME=root
DB_PASSWORD=
```

### 3) Sanctum
Instale e publique o Sanctum (caso ainda não esteja instalado):
```bash
php artisan install:api
```



### 4) Migrar & Popular o banco
```bash
php artisan migrate --seed
```
- As **migrations** criam as tabelas de **candidatos**, **programas**, **candidaturas** e tabelas do Sanctum.
- Os **seeders** podem pré-cadastrar **Programas**


### 5) Rodar o servidor
```bash
php artisan serve
```
A API ficará disponível em: `http://127.0.0.1:8000` .

-----------------------------------------------------------------------------------------------------------

##  Modelo de Dados

### Entidades
-**User**
 - `id`, `nome`, `email (unique)`, `password (hash)`
- **Candidato**
  - `user_id `, `genero (enum:[M, F])`,`telefone `,`data_nascimento`,`nacionalidade`,`endereco`
- **Programa**
  - `id`, `nome`, `descricao`, `data_inicio`, `data_fim`, `estado (enum: activo|pendente)`
- **Candidatura** (tabela pivô — muitos-para-muitos **Candidato ↔ Programa**)
  - `id`, `candidato_id (FK)`, `programa_id (FK)`, `estado (enum: aprovado|reprovado|pendente)` ,timestamps

### Regras de Negócio
1) Um **Candidato** pode candidatar-se a **vários Programas**.  
2) Submissão de candidatura **exige login** (token válido).  
3) Programa **só aceita candidaturas** se `estado = activo` **e** `data_inicio ≤ hoje ≤ data_fim`.  
4) **Não duplicar** candidaturas para o mesmo par (`candidato_id`, `programa_id`).

---

## 📦 Estrutura de Pastas (resumo)

```
app/
  Http/
    Controllers/
      AuthController.php
      ProgramaController.php
      CandidaturaController.php
      CandidatoController.php

  Requests/
  LoginRequest.php
  RegisterRequest.php
  StoreCandidaturaRequest.php
  StoreProgramaRequest.php
  UpdaterogramaRequest.php

  Resources/
  CandidatoResource.php
  CandidaturaResource.php
  ProgramaResource.php


  Models/
    User.php
    Candidato.php
    Programa.php
    Candidatura.php

  Providers/
  AppServiceProvider.php

  Services/
  CandidaturaService.php
  
database/
  migrations/
  seeders/
routes/
  api.php
postman/
  programa_candidatos_api
```

-----------------------------------------------------------------------------------------------------------

##  Autenticação (Sanctum)


### Login + Emissão de Token
- **POST** `/api/login`
- **Body (JSON)**:
  ```json
  {
    "email": "ana@example.com",
    "password": "secret"
  }
  ```
- **Resposta (200)**:
  ```json
  {
    "token": "plain_text_token_aqui",
    "candidato": {
      "id": 1,

      "nome": "Ana Silva",
      "email": "ana@example.com"
    }
  }
  ```
Guarde o `token` e envie nos próximos requests:

```
Authorization: Bearer <token>
```

### Logout (revogar token atual)
- **POST** `/api/logout`  
  (requer header `Authorization: Bearer <token>`)

-----------------------------------------------------------------------------------------------------------

##  Endpoints (Principais)

### Programas
- **GET** `/api/programas`
  - **Query Params (opcionais)**:
    - `estado=activo` (filtra por estado)
  - **Resposta (200)**:
    ```json
    [
      {
        "id": 3,
        "nome": "Programa Jovens Talentos",
        "descricao": "Descrição...",
        "data_inicio": "2025-08-01",
        "data_fim": "2025-10-30",
        "estado": "activo"
      }
    ]
    ```

- **POST** `/api/programas` (opcional/admin)
  - **Body**: `nome`, `descricao` (opcional), `data_inicio`, `data_fim`, `estado`
  - Valida `data_fim >= data_inicio`.

### Candidaturas (requer autenticação)
- **GET** `/api/candidaturas` → Lista as **minhas** candidaturas.
- **POST** `/api/candidaturas`
  - **Body (JSON)**:
    ```json
    {
      "programa_id": 3
    }
    ```
  - **Validações**:
    - Candidato **logado**;
    - Programa **existe**;
    - `programa.estado === "activo"`;
    - `programa.data_inicio ≤ hoje ≤ programa.data_fim`;
    - **Não permitir duplicado** do par (`candidato_id`, `programa_id`).
  - **Resposta (201)**:
    ```json
    {
      "message": "Candidatura submetida com sucesso!",
      "candidatura": {
        "id": 10,
        "programa_id": 3,
        "candidato_id": 1,
        "estado": "aprovado"
      }
    }
    ```

- **DELETE** `/api/candidaturas/{id}` → Cancela a minha candidatura (opcional).

---------------------------------------------------------------------------------------------------------


## 🧑‍🍳 Seeders & Factories

- **Programas**: crie seeds para popular com diferentes datas e estados (`activo|pendente`).
- **Candidatos**: pelo menos 1 candidato de teste.
- **Factories**: mantenha coerência das datas (ex.: `data_inicio` ≤ `data_fim`).

**Comandos úteis:**
```bash
php artisan migrate:fresh --seed   # recria tudo do zero
php artisan db:seed              # roda seeders
```

-------------------------------------------------------------------------------------------------------

##  Tratamento de Erros utilizados durante o desenvolvimento

- **401 Unauthorized**: tentar candidatar sem token válido.
- **404 Not Found**: programa ou candidatura inexistente.

Exemplo de resposta de erro:
```json
{
  "message": "Já se candidatou a este programa."
}
```

----------------------------------------------------------------------------------------------------------
## Submissão

- Repositório GitHub público **ou** ZIP do projeto
- Incluir **README** (este) + **Postman Collection**
- Se possível, **credenciais demo***
