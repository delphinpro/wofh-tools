<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class TableNames
{
    public $Users;
    public $Roles;
    public $Permissions;
    public $Menu;
    public $RoleUsers;
    public $RolePermissions;
    public $UserPermissions;
    public $RoleMenu;
    public $OperationLog;


    public function __construct()
    {
        $this->Users = config('admin.database.users_table');
        $this->Roles = config('admin.database.roles_table');
        $this->Permissions = config('admin.database.permissions_table');
        $this->Menu = config('admin.database.menu_table');
        $this->RoleUsers = config('admin.database.role_users_table');
        $this->RolePermissions = config('admin.database.role_permissions_table');
        $this->UserPermissions = config('admin.database.user_permissions_table');
        $this->RoleMenu = config('admin.database.role_menu_table');
        $this->OperationLog = config('admin.database.operation_log_table');
    }
}


class CreateAdminTables extends Migration
{
    use \App\Helpers\MigrationHelper;


    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = new TableNames();

        // Schema::create($tableUsers, function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('username', 190)->unique();
        //     $table->string('password', 60);
        //     $table->string('name');
        //     $table->string('avatar')->nullable();
        //     $table->string('remember_token', 100)->nullable();
        //     $table->timestamps();
        // });

        Schema::create($names->Roles, function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        Schema::create($names->Permissions, function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
        });

        Schema::create($names->Menu, function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri')->nullable();
            $table->string('permission')->nullable();
            $table->timestamps();
        });

        Schema::create($names->RoleUsers, function (Blueprint $table) use ($names) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->index(['role_id', 'user_id']);
            $table->foreign('role_id')->references('id')->on($names->Roles);
            $table->foreign('user_id')->references('id')->on($names->Users);
            $table->timestamps();
        });

        Schema::create($names->RolePermissions, function (Blueprint $table) use ($names) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();
            $table->index(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on($names->Roles);
            $table->foreign('permission_id')->references('id')->on($names->Permissions);
            $table->timestamps();
        });

        Schema::create($names->UserPermissions, function (Blueprint $table) use ($names) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();
            $table->index(['user_id', 'permission_id']);
            $table->foreign('user_id')->references('id')->on($names->Users);
            $table->foreign('permission_id')->references('id')->on($names->Permissions);
            $table->timestamps();
        });

        Schema::create($names->RoleMenu, function (Blueprint $table) use ($names) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('menu_id')->unsigned();
            $table->index(['role_id', 'menu_id']);
            $table->foreign('role_id')->references('id')->on($names->Roles);
            $table->foreign('menu_id')->references('id')->on($names->Menu);
            $table->timestamps();
        });

        Schema::create($names->OperationLog, function (Blueprint $table) use ($names) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists(config('admin.database.users_table'));
        Schema::dropIfExists(config('admin.database.roles_table'));
        Schema::dropIfExists(config('admin.database.permissions_table'));
        Schema::dropIfExists(config('admin.database.menu_table'));
        Schema::dropIfExists(config('admin.database.user_permissions_table'));
        Schema::dropIfExists(config('admin.database.role_users_table'));
        Schema::dropIfExists(config('admin.database.role_permissions_table'));
        Schema::dropIfExists(config('admin.database.role_menu_table'));
        Schema::dropIfExists(config('admin.database.operation_log_table'));
    }
}
