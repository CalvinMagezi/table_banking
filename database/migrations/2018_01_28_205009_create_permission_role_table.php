<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unique();

            $table->uuid('permission_id', 36);
            $table->uuid('role_id', 36);

            $table->softDeletes();
            $table->timestamps();

         //  $table->foreign('permission_id')->references('uuid')->on('permissions')->onDelete('cascade')->onUpdate('cascade');
        //   $table->foreign('role_id')->references('uuid')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
