<?php


use Illuminate\Database\Schema\Blueprint;
use Dolphin\Commands\Migration\BaseMigration;


class Migration_20190720_144406 extends BaseMigration
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 127);
            $table->string('username', 50);
            $table->string('password', 70);
            $table->timestamps();
            $table->tinyInteger('sex')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('lang')->default(0);
            $table->string('avatar', 50)->nullable();
            $table->char('reset_hash', 32)->nullable();
            $table->tinyInteger('verified')->nullable(false)->default(0);
            $table->string('guid', 255)->nullable();
            $table->unique('email');
            $table->unique('username');
            $table->engine = "InnoDB";
        });
    }


    /**
     * @throws \Exception
     */
    public function down()
    {
        throw new Exception('This migration cannot be rolled back: '.__CLASS__.' ('.$this->description().')');
    }


    public function description()
    {
        return 'Create new table users';
    }
}
