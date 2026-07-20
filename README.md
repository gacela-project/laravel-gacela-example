# Laravel + Gacela Example

A small, runnable showcase of how to build **[Gacela](https://gacela-project.com/) modules inside a
[Laravel](https://laravel.com/) 12 application**. If you have never seen Gacela before, this README is
meant to get you productive in a few minutes.

> **Stack:** Laravel 12 · Gacela 1.18 · PHP 8.2+ · Vite · PHPStan (larastan) · Pint

## What is Gacela?

Gacela is a lightweight framework for structuring an application into **decoupled modules**. Every
module exposes a thin, well-defined surface built from **four pillars**:

| Pillar | Responsibility |
|--------|----------------|
| **Facade** | The only public entry point of the module. Delegates to the Factory. |
| **Factory** | Wires the module's internal classes (application services, etc.) together. |
| **Config** | Typed access to configuration values for the module. |
| **Provider** | Declares the module's dependencies / bindings (optional). |

Pairing it with Laravel gives you the best of both worlds: Laravel handles HTTP, routing, Eloquent,
queues and the ecosystem, while Gacela keeps your **business logic framework-agnostic** and cleanly
separated behind module boundaries.

## How Gacela wires into Laravel (in this repo)

### Autoload split: `App\` vs `Src\`

```jsonc
// composer.json
"App\\": "app/",   // Laravel land: controllers, commands, Eloquent models, providers
"Src\\": "src/",   // Gacela land: your framework-agnostic modules
```

`app/` is the Laravel skeleton. `src/` holds Gacela modules. Controllers/commands in `App\` call into
`Src\` modules through their Facade — never the other way around.

### Where Gacela boots

Gacela is bootstrapped in **[`bootstrap/app.php`](bootstrap/app.php)**, right after Laravel's
application is created (so the Laravel container/helpers are already available):

```php
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(...)
    ->withCommands([...])
    ->create();

Gacela::bootstrap($app->basePath()); // <-- reads gacela.php

return $app;
```

### The bootstrap config: `gacela.php`

[`gacela.php`](gacela.php) at the project root tells Gacela how to read config and resolve bindings:

```php
return static function (GacelaConfig $config) {
    $config
        // 1. Read environment variables (.env*) through Gacela's env reader
        ->addAppConfig('.env*', '.env', EnvConfigReader::class)
        // 2. Feed Laravel's own config/*.php files into Gacela's config store
        ->addAppConfig('config/*.php')
        // 3. Bind the module interface to its Eloquent-backed implementation
        ->addBinding(ProductRepositoryInterface::class, ProductRepository::class);
};
```

The `addAppConfig('config/*.php')` glob is the bridge that makes **Laravel's configuration readable from
inside Gacela modules** (e.g. `ProductConfig` reads `DEFAULT_PRODUCT_PRICE`). The `addBinding(...)` line
is what lets the Factory receive a concrete `ProductRepository` wherever a `ProductRepositoryInterface`
is requested.

## The Product module: request flow

The golden rule: **controllers and commands never touch an Eloquent model directly**. They go through
the Facade, and only the repository — the module's Infrastructure layer — talks to the database.

```
HTTP request  ─▶  ListProductController (App\)
                        │  getFacade()
                        ▼
                  ProductFacade (Src\Product)          ◀── the module's only entry point
                        │  getFactory()->createProductLister()
                        ▼
                  ProductLister (Application service)
                        │  depends on
                        ▼
                  ProductRepositoryInterface (Domain)  ◀── bound in gacela.php
                        │  resolves to
                        ▼
                  ProductRepository (Infrastructure)   ◀── the ONLY place that uses Eloquent
                        │
                        ▼
                  App\Models\Product (Eloquent)  ─▶  database
```

Data crossing the boundary is a **`ProductTransfer`** DTO (Domain), not an Eloquent model — so the
Facade never leaks framework types to its callers.

### Module layout

```
src/Product
├── Application/                 # use cases (no framework dependencies)
│   ├── ProductCreator.php
│   └── ProductLister.php
├── Domain/                      # contracts + DTOs
│   ├── ProductRepositoryInterface.php
│   └── ProductTransfer.php
├── Infrastructure/              # adapters — the only layer allowed to touch Eloquent
│   └── Repository/ProductRepository.php
├── ProductConfig.php            # Config pillar
├── ProductFacade.php            # Facade pillar
└── ProductFactory.php           # Factory pillar
```

`src/Shared` holds cross-module helpers (e.g. `AbstractTransfer`).

## Getting started

**Requirements:** PHP 8.2+, Composer, Node.js 20+.

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment + app key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database and run migrations
php artisan gacela:create-sqlite      # interactive helper
#   ...or manually:
#   touch database/database.sqlite && php artisan migrate

# 4. Build front-end assets (Vite)
npm run build      # or `npm run dev` for the dev server

# 5. Serve
php artisan serve
```

## Using the Product module

### Console commands

Two commands drive the module, each showing a different (valid) way to reach a Gacela Facade:

- **`AddProductCommand`** — a Laravel command that receives the Facade via **constructor injection**.
- **`ListProductCommand`** — a Symfony command that resolves the Facade via Gacela's
  `ServiceResolverAwareTrait` (the `@method ProductFacade getFacade()` doc-block).

```bash
php artisan gacela:product:add Keyboard        # uses DEFAULT_PRODUCT_PRICE (49)
php artisan gacela:product:add Monitor 150
php artisan gacela:product:list
```

### Routes

Controllers resolve the Facade with `ServiceResolverAwareTrait`.

| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/` | — | welcome page |
| GET | `/list` | `product_list` | `App\Http\Controllers\Product\ListProductController` |
| GET | `/add/{name}/{price?}` | `product_add` | `App\Http\Controllers\Product\AddProductController` |

```bash
php artisan route:list
```

## Adding a new module

Gacela's scaffolder is wired into Laravel's `artisan` in this repo, so you can generate a fully-formed
module without writing boilerplate:

```bash
# Facade + Factory + Config + Provider only
php artisan make:module Src/Basket

# ...or a "service" module (Facade wired to a Domain service) plus a GacelaTestCase test
php artisan make:module Src/Basket --template=service --with-tests
```

This creates `src/Basket/BasketFacade.php`, `BasketFactory.php`, `BasketConfig.php`,
`BasketProvider.php` (and, with `--template=service`, a `Domain/BasketService.php` and
`Tests/BasketFacadeTest.php`). Add any bindings in `gacela.php` (or the generated Provider) and you are
ready to go.

## Inspecting modules

The Gacela debug commands are also exposed through `artisan`:

```bash
php artisan list:modules           # table of every module and which pillars it defines
php artisan debug:module Product   # resolved Facade/Factory/Config + container bindings + dep tree
php artisan debug:graph            # module dependency graph (who imports whom)
```

For example, `php artisan debug:module Product` prints the resolved classes and the
`ProductRepositoryInterface => ProductRepository` binding declared in `gacela.php`.

## Testing

The Laravel feature/unit suites live in `tests/`:

```bash
composer test          # runs both suites
composer test-unit
composer test-feature
```

Modules generated with `--with-tests` extend Gacela's **`GacelaTestCase`**, which boots the module in
isolation:

```php
use Gacela\Framework\Testing\GacelaTestCase;

final class BasketFacadeTest extends GacelaTestCase
{
    public function test_execute(): void
    {
        $this->bootstrapGacela(__DIR__);

        self::assertSame('...', (new BasketFacade())->execute());
    }
}
```

## Quality tooling

```bash
composer pint          # Laravel Pint — code style (auto-fix)
composer pint-test     # Pint in check-only mode
composer phpstan       # PHPStan level 6 (larastan + Gacela module-boundary rules)
```

CI runs the whole matrix (PHP 8.2 / 8.3 / 8.4) plus the Vite build — see
[`.github/workflows/ci.yml`](.github/workflows/ci.yml).

---

Read the full framework documentation at **[gacela-project.com](https://gacela-project.com/)**.
