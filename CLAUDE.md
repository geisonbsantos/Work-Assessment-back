# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Documentação do projeto (Obsidian) — consulta obrigatória

A documentação viva do projeto (PRD, SDD, RPI e referências) mora **fora do
repositório**, no cofre Obsidian **Safe_WA**:
`/mnt/c/Users/USUÁRIO/Desktop/Obsidian/Safe_WA` (Windows: `C:\Users\USUÁRIO\Desktop\Obsidian\Safe_WA`).
O caminho está em `WA_OBSIDIAN_VAULT` (`.claude/settings.local.json`).

**Antes de responder qualquer pedido ou escrever código**, leia as notas
pertinentes do cofre e fundamente a resposta nelas. Um hook `UserPromptSubmit`
(`.claude/hooks/obsidian-consulta.sh`) injeta o índice das notas a cada mensagem.

- Comece por `Índice.md`. Documentos: `PRD/`, `SDD/`, `RPI/` (uma nota por feature:
  **R**equisitos → **P**lanejamento → **I**mplementação), `Templates/`, `Referências/`.
- Fluxo de mudança: criar/atualizar o `RPI/RPI-XXXX — <nome>.md` (fechar Requisitos
  e Planejamento) → atualizar PRD/SDD se produto ou arquitetura mudarem → só então
  implementar → registrar o resultado na seção **Implementação** do RPI.
- Se a informação necessária **não estiver no cofre**, diga isso explicitamente e
  proponha criar/atualizar a nota antes de prosseguir. O código segue a documentação.

## What this is

A token-authenticated REST API built on Laravel 12 / PHP 8.3. It began as the SESAB/DMA
"StarterPack" (a shared base for Bahia state health-department projects), so most of the
scaffolding is generic and the domain code (users, profiles, abilities, unities, sectors,
expertise areas, FAQ) sits on top of it. The codebase — comments, commit messages, API
response strings, validation messages — is in **Portuguese**; keep new user-facing text in
Portuguese.

Production runs against **Oracle 19c** via `yajra/laravel-oci8`. Local development and the
test suite use **SQLite** (`database/database.sqlite`, or `:memory:` under phpunit).

## Commands

```bash
composer install
php artisan key:generate            # only if APP_KEY is empty
php artisan migrate --seed          # seeders assume an empty DB; profile id 1 = ADMINISTRADOR
php artisan serve                   # http://localhost:8000

composer teste                      # run the Pest suite (alias for ./vendor/bin/pest)
php artisan test                    # same suite via artisan
./vendor/bin/pest tests/Feature/Api/Profille/ProfileControllerTest.php   # one file
./vendor/bin/pest --filter="create a new profile"                        # one test by name

./vendor/bin/pint                   # format (Laravel preset; see .styleci.yml, no_unused_imports disabled)
```

Docker: `docker-compose build && docker-compose up -d` → Nginx on `:9080`, Vite on `:5173`,
Xdebug on `9003`. `make clear` / `make optimize` wrap `artisan optimize:clear` inside the container.

### Module scaffolding

`php artisan make:module {Name}` generates a full vertical slice from `stubs/` —
Controller, Service, Repository, Model, `StoreUpdate{Name}FormRequest`, migration, factory,
seeder (auto-registered in `DatabaseSeeder`), and a Pest test (`make:genericTest`). Use this
rather than hand-creating the layers; it encodes the conventions below.

## Architecture

Request flow: **route → `App\Http\Controllers\Api\*` → `App\Services\*` → `App\Repositories\Core\*` → Eloquent model**.

- **`CrudController`** (`app/Http/Controllers/Api/CrudController.php`) is the generic base.
  Its constructor takes an `object $service`; subclasses inject their concrete Service and
  call `parent::__construct($service)`. It provides `index/store/show/update/destroy` that
  just delegate to `$this->service` and return standard Portuguese JSON messages.
- **`index()` dispatch**: `?per_page=N` → `service->paginate()`; any other query string →
  `service->applyFilter()`; no input → `service->getAll()`. Several modules bypass this with
  a dedicated `GET /filter` route calling a `filter()` method instead.
- **`beforeStore` / `beforeUpdate`**: routes point at these `protected` methods (not
  `store`/`update` directly). They type-hint the module's `StoreUpdate*FormRequest` so
  validation runs, then call the inherited `store`/`update`. Protected visibility works
  because Laravel's `callAction` executes in the controller-instance scope.
- **Services** wrap repository output in `App\Http\Resources` Resources / Collections, and
  hold cross-cutting logic (slug prep, audit logging, mail). Some implement an interface in
  `App\Repositories\Contracts`, most don't.
- **Repositories**: `BaseRepository` implements `RepositoryInterface` over an injected model
  (`$entity`). Concrete repos extend it, re-declare `$entity` with a concrete type, re-assign
  it in the constructor after `parent::__construct`, and override `store`/`update`/`filter`
  where they need transactions, relations, or `LIKE` filtering. Only
  `UserRepositoryInterface → UserRepository` is container-bound
  (`RepositoryServiceProvider`); everything else is resolved as the concrete class.
- **Slugs**: `CreateSlugHelpers::prepareDataForStore()` derives `slug` from `name` or
  `description` in the Service before `store`.
- **Soft deletes** are used throughout. Models expose `restore()` and there are
  `PUT /restore/{id}` routes; list/filter queries frequently include `withTrashed()`.

### Auth & authorization

- **Login is by CPF + password** (`POST /api/login`), not email. `UserService::login`
  deletes the user's existing tokens (single active session), then issues a Sanctum
  plain-text token whose **abilities are the slugs of the user's profile's abilities**,
  expiring after `TOKEN_SANCTUM_EXPIRATION` minutes (480; `config/sanctum.php`).
- Protected routes sit in the `['auth:sanctum', 'refreshTokenSanctum']` group.
  `RefreshTokenSanctum` middleware re-syncs a token's abilities from the profile every 5
  minutes and re-emits the `Authorization` header.
- Per-route permission checks use `->middleware(['abilities:<slug>'])` (Sanctum
  `CheckAbilities`). Slugs live in the `abilities` table, seeded by `AbilitySeeder` /
  `ProfileAbilitySeeder`.
- RBAC model graph: `User` → `Profile` (via `profile_id`) → `ProfileAbility` → `Ability`.
  `User` also relates to `Unity`, `Sector`, and `ExpertiseArea` (many-to-many via
  `user_expertise_areas`). The `User` model auto-hashes `password` and upper-cases `name`
  via mutators, and is `Auditable` (`owen-it/laravel-auditing`, `audits` table).

### Legacy skeleton on a modern framework

Despite being Laravel 12, this project keeps the **pre-11 application skeleton**:
`app/Http/Kernel.php` and `app/Console/Kernel.php` (middleware aliases and command loading
live here), a classic `bootstrap/app.php`, the `config/app.php` `providers` array, and
`RouteServiceProvider`. There is no fluent `bootstrap/app.php` config and no
`bootstrap/providers.php` — register providers/middleware the old way.

## Testing conventions

- Pest 3; all tests are in `tests/Feature` (`tests/Unit` is referenced by config but doesn't exist).
- `RefreshDatabase` is deliberately **off**. Instead each test file wraps itself in
  `DB::beginTransaction()` / `DB::rollBack()` via `beforeEach`/`afterEach`, and tests assume
  the **seeded** dataset is present (e.g. `GET /api/profiles/1` returns `ADMINISTRADOR`).
  Run `php artisan migrate --seed` against the sqlite file before running tests locally.
- Get an authenticated token with the `createUserFactoryGetToken()` helper; pass it as
  `['Authorization' => 'Bearer '.$token]`.
- Only `User` and `Profile` have factories.

## Other notable pieces

- `laravellegends/pt-br-validator` provides `cpf` / `cnpj` validation rules used in FormRequests.
- Broadcasting uses **Laravel Reverb**; `UserUpdatedEvent` fires from `UserUpdatedObserver`.
- Log viewer UI: `opcodesio/log-viewer`. Captcha: `mews/captcha`.
- SonarQube is wired up via `sonar-project.properties` (coverage expected at `testresults/test-clover.xml`).
- `.env` and `.env.testing` are committed and both default to `APP_ENV=testing` /
  `DB_CONNECTION=sqlite`. Copy from them for local setup; real credentials are not in the repo.
