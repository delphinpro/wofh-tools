<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatLogsTable extends Migration
{
    public function up()
    {
        Schema::create('wt_stat_log', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('operation')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->integer('world_id')->unsigned()->nullable()->default(null);
            $table->string('message', 255)->nullable()->default(null);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wt_stat_log');
    }
}
