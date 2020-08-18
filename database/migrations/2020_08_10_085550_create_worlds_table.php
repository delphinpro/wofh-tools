<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateWorldsTable extends Migration
{
    private $tableName = 'wt_worlds';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('title_alt', 50)->nullable()->default(null);
            $table->char('sign', 10);
            $table->boolean('can_reg')->default(false);
            $table->boolean('working')->default(false);
            $table->boolean('statistic')->default(false);
            $table->boolean('hidden')->default(false);
            $table->timestamp('started_at')->nullable()->default(null);
            $table->timestamp('closed_at')->nullable()->default(null);
            $table->timestamp('stat_loaded_at')->nullable()->default(null);
            $table->timestamp('stat_updated_at')->nullable()->default(null);
            $table->timestamp('const_updated_at')->nullable()->default(null);
            $table->timestamp('update_started_at')->nullable()->default(null);
            $table->char('version', 10)->nullable()->default(null);
            $table->text('desc')->nullable()->default(null);
            $table->json('meta_info')->nullable()->default(null);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
