<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetLoanApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_loan_applications', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unique();

            $table->uuid('asset_id', 36);
            $table->uuid('loan_application_id', 36);

            //  $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade')->onUpdate('cascade');
            //   $table->foreign('loan_application_id')->references('id')->on('loan_applications')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_loan_applications');
    }
}
