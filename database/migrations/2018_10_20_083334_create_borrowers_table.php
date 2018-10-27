<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('uuid', 36)->primary()->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('nationality');
            $table->string('id_image')->nullable();
            $table->string('id_number')->unique();
            $table->string('passport_number')->unique()->nullable();
            $table->string('telephone_number');
            $table->string('email')->nullable();
            $table->string('postal_address');
            $table->string('residential_address');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('spouse_type');
            $table->string('spouse_name');
            $table->string('spouse_id_number');
            $table->string('spouse_phone');
            $table->string('spouse_address');
            $table->string('members_status');
            $table->string('passport_photo')->nullable();

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
        Schema::dropIfExists('borrowers');
    }
}
