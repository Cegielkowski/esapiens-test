# API Esapiens

## Tecnologias

- [Lumen 5.5](https://github.com/laravel/lumen/tree/v5.5.0).
- [Dingo](https://github.com/dingo/api) to easily and quickly build your own API. <sup>[1]</sup>
- [Lumen Generator](https://github.com/flipboxstudio/lumen-generator) to make development even easier and faster.
- [CORS and Preflight Request](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS) support.
- [FIle System](https://gist.github.com/deividaspetraitis/4cca4fa6a61cc9a75e12f640041e53f5) support.
- [intervention Image](https://github.com/Intervention/image) support.

## Quick Start

- Clone o repositorio, ou faça o download e o extraia
- Rode o `composer install`
- Rode o `php artisan jwt:secret`
- Configure seu `.env` para poder ser utilizado o bd
- Coloque o `API_PREFIX` no .env (coloque `api`).

## Para usar

- Rode o servidor do próprio PHP através da raiz do projeto:

```sh
php -S localhost:8000 -t public/
```

Ou pelo comando artisan (meu favorito):

```sh
php artisan serve
```

Após isso, rode:

```sh
php artisan migrate:install
```

Também rode:

```sh
php artisan migrate
```

E depois para gerar dados ficticios:

```sh
php artisan db:seed
```




