<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('uuid', 36)->primary()->unique();

            $table->string('member_id', 36);
            $table->string('reviewed_by_user_id', 36)->nullable();
            $table->string('approved_by_user_id', 36)->nullable();

            $table->string('application_date');
            $table->string('amount_applied');
            $table->string('repayment_period');
            $table->string('date_approved');
            $table->string('loan_status_id', 36);

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
        Schema::dropIfExists('loan_applications');
    }
}
