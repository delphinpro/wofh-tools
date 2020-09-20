<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */


namespace App\Http\Composers;


use App\Services\State;
use Illuminate\View\View;


class StateComposer
{
    /**
     * @var \App\Services\State
     */
    private $state;

    public function __construct(State $state)
    {
        $this->state = $state;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('state', $this->state->toArray());
        $view->with('WT', [
            'updatedAt' => $this->getLastUpdateTime(),
        ]);
    }

    private function getLastUpdateTime()
    {
        $file = base_path('.git/logs/HEAD');
        if (file_exists($file)) return filemtime($file);
        return false;
    }
}
