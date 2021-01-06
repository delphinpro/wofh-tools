<?php

namespace App\Admin\Controllers;


use App\Services\Wofh;
use App\Models\StatLog;
use App\Models\World;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;


class StatLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'StatLog';

    protected static $statuses = [];


    public function __construct()
    {
        self::$statuses = [
            0 => [
                'text' => 'SUCCESS',
                'icon' => '<i class="fa fa-check-circle text-success"></i>',
            ],
            1 => [
                'text' => 'ERROR',
                'icon' => '<i class="fa fa-bomb text-danger"></i>',
            ],
            2 => [
                'text' => 'WARNING',
                'icon' => '<i class="fa fa-exclamation-triangle text-warning"></i>',
            ],
            3 => [
                'text' => 'INFO',
                'icon' => '<i class="fa fa-info-circle text-info"></i>',
            ],
        ];
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StatLog());

        $grid->model()->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            /** @var \Encore\Admin\Grid\Filter $filter */
            $filter->disableIdFilter();
            $filter->expand();

            $filter->equal('operation', 'Операция')->select([
                1 => 'Загрузка статистики',
                2 => 'Обновление статистики',
            ]);

            $filter->equal('status', 'Статус')->select([
                0 => self::$statuses[0]['text'],
                1 => self::$statuses[1]['text'],
                2 => self::$statuses[2]['text'],
                3 => self::$statuses[3]['text'],
            ]);

            $worlds = World::where('hidden', 0)
                ->get(['id', 'sign'])
                ->keyBy('id')->map(function ($item) { return $item['sign']; })
                ->toArray();
            $filter->equal('world_id', 'Мир')->select($worlds);
        });


        $grid->column('operation', __('Operation'))->display(function ($value) {
            $operations = [
                1 => 'LOAD',
                2 => 'UPDATE',
            ];

            return $operations[$value];
        });
        $grid->column('status', __('Status'))->display(function ($value) {
            return '<div class="text-center">'.self::$statuses[$value]['icon'].'</div>';
        });
        $grid->column('world_id', __('World'))->display(function ($value) {
            return '<div class="text-right"><code>'.($value ? resolve(Wofh::class)->idToSign($value) : '—').'</code></div>';
        });
        $grid->column('message', __('Message'));
        $grid->column('created_at', __('Time'))->display(function ($value) {
            /** @var \App\Models\StatLog $this */
            return '<tt style="font-size: 0.8em;">'.($this->created_at->format('Y-m-d H:i:s')).'</tt>';
        });

        $grid->paginate(50);
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableActions();

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return new Form(new StatLog());
    }
}
