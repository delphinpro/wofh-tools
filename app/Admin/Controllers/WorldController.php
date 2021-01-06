<?php

namespace App\Admin\Controllers;


use App\Models\World;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;


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


    protected function filterHiddenApplied()
    {
        return array_key_exists('hidden', $_GET);
    }


    protected function filterHiddenAppliedOff()
    {
        return $this->filterHiddenApplied() && $_GET['hidden'] === '';
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new World());

        if (!$this->filterHiddenApplied() || $this->filterHiddenAppliedOff()) {
            $grid->model()->where('hidden', 0);
        }


        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'yes':
                        // $query->where('hidden', 1);
                        break;
                    default:
                        $query->where('hidden', 0);
                        break;
                }
            }, 'Hidden worlds:', 'hidden')->radio([
                'yes' => 'Show',
                ''    => 'Hide',
            ]);
        });

        $grid->column('title', __('worlds.title'))->display(function () {
            /** @var World $this */
            $_title = $this->title_alt ?: $this->title;
            $_title = ucfirst($this->sign).(strtolower($_title) != $this->sign ? (' — '.$_title) : '');

            return '<code>'.$this->id.'</code> '.
                '<a href="'.route('admin.worlds.edit', ['world' => $this->id]).'">'.$_title.'</a>';
        });
        $grid->column('can_reg', __('worlds.register'))->bool()->sortable();
        $grid->column('working', __('worlds.working'))->bool()->sortable();
        $grid->column('statistic', __('worlds.statistic'))->switch([
            'on'  => ['value' => 1, 'color' => 'success'],
            'off' => ['value' => 0, 'color' => 'default'],
        ])->sortable();
        if ($this->filterHiddenApplied()) {
            $grid->column('hidden', __('worlds.hidden'))->switch([
                'on'  => ['value' => 1, 'text' => 'yes', 'color' => 'warning'],
                'off' => ['value' => 0, 'text' => 'no', 'color' => 'success'],
            ])->sortable();
        }
        $grid->column('started_at', __('worlds.started_at'))->display(function ($value) {
            return '<tt style="font-size: 0.7em;">'.($value ? date('d.m.Y h:i', $value) : '—').'</tt>';
        });
        $grid->column('stat_loaded_at', __('worlds.stat_loaded_at'))->display(function ($value) {
            return '<tt style="font-size: 0.7em;">'.($value ? date('d.m.Y h:i', $value) : '—').'</tt>';
        });
        $grid->column('stat_updated_at', __('worlds.stat_updated_at'))->display(function ($value) {
            return '<tt style="font-size: 0.7em;">'.($value ? date('d.m.Y h:i', $value) : '—').'</tt>';
        });
        $grid->column('update_started_at', 'Lock')->display(function ($value) {
            /** @var World $this */
            return $value
                ? '<i '.
                'style="position:absolute;right:0.75em;" '.
                'title="'.$this->update_started_at->format('d.m.Y H:i').'" '.
                'class="fa fa-lock fa-2x text-danger"></i>'
                : '';
        });

        $grid->paginate(50);
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableBatchActions();
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
        $form = new Form(new World());
        // echo '<pre>';
        // var_dump($form->model());die;


        $form->display('id', 'ID');
        $form->text('title', __('worlds.title'));
        $form->text('title_alt', __('Title alt'));
        $form->text('sign', __('Sign'));
        $form->switch('can_reg', __('worlds.register'));
        $form->switch('working', __('worlds.working'));
        $form->switch('statistic', __('worlds.statistic'));
        $form->switch('hidden', __('worlds.hidden'));
        $form->datetime('started_at', __('worlds.started_at'))->setView('admin.form.timestamp');
        $form->datetime('closed_at', __('worlds.closed_at'))->setView('admin.form.timestamp');
        // $form->datetime('stat_loaded_at', __('Stat loaded at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('stat_updated_at', __('Stat updated at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('const_updated_at', __('Const updated at'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('update_started_at', __('Update started at'))->default(date('Y-m-d H:i:s'));
        $form->text('version', __('Version'));
        $form->textarea('desc', __('Desc'));
        $form->text('meta_info', __('Meta info'));

        // $form->ignore(['can_reg', 'working']);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
            $tools->disableList();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            // $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        return $form;
    }
}
