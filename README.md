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
pm2 start
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

