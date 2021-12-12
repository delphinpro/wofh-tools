<?php

namespace App\Orchid\Screens\World;

use App\Orchid\Layouts\World\WorldListLayout;
use App\Repositories\WorldRepository;
use Orchid\Screen\Screen;

class WorldListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'World List';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Game world list';

    /**
     * Query data.
     *
     * @param  \App\Repositories\WorldRepository  $worlds
     * @return array
     */
    public function query(WorldRepository $worlds): array
    {
        return [
            'worlds' => $worlds->all(),//->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            WorldListLayout::class,
        ];
    }
}
