# Wofh-tools.ru

Repository of wofh-tools.ru

Работает на [Laravel 7](https://laravel.com/docs/7.x) \([Русская документация](https://delphinpro.gitbook.io/laravel-ru/)\)

## Используемые пакеты

Административная панель [laravelrus/sleepingowl](https://sleepingowladmin.ru/#/ru/installation)  
Фронтэнд [laravel/ui](https://github.com/laravel/ui)

## Установка

```
git clone git@github.com:delphinpro/wofh-tools.git .
composer install --no-dev
composer dumpautoload
```

> __Note:__ В development среде устанавливать пакеты без ключа `--no-dev`

В development среде можно сгенерировать файлы автодополнения для IDE (phpStorm)
```
php artisan ide-helper:generate
php artisan ide-helper:models
```
https://github.com/barryvdh/laravel-ide-helper/issues/126#issuecomment-328281716

**При генерации PhpDoc для моделей, следует размещать их прямо в файлах моделей
(делайте это перед коммитом новой или измененной модели).
Для этого нужно ответить `yes` на вопрос команды.**


