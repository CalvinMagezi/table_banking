<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id', 36)->primary()->unique();
            $table->string('business_name')->unique();
            $table->string('business_type')->nullable();
            $table->string('email')->unique();
            $table->string('contact_first_name');
            $table->string('contact_last_name')->nullable();
            $table->string('currency')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('county')->nullable();
            $table->string('town')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

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
        Schema::dropIfExists('general_settings');
    }
}
