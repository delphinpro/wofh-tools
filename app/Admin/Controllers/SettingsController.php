<?php

namespace App\Admin\Controllers;


use App\Admin\Forms\Analytics;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;


class SettingsController extends AdminController
{
    protected $title = 'Settings';

    public function settings(Content $content)
    {
        $forms = [
        ];

        $content->title('Настройки');
        $content->body(Tab::forms($forms));

        return $content;
    }
}
