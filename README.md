## About Laravel Gacela Example

This is an example of how to use Laravel with Gacela modules.

The trick is to allow the auto wiring mechanism from Laravel so the Facade injects its Factory and the Factory injects whatever you want. This is a useful way to get the Laravel Repositories in your Factory, so you can inject them in your application services.

## Setup

There are two commands and two controllers inside the Product module:

This repository example uses sqlite, so you can check out and try it yourself.

```bash
# 1. Run the custom `CreateSqliteFileCommand`
php artisan gacela:create-sqlite
```

Or you can do it manually with:
```bash
# 1. Create a empty file in 
touch /database/database.sqlite
# 2. Run migrations
php artisan migrate
```

### Commands

- App > Console > Commands > AddProductCommand
- App > Console > Commands > ListProductCommand

```bash
php artisan gacela:product:add {PRODUCT_NAME} {PRODUCT_PRICE=49}

php artisan gacela:product:list
```

### Controllers

- App > Http > Controller > AddProductController
- App > Http > Controller > ListProductController

### Demo 

To locally run the application use `php artisan serve`

```bash
php artisan route:list
```

| Method   | URI        | Name          | Action                                      | Middleware |
|----------|------------|---------------|---------------------------------------------|------------|
| GET HEAD | add/{name} | product_add   | App\Http\Controllers\AddProductController   | web        |
| GET HEAD | list       | product_list  | App\Http\Controllers\ListProductController  | web        |

---

Read the full docs in http://gacela-project.com/
