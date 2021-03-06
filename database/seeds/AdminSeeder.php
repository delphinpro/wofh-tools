<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        // Administrator::truncate();
        // Administrator::create([
        //     'username' => 'admin',
        //     'password' => bcrypt('j3c4hN4pzM9FC7FAhk1Y'),
        //     'name'     => 'delphinpro',
        // ]);

        // create a role.
        // Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        // Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        // Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Панель управления',
                'icon'      => 'fa-th',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => 'Админ',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => 'Пользователи',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => 'Роли',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => 'Разрешения',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order'     => 6,
                'title'     => 'Меню',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 2,
                'order'     => 7,
                'title'     => 'Лог операций',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],

            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Игровые миры',
                'icon'      => 'fa-globe',
                'uri'       => 'worlds',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => 'Лог статистики',
                'icon'      => 'fa-bar-chart',
                'uri'       => 'stat-logs',
            ],
            [
                'parent_id' => 0,
                'order'     => 4,
                'title'     => 'Файл-менеджер',
                'icon'      => 'fa-folder-open',
                'uri'       => 'media',
            ],
            [
                'parent_id' => 0,
                'order'     => 5,
                'title'     => 'Настройки',
                'icon'      => 'fa-gears',
                'uri'       => 'settings',
            ],
        ]);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
