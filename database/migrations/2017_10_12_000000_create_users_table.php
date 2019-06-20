<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 36)->primary()->unique();

            $table->uuid('role_id', 36);
            $table->uuid('employee_id', 36)->unique();

            $table->string('email')->unique();
            $table->string('password', 60);

            /*$table->string('first_name');
            $table->string('last_name');
            $table->string('salutation')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();*/

            $table->boolean('confirmed')->default(false);
            $table->string('confirmation_code')->nullable();

            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
