<?php

namespace App\Orchid;

use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @return ItemMenu[]
     */
    public function registerMainMenu(): array
    {
        $mainmenu = [];

        $mainmenu[] = ItemMenu::label('Worlds')
            ->title('Wofh')
            ->icon('fas.globe-americas')
            ->route('admin.worlds');

        if (!config('platform.examples')) return $mainmenu;

        $mainmenu[] = ItemMenu::label('Fontawesome icons')
            ->title('Resources')
            ->icon('fas.external-link-alt')
            ->url('https://fontawesome.com/icons?d=gallery&s=light,regular,solid&m=free');

        $mainmenu[] = ItemMenu::label('Orchid platform')
            ->icon('fas.external-link-alt')
            ->url('https://orchid.software/ru/docs');

        $mainmenu[] = ItemMenu::label('Examples')
            ->slug('example-menu')
            ->icon('fas.code')
            ->withChildren();

        $mainmenu[] = ItemMenu::label('Example screen')
            ->place('example-menu')
            ->route('platform.example')
            ->badge(function () { return 6; });

        $mainmenu[] = ItemMenu::label('Basic Elements')
            ->place('example-menu')
            ->route('platform.example.fields');

        $mainmenu[] = ItemMenu::label('Advanced Elements')
            ->place('example-menu')
            ->route('platform.example.advanced');

        $mainmenu[] = ItemMenu::label('Text Editors')
            ->place('example-menu')
            ->route('platform.example.editors');

        $mainmenu[] = ItemMenu::label('Overview layouts')
            ->place('example-menu')
            ->route('platform.example.layouts');

        $mainmenu[] = ItemMenu::label('Chart tools')
            ->place('example-menu')
            ->route('platform.example.charts');

        $mainmenu[] = ItemMenu::label('Cards')
            ->place('example-menu')
            ->route('platform.example.cards');

        return $mainmenu;
    }

    /**
     * @return ItemMenu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            ItemMenu::label('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemMenu[]
     */
    public function registerSystemMenu(): array
    {
        return [
            ItemMenu::label(__('Access rights'))
                ->icon('lock')
                ->slug('Auth')
                ->active('platform.systems.*')
                ->permission('platform.systems.index')
                ->sort(1000),

            ItemMenu::label(__('Users'))
                ->place('Auth')
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->sort(1000)
                ->title(__('All registered users')),

            ItemMenu::label(__('Roles'))
                ->place('Auth')
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->sort(1000)
                ->title(__('A Role defines a set of tasks a user assigned the role is allowed to perform.')),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('Systems'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }

    /**
     * @return string[]
     */
    public function registerSearchModels(): array
    {
        return [
            // ...Models
            // \App\Models\User::class
        ];
    }
}
