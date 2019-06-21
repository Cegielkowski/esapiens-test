# API Esapiens
- Qualquer dúvida podem me ligar ou mandar mensagem, 14 99858-3391
## Tecnologias

- [Lumen 5.5](https://github.com/laravel/lumen/tree/v5.5.0).
- [Redis](https://github.com/nrk/predis).
- [Dingo](https://github.com/dingo/api) to easily and quickly build your own API. <sup>[1]</sup>
- [Lumen Generator](https://github.com/flipboxstudio/lumen-generator) to make development even easier and faster.
- [CORS and Preflight Request](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS) support.
- [FIle System](https://gist.github.com/deividaspetraitis/4cca4fa6a61cc9a75e12f640041e53f5) support.
- [intervention Image](https://github.com/Intervention/image) support.

## Quick Start

- Clone o repositorio, ou faça o download e o extraia
- Rode o `composer install`
- Configure seu `.env` para poder ser utilizado o bd
- Coloque o `API_PREFIX` no .env (coloque `api`).
 
## Regras
- Só pode comentar 3x por minuto
- As notificações dos usuários expirarão 1 hora após sua visualização.

## Infos
- Infelizmente nao deu tempo de implementar autenticacao
- A logica para aguentar mais requisições foi utilizar o lumen que aguenta mais que o laravel, combinado com o redis que cacheia por 2 segundos, ou seja so faz a requisição no BD a cada 2 segundos

## Para usar

Instalar por favor:
```sh
Php 7.1 - Mysql 5.6 - Redis 
```
- Crie o banco com o nome conforme seu .env

Rode o php pelo:

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
##Docs

Documentacao dos metodos da Api esta aqui (com exemplos):

https://documenter.getpostman.com/view/3611232/S1a1ZTsU?version=latest#c13f2e74-4c52-4e52-ad81-6929bd3dd636



Quer saber como foi planejado este desafio? Acesse:
https://trello.com/b/BYDG3Dzr/desafio-esapiens