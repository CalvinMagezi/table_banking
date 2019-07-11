<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 36)->primary()->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('nationality');
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('id_number')->unique();
            $table->string('passport_number')->unique()->nullable();

            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('postal_address');
            $table->string('residential_address');

            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_branch')->nullable();

            $table->string('status_id')->nullable();
            $table->string('passport_photo')->nullable();
            $table->string('national_id_image')->nullable();

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
        Schema::dropIfExists('members');
    }
}
