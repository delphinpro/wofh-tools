<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Schema\Blueprint;


class AddVoyagerUserFields extends MigrationHelper
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('role_id')->nullable()->after('id');
            $table->string('avatar')->nullable()->after('email')->default('users/default.png');
            $table->text('settings')->nullable()->default(null)->after('remember_token');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->dropColumnSafe('users', 'role_id');
        $this->dropColumnSafe('users', 'avatar');
        $this->dropColumnSafe('users', 'settings');
    }
}
