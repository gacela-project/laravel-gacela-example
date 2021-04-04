## About Laravel Gacela Example

Gacela is a framework that helps you to improve the design of your application by splitting the logic into different
modules.

This repo is a usage example of Gacela using Laravel `8.35.1`.

Instead of having all things together inside `app`, you will find different modules that helps you identify the use
cases and grouped by responsibilities, splitting as well the Infrastructure, and the Domain code.

## Main architecture differences

### BEFORE

This is the current version of a laravel:

```
- app
| -- Console
| -- Exception
| -- Http
| -- Models
| -- Providers
```

### AFTER

We moved the app directories into their own modules, so you can easily identify the responsibilities and distinguish the
different layers between Infrastructure and Domain.

```
- app
| -- Auth
| ---- Infrastructure
| -- Broadcast
| ---- Infrastructure
| -- Calculator (NEW example module which uses Gacela's Facade and Factory)
| ---- Domain
| ---- Infrastructure
| -- Console
| ---- Infrastructure
| -- Event
| ---- Infrastructure
| -- Router
| ---- Infrastructure
| -- Shared
| ---- Infrastructure
| ------ Console
| ------ Exceptions
| ------ Http
| ------ Models
| ------ Providers
```


## Development

If you want to try out this template, don't forget to create the `.env` file:
``` 
cp .env.example .env
```


## Gacela Framework

You can know more about this project in:

- http://gacela-project.com
- https://github.com/gacela-project/gacela
 
