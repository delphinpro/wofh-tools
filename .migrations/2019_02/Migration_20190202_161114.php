<?php


use Dolphin\Commands\Migration\BaseMigration;
use Illuminate\Database\Schema\Blueprint;


class Migration_20190202_161114 extends BaseMigration
{
    public function up()
    {
        $this->schema->create('wt_worlds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->string('title_alt', 50)->nullable();
            $table->string('sign', 7);
            $table->tinyInteger('can_reg')->nullable()->default(0);
            $table->tinyInteger('working')->nullable()->default(0);
            $table->tinyInteger('statistic')->nullable()->default(0);
            $table->tinyInteger('hidden')->nullable()->default(0);
            $table->dateTime('started')->nullable();
            $table->dateTime('closed')->nullable();
            $table->dateTime('time_of_loaded_stat')->nullable();
            $table->dateTime('time_of_updated_stat')->nullable();
            $table->dateTime('time_of_updated_const')->nullable();
            $table->dateTime('time_of_update_started')->nullable();
            $table->char('version', 10)->nullable();
            $table->text('desc')->nullable();
            $table->json('meta_info')->nullable();
            $table->engine = "InnoDB";
        });
    }


    /**
     * @throws Exception
     */
    public function down()
    {
        throw new \Exception('This migration cannot be rolled back: '.__CLASS__.' ('.$this->description().')');
    }


    public function description()
    {
        return 'Create new table wt_worlds';
    }
}
