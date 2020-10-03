# Wofh-tools.ru

Repository of wofh-tools.ru

Работает на [Laravel 7](https://laravel.com/docs/7.x) \([Русская документация](https://delphinpro.gitbook.io/laravel-ru/)\)

## Используемые пакеты

| Назначение | Назание и ссылка
|---|---
| Отладчик | [`recca0120/laravel-tracy`](https://github.com/recca0120/laravel-tracy)
| Административная панель | [`encore/laravel-admin`](https://github.com/z-song/laravel-admin)
| Аутентификация | [`laravel/ui`](https://github.com/laravel/ui)
| Фронтэнд | [`Quasar framework`](#)

## Установка

```
git clone git@github.com:delphinpro/wofh-tools.git .

composer install
composer dumpautoload

php artisan storage:link
php artisan key:generate
php artisan migrate:install
php artisan migrate

npm install
npm run build
```

Решение проблемы с нехваткой памяти
```
php -d memory_limit=-1 composer.phar <...>
```

В development среде можно сгенерировать файлы автодополнения для IDE (phpStorm)
```
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:models --filename=_ide_helper_models_admin.php --dir=vendor/encore/laravel-admin/src/Auth/Database
```
https://github.com/barryvdh/laravel-ide-helper/issues/126#issuecomment-328281716

## Сервер

Установить Apache, Nginx, NodeJS (>=v14.13.0)

Установить глобально менеджер процессов pm2
И после сборки запустить frontend сервер. Сервер запустится на 3333 порту.
```batch
npm i -g pm2
npm run build
pm2 start
```

Apache повесить на порт 8080
```apacheconfig
Listen 8080
RemoteIPHeader X-Real-IP
DirectoryIndex index.php
AccessFileName .htaccess
AddDefaultCharset UTF-8
<VirtualHost *:8080>
    ServerName      "wofh-tools.project"
    ServerAlias     "wofh-tools.project" 
    DocumentRoot    "d:/dev/projects/wofh-tools/wofh-tools.project/public"
</VirtualHost>
```

В nginx настроить проксирование запросов

```apacheconfig
server {
    listen       *:80;
    server_name  wofh-tools.ru;

    location / {
        proxy_buffer_size         64k;
        proxy_buffering           on;
        proxy_buffers             4 64k;
        proxy_connect_timeout     5s;
        proxy_ignore_client_abort off;
        proxy_intercept_errors    off;
        proxy_pass_header         Server;
        proxy_read_timeout        5m;
        proxy_redirect            off;
        proxy_send_timeout        5m;
        proxy_pass                http://127.0.0.1:3333;
        proxy_set_header          Host $host;
        proxy_set_header          X-Real-IP $remote_addr;
        proxy_set_header          X-Forwarded-For $http_x_forwarded_for;
        proxy_set_header          X-Forwarded-Proto $scheme;
    }

    location /api {
        proxy_pass                http://127.0.0.1:8080;
        proxy_set_header          Host $host;
        proxy_set_header          X-Real-IP $remote_addr;
    }

    location /storage {
        proxy_pass                http://127.0.0.1:8080;
        proxy_set_header          Host $host;
        proxy_set_header          X-Real-IP $remote_addr;
    }

    location /vendor {
        proxy_pass                http://127.0.0.1:8080;
        proxy_set_header          Host $host;
        proxy_set_header          X-Real-IP $remote_addr;
    }

    location /admin {
        proxy_pass                http://127.0.0.1:8080;
        proxy_set_header          Host $host;
        proxy_set_header          X-Real-IP $remote_addr;
    }

    # deny access to .htaccess files, if Apache's document root
    # concurs with nginx's one

    location ~ /\.ht {
        deny  all;
    }
}
```
