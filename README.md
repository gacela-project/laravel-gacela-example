# Laravel Gacela Example

An example of how to build [Gacela](https://gacela-project.com/) modules inside a
[Laravel](https://laravel.com/) application.

The trick is to let Laravel's auto-wiring resolve a Gacela **Facade**, which resolves its
**Factory**, which in turn injects whatever you need — for instance a repository that talks to
the database. This keeps your application services free of framework details while still using
Laravel's Eloquent under the hood.

- **Laravel:** 12
- **Gacela:** 1.18
- **PHP:** 8.2+
- **Frontend:** Vite

## How the integration works

Gacela is booted in [`bootstrap/app.php`](bootstrap/app.php) right after the Laravel application
is created:

```php
Gacela::bootstrap($app->basePath());
```

The configuration lives in [`gacela.php`](gacela.php) at the project root. It:

- reads the environment (`.env*`) through the `EnvConfigReader`,
- reads Laravel's own `config/*.php` files as Gacela config,
- binds `ProductRepositoryInterface` to its Eloquent implementation `ProductRepository`.

The `Product` module (under [`src/Product`](src/Product)) follows a hexagonal layout:

```
src/Product
├── Application      # ProductCreator, ProductLister (use cases)
├── Domain           # ProductRepositoryInterface, ProductTransfer (DTO)
├── Infrastructure   # ProductRepository (the only place that talks to Eloquent)
├── ProductConfig.php
├── ProductFacade.php
└── ProductFactory.php
```

Only the repository touches the database. Controllers and commands go through the Facade.

## Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Create your env file and app key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database and run the migrations
php artisan gacela:create-sqlite
#   ...or manually:
#   touch database/database.sqlite && php artisan migrate

# 4. Build the front-end assets
npm run build   # or: npm run dev
```

Then serve the app:

```bash
php artisan serve
```

## Product module in action

### Console commands

Both command styles are wired to the same Gacela Facade:

- `AddProductCommand` — a Laravel command that receives the Facade via **constructor injection**.
- `ListProductCommand` — a Symfony command that resolves the Facade via Gacela's
  `ServiceResolverAwareTrait` (the `@method ProductFacade getFacade()` doc-block).

```bash
php artisan gacela:product:add {name} {price?}   # price defaults to DEFAULT_PRODUCT_PRICE (49)
php artisan gacela:product:list
```

### Controllers / routes

The controllers use `ServiceResolverAwareTrait` to resolve the Facade.

| Method   | URI                  | Name           | Action                                             |
|----------|----------------------|----------------|----------------------------------------------------|
| GET      | `/`                  | —              | welcome page                                       |
| GET      | `/list`              | `product_list` | `App\Http\Controllers\Product\ListProductController` |
| GET      | `/add/{name}/{price?}` | `product_add`  | `App\Http\Controllers\Product\AddProductController`  |

```bash
php artisan route:list
```

## Quality tooling

```bash
composer test       # PHPUnit (unit + feature suites)
composer phpstan     # PHPStan (larastan + gacela module boundaries), level 6
composer pint        # Laravel Pint (code style, auto-fix)
composer pint-test   # Laravel Pint in check-only mode
```

CI runs the whole matrix (PHP 8.2 / 8.3 / 8.4) plus the Vite build in
[`.github/workflows/ci.yml`](.github/workflows/ci.yml).

---

Read the full docs at [gacela-project.com](https://gacela-project.com/).
