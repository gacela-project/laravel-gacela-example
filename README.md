## About Laravel Gacela Example

This is an example of how to use Laravel with Gacela modules.

The trick is to allow the auto wiring mechanism from Laravel so the Facade injects its Factory and the Factory injects
whatever you want. This is a useful way to get the Laravel Repositories in your Factory, so you can inject them in your
application services.

## Setup

There are two commands and two controllers inside the Product module:

This repository example uses sqlite, so you can easily check out and try it yourself :)
```
1. Create a empty file in '/database/database.sqlite'
2. Execute the 'php artisan migrate' command
```

### Commands

> App > Console > Commands > { AddProductCommand | ListProductCommand }

```bash
php artisan gacela:product:add {PRODUCT_NAME} {PRODUCT_PRICE=1996}

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

The Gacela Factory has an autowiring logic that will automagically resolve its dependencies. The only exception is for
interfaces, when there is no way to discover what want to inject there. For this purpose, you simply need to define the
mapping between the interfaces and to what do you want them to be resolved. You can do this in two ways

- OPTION A: In the `Gacela::bootstrap()` you just pass the globalServices that will be used in the `gacela.php` file.

```php
# bootstrap/app.php
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// $app-> ...

Gacela::bootstrap(
    appRootDir: base_path(), 
    globalServices: ['laravel/app' => $app]
);
```

- OPTION B: Directly in the bootstrap, as globalServices. This way you don't need a `gacela.php` file.

```php
Gacela::bootstrap($kernel->getProjectDir(), [
    'mapping-interfaces' => function (
        MappingInterfacesBuilder $mappingInterfacesBuilder,
        array $globalServices
    ): void {
        $mappingInterfacesBuilder->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        //...
    },
]);
```

### How can you use the original symfony kernel in Gacela?

> Following the previous example using the gacela.php file (OPTION A).

To use the original `$app` you pass it as a globalService in Gacela in the entry point of the application.

```php
# bootstrap/app.php
Gacela::bootstrap(
    appRootDir: base_path(),
    globalServices: ['laravel/app' => $app],
);
```

Afterwards, you can access to it in your `gacela.php` file in the `mappingInterfaces()` method, such as: 
`$globalServices['laravel/app']`. This way you are telling Gacela what concretion do you want when it encounters
an abstraction (like an abstract class or an interface).

```php
use App\Product\Domain\ProductRepositoryInterface;
use App\Product\Infrastructure\Persistence\ProductRepository;

return static fn() => new class() extends AbstractConfigGacela {
    public function config(ConfigBuilder $configBuilder): void
    {
        $configBuilder->add('.env*', '.env.local', EnvConfigReader::class);
    }

    public function mappingInterfaces(
        MappingInterfacesBuilder $mappingInterfacesBuilder,
        array $globalServices
    ): void {
        $mappingInterfacesBuilder->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        /** @var \Illuminate\Foundation\Application $app */
        $app = $globalServices['laravel/app'];
    }
};
```

---

Read the full docs in http://gacela-project.com/
