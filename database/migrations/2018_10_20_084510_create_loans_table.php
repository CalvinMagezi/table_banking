<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('uuid', 36)->primary()->unique();

            $table->string('member_id', 36);
            $table->string('approved_by_user_id', 36);


            $table->string('loan_ref')->unique();
            $table->string('amount_applied');
            $table->string('amount_approved')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('date_approved')->nullable();
            $table->string('due_date')->nullable();
            $table->string('loan_status')->nullable();
            $table->string('application_id')->unique();

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
        Schema::dropIfExists('loans');
    }
}
