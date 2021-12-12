<?php

namespace App\Orchid\Layouts\World;

use App\Models\World;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WorldListLayout extends Table
{
    /**
     * Data source.
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'worlds';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('id', __('ID'))
                ->cantHide(),
            TD::make('sign', __('Sign'))
                ->cantHide(),
            TD::make('title', __('Title'))
                ->cantHide()
                ->render(function (World $world) {
                    return $world->title_alt ?? $world->title;
                }),
            TD::make('can_reg', __('Register')),
            TD::make('working', __('Working')),
            TD::make('statistic', __('Statistic')),
            TD::make('started_at', __('Started at')),
            TD::make('closed_at', __('Closed at')),
            TD::make('stat_loaded_at', __('Loaded at')),
            TD::make('stat_updated_at', __('Updated at')),
        ];
    }
}
