<?php

namespace App\Admin\Controllers;


use App\World;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;


class WorldController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Worlds of game';


    protected function title()
    {
        return __('worlds.'.$this->title);
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new World());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->scope('hidden', 'Показать скрытые')->where('hidden', 1);
            $filter->scope('visible', 'Не показывать скрытые')->where('hidden', 0);
        });

        $grid->column('id', __('worlds.id'))->sortable()->display(function ($value) {
            return '<code>'.$value.'</code>';
        });
        $grid->column('title', __('worlds.title'))->display(function () {
            /** @var World $this */
            $_title = $this->title_alt ?: $this->title;
            $_title = ucfirst($this->sign).(strtolower($_title) != $this->sign ? (' — '.$_title) : '');

            return '<a href="'.route('admin.worlds.edit', ['world' => $this->id]).'">'.$_title.'</a>';
        });
        $grid->column('can_reg', __('worlds.register'))->bool()->sortable();
        $grid->column('working', __('worlds.working'))->bool()->sortable();
        $grid->column('statistic', __('worlds.statistic'))->switch()->sortable();
        $grid->column('started_at', __('worlds.started_at'))->display(function ($value) {
            return '<tt style="font-size: 0.8em;">'.($value ? date('Y-m-d h:i:s', $value) : '—').'</tt>';
        });

        $grid->paginate(50);
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableBatchActions();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        return $grid;
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(World::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('title_alt', __('Title alt'));
        $show->field('sign', __('Sign'));
        $show->field('can_reg', __('Can reg'));
        $show->field('working', __('Working'));
        $show->field('statistic', __('Statistic'));
        $show->field('hidden', __('Hidden'));
        $show->field('started_at', __('Started at'));
        $show->field('closed_at', __('Closed at'));
        $show->field('stat_loaded_at', __('Stat loaded at'));
        $show->field('stat_updated_at', __('Stat updated at'));
        $show->field('const_updated_at', __('Const updated at'));
        $show->field('update_started_at', __('Update started at'));
        $show->field('version', __('Version'));
        $show->field('desc', __('Desc'));
        $show->field('meta_info', __('Meta info'));

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new World());

        $form->text('title', __('Title'));
        $form->text('title_alt', __('Title alt'));
        $form->text('sign', __('Sign'));
        $form->switch('can_reg', __('Can reg'));
        $form->switch('working', __('Working'));
        $form->switch('statistic', __('Statistic'));
        $form->switch('hidden', __('Hidden'));
        // $form->datetime('started_at', __('Started at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('closed_at', __('Closed at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('stat_loaded_at', __('Stat loaded at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('stat_updated_at', __('Stat updated at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('const_updated_at', __('Const updated at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('update_started_at', __('Update started at'))->default(date('Y-m-d H:i:s'));
        $form->text('version', __('Version'));
        $form->textarea('desc', __('Desc'));
        $form->text('meta_info', __('Meta info'));

        return $form;
    }
}
