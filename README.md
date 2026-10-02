# Laravel + Gacela Example

A small, runnable showcase of how to build **[Gacela](https://gacela-project.com/) modules inside a
[Laravel](https://laravel.com/) 12 application**. If you have never seen Gacela before, this README is
meant to get you productive in a few minutes.

> **Stack:** Laravel 12 · Gacela 2.6 · Octane · PHP 8.3+ · Vite · PHPStan (larastan) · Pint

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

Gacela ships a Laravel service provider inside the framework package, so there is nothing extra to
require and nothing to bootstrap by hand. Registering it in
**[`bootstrap/providers.php`](bootstrap/providers.php)** is the whole integration:

```php
return [
    AppServiceProvider::class,
    Gacela\LaravelBridge\GacelaServiceProvider::class,
];
```

That gives you five things:

1. **Gacela bootstrapped when the application boots**, with `base_path()` as the app root — so
   [`gacela.php`](gacela.php) is read without an explicit `Gacela::bootstrap()` call.
2. **Laravel services reachable from Gacela** — the ones you list in `external_services`, and only
   those. This app needs none: its repository reaches Eloquent directly.
3. **Gacela's console commands in `artisan`**, under a `gacela:` prefix. All of them, not a
   hand-picked few. The prefix is not decoration — artisan owns the whole `make:*` namespace.
4. **`artisan optimize` warms Gacela's caches too**, so a deploy has one optimize step instead of
   two. `optimize:clear` clears them again.
5. **A clean slate for each Octane request.** The bridge listens to Octane's `RequestReceived`
   and `RequestTerminated` and calls `Gacela::resetRequestState()`, so a worker keeps its warm
   caches but drops the Factories and the services they built, even after a request that threw. See [Running under Octane](#running-under-octane).

### Configuring the bridge: `config/gacela.php`

Published with `php artisan vendor:publish --tag=gacela-config`. Every key here is left at its
default; each one is commented in [`config/gacela.php`](config/gacela.php). Two are worth knowing
about before a deploy:

- `file_cache` is **off**, which is Gacela's own default. Turn it on in production and `artisan
  optimize` writes Gacela's resolution cache alongside Laravel's.
- `cache_dir` defaults to the **system temp directory**, not somewhere under the app. If you enable
  `file_cache`, point this at `storage_path('framework/gacela')`.

An unknown or mistyped key fails at boot naming the key — Laravel has no compile step where a
validated config tree could catch it.

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

The `addAppConfig('config/*.php')` glob is what makes **Laravel's configuration readable from inside
Gacela modules** (e.g. `ProductConfig` reads `DEFAULT_PRODUCT_PRICE`). The `addBinding(...)` line is
what lets the Factory receive a concrete `ProductRepository` wherever a `ProductRepositoryInterface`
is requested.

`gacela.php` stays the place for anything Gacela-specific; `config/gacela.php` configures the
*bridge*. The two are different files with different jobs.

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
│   ├── PriceInput.php
│   └── Repository/ProductRepository.php
├── ProductConfig.php            # Config pillar
├── ProductFacade.php            # Facade pillar
└── ProductFactory.php           # Factory pillar
```

`src/Shared` holds cross-module helpers (e.g. `AbstractTransfer`).

## Getting started

**Requirements:** PHP 8.3+, Composer, Node.js 20+.

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment + app key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database and run migrations
php artisan app:create-sqlite         # interactive helper
#   ...or manually:
#   touch database/database.sqlite && php artisan migrate

# 4. Build front-end assets (Vite)
npm run build      # or `npm run dev` for the dev server

# 5. Serve
php artisan serve
```

## Running under Octane

[Laravel Octane](https://laravel.com/docs/octane) is required already and its config is published in
[`config/octane.php`](config/octane.php). `.env.example` picks FrankenPHP:

```bash
php artisan octane:start                      # FrankenPHP, from OCTANE_SERVER
php artisan octane:start --server=roadrunner  # or RoadRunner
```

The first start offers to download the server binary into the project root. It is gitignored, so
each machine downloads its own. RoadRunner also asks to require `spiral/roadrunner-http` and
`spiral/roadrunner-cli`.

One worker serves many requests from one booted application, so anything a request builds would
still be there for the next one. Nothing to add for Gacela: the bridge resets its request state
after each request. What stays for the process is what is safe to share, such as resolved class
names and the merged config.

`ProductFactory` shows the difference. `CreatedProducts` is a `singleton()` that records the
products a request created, and `ProductFacade::getProductsCreatedInThisRequest()` reads it. Under
Octane it only ever holds the current request's products.
[`OctaneWorkerTest`](tests/Feature/Octane/OctaneWorkerTest.php) proves it without a server: it
serves two requests from one application, firing `RequestTerminated` between them, and checks the
second request does not see the first one's product. Without the event, it does.

Keep request data out of `gacela.php` singletons: those live for the whole process.

## Using the Product module

### Console commands

Two commands drive the module, each showing a different (valid) way to reach a Gacela Facade:

- **`AddProductCommand`** — a Laravel command whose constructor parameter carries the bridge's
  `#[Inject(ProductFacade::class)]`, so **Laravel resolves it through Gacela's container** instead of
  autowiring it itself. The class argument is required there: Laravel hands a contextual attribute no
  parameter to read a type from. Note the parameter is deliberately *not* promoted — a promoted
  parameter carries its attributes onto the property too, and the bridge then reads a constructor
  injection as a property one, which throws for a `readonly` property.
- **`ListProductCommand`** — a Symfony command that resolves the Facade via Gacela's
  `ServiceResolverAwareTrait`, declared with `#[ServiceMap(method: 'getFacade', className:
  ProductFacade::class)]`.

```bash
php artisan product:add Keyboard        # uses DEFAULT_PRODUCT_PRICE (49)
php artisan product:add Monitor 150
php artisan product:list
```

> The application's own commands live outside the `gacela:` namespace, because the bridge now owns
> that prefix for the framework's commands. `php artisan list gacela` shows Gacela's, not yours.

### Routes

Controllers resolve the Facade with `ServiceResolverAwareTrait` plus `#[ServiceMap]`.

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
php artisan gacela:make:module Src/Basket

# ...or a "service" module (Facade wired to a Domain service) plus a GacelaTestCase test
php artisan gacela:make:module Src/Basket --template=service --with-tests
```

Generating over files that already exist is refused — the whole run writes nothing and exits `1`.
Pass `--force` if replacing really is the intent, or `gacela:make:file Src/Basket Config` to fill a
single gap.

This creates `src/Basket/BasketFacade.php`, `BasketFactory.php`, `BasketConfig.php`,
`BasketProvider.php` (and, with `--template=service`, a `Domain/BasketService.php` and
`Tests/BasketFacadeTest.php`). Add any bindings in `gacela.php` (or the generated Provider) and you are
ready to go.

## Inspecting modules

The bridge exposes every Gacela command through `artisan` — `php artisan list gacela` is the full
list:

```bash
php artisan gacela:list:modules           # table of every module and which pillars it defines
php artisan gacela:debug:module Product   # resolved Facade/Factory/Config + bindings + dep tree
php artisan gacela:debug:graph            # module dependency graph (who imports whom)
php artisan gacela:debug:modules --check  # can every pillar constructor be satisfied?
php artisan gacela:doctor                 # environment and wiring health checks
```

For example, `php artisan gacela:debug:module Product` prints the resolved classes and the
`ProductRepositoryInterface => ProductRepository` binding declared in `gacela.php`.

> Run these through `artisan`, not through `vendor/bin/gacela`. `gacela.php` feeds Laravel's
> `config/*.php` into Gacela, and those files call helpers like `storage_path()` that only exist
> inside a booted Laravel application — the standalone binary fails on them.

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

Gacela's PHPStan rules **ship with the framework** — there is no extension package to install. With
[phpstan/extension-installer](https://github.com/phpstan/extension-installer) they register
themselves; this repo does not use it, so [`phpstan.neon`](phpstan.neon) includes
`vendor/gacela-project/gacela/phpstan-gacela.neon` by hand and turns on the two opt-in cross-module
rules for the `Src` namespace.

The rules are what makes `#[ServiceMap]` worth writing: with the attribute declared, `getFacade()`
has a real type, so `$this->getFacade()->typoMethod()` is a PHPStan error rather than a call on
`mixed`.

CI runs the matrix (PHP 8.3 / 8.4), `gacela:doctor`, and the Vite build — see
[`.github/workflows/ci.yml`](.github/workflows/ci.yml).

---

Read the full framework documentation at **[gacela-project.com](https://gacela-project.com/)**.
