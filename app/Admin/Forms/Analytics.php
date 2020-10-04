<?php

namespace App\Admin\Forms;


use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;


class Analytics extends Form
{
    public $title = 'Аналитика Яндекс';
    public $buttons = ['submit'];

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        try {

            $this->saveSetting('yaCounterId', $request->get('yaCounterId'));
            $this->saveSetting('yaCounterSrc', $request->get('yaCounterSrc'));
            $this->saveSetting('yaInformerLink', $request->get('yaInformerLink'));
            $this->saveSetting('yaInformerImg', $request->get('yaInformerImg'));
            admin_success('Успешно');

        } catch (\Throwable $e) {

            admin_error('Ошибка', $e->getMessage());

        }

        return back();
    }

    private function saveSetting($name, $value, $type = 'text')
    {
        $setting = \App\Settings::findOrNew($name);
        $setting->name = $name;
        $setting->type = $type;
        $setting->value = $value;
        $setting->save();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('yaCounterId', 'Счетчик: Id');
        $this->text('yaCounterSrc', 'Счетчик: Script src');
        $this->text('yaInformerLink', 'Информер: ссылка');
        $this->text('yaInformerImg', 'Информер: картинка');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return [
            'yaCounterId'    => \App\Settings::findOrNew('yaCounterId')->value,
            'yaCounterSrc'    => \App\Settings::findOrNew('yaCounterSrc')->value,
            'yaInformerLink' => \App\Settings::findOrNew('yaInformerLink')->value,
            'yaInformerImg'  => \App\Settings::findOrNew('yaInformerImg')->value,
        ];
    }
}
