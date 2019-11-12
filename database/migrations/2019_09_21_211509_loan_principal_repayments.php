<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoanPrincipalRepayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_principal_repayments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id', 36)->primary()->unique();
          //  $table->bigIncrements('id');

            $table->string('loan_id', 36);
            $table->integer('period_count'); // interval
            $table->date('due_date');
            $table->string('amount')->nullable();

            $table->timestamp('paid_on')->nullable();

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
        Schema::dropIfExists('loan_principal_repayments');
    }
}
