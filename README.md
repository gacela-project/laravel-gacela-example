## About Laravel Gacela Example

This is an example of how to use Laravel with Gacela modules.

The trick is to allow the auto wiring mechanism from Laravel so the Facade injects its Factory and the Factory injects
whatever you want. This is a useful way to get the Laravel Repositories in your Factory, so you can inject them in your
application services.

## Development

If you want to try out this template, don't forget to create the `.env` file:
``` 
cp .env.example .env
```

Also, this repository example uses sqlite, so you can easily check out and try it yourself :)
```
1. Create a empty file in '/database/database.sqlite'
2. Execute the 'php artisan migrate' command
```

There are two commands and two controllers inside the Product module:

### Commands

> App > Console > Commands > { AddProductCommand | ListProductCommand }

```bash
php artisan gacela:product:add {PRODUCT_NAME}

php artisan gacela:product:list
```

### Controllers

> App > Http > Controller > { AddProductController | ListProductController }

In order to run locally the application, run `php artisan serve`

```bash
php artisan route:list
```

| Method   | URI        | Name          | Action                                      | Middleware |
|----------|------------|---------------|---------------------------------------------|------------|
| GET HEAD | add/{name} | product_add   | App\Http\Controllers\AddProductController   | web        |
| GET HEAD | list       | product_list  | App\Http\Controllers\ListProductController  | web        |

## Injecting the Doctrine ProductRepository to the Facade Factory

The Gacela Factory (as well as the Config and DependencyProvider) has an autowiring logic that will automagically
resolve its dependencies. The only exception is for interfaces, when there is no way to discover what want to inject there.
For this purpose, you simply need to define the mapping between the interfaces
and to what do you want them to be resolved (in `gacela.php`): `mappingInterfaces(): array`

Actually, in our current context/example (using laravel) it does not make sense the idea of injecting an `Entity
Manager`, but in case we would like to inject an instance from the kernel, we should inject it to the Gacela bootstrap.

### How can you use the original laravel app in Gacela?

To use the original app you simply pass it as a global service in Gacela
in the entry point of the application. It might be in the `public/index.php` or `bin/console`.

```php
# bootstrap/app.php
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// $app-> ...

\Gacela\Framework\Gacela::bootstrap(
    base_path(),
    ['laravel/app' => $app],
    ['env' => new \Gacela\Framework\Config\ConfigReader\EnvConfigReader()]
);
```

Afterwards, you can access to it easily in your `gacela.php` file from the `$this->getGlobalService('symfony/kernel')`
and this way you can specify in the `'dependencies'` that when the `EntityManagerInterface::class` is found, then you
want to resolve it using the "doctrine service" from the original kernel.

```php
<?php
return static fn () => new class () extends AbstractConfigGacela {

    // ...

    public function mappingInterfaces(array $globalServices): array
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = $this->getGlobalService('laravel/app');

        return [
            ProductRepositoryInterface::class => ProductRepository::class,
            ProductEntityManagerInterface::class => ProductRepository::class,
        ];
    }
};
```

## Gacela Framework

You can know more about this project in:

- http://gacela-project.com
- https://github.com/gacela-project/gacela
