<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use App\Repositories\WorldRepository;
use App\Traits\CliColors;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class StatisticClear extends Command
{
    use CliColors;


    /** @var string */
    protected $signature = 'stat:clear
                            {world : Process only for one world (ex. ru23)}';

    /** @var string */
    protected $description = 'Clear statistic for one world';

    /** @var \App\Repositories\WorldRepository */
    protected $worldRepository;


    /**
     * Create a new command instance.
     *
     * @param \App\Repositories\WorldRepository $worldRepository
     */
    public function __construct(WorldRepository $worldRepository)
    {
        parent::__construct();
        $this->worldRepository = $worldRepository;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sign = $this->argument('world');
        $this->alert('Clear statistic for '.$sign);
        $world = $this->worldRepository->bySign($sign);

        try {
            if (!$world) {
                throw new \Exception('Invalid argument {world}');
            }

            DB::beginTransaction();

            Schema::dropIfExists("z_{$world->sign}_towns");
            Schema::dropIfExists("z_{$world->sign}_towns_stat");
            Schema::dropIfExists("z_{$world->sign}_accounts");
            Schema::dropIfExists("z_{$world->sign}_accounts_stat");
            Schema::dropIfExists("z_{$world->sign}_countries");
            Schema::dropIfExists("z_{$world->sign}_countries_stat");
            Schema::dropIfExists("z_{$world->sign}_countries_diplomacy");
            Schema::dropIfExists("z_{$world->sign}_events");
            Schema::dropIfExists("z_{$world->sign}_common");

            $world->stat_loaded_at = null;
            $world->stat_updated_at = null;
            $world->update_started_at = null;
            $world->save();

            DB::commit();

            $this->info('Statistic cleared for '.$world->sign);
            $this->line('');

        } catch (\Throwable $e) {

            $this->error($e->getMessage());

            if (DB::transactionLevel() > 0) {
                try {
                    DB::rollBack();
                } catch (\Throwable $e) {
                    $this->error($e->getMessage());
                }
            }

            return 1;
        }

        return 0;
    }
}
