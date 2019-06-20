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
            $table->string('id', 36)->primary()->unique();

            $table->string('borrower_id', 36);
            $table->string('loan_type_id', 36);
            $table->string('loan_status_id', 36)->nullable();
            $table->string('loan_application_id', 36)->nullable();

            $table->string('branch_id', 36)->nullable();
            $table->string('approved_by_user_id', 36)->nullable();

            $table->string('loan_reference')->unique();
            $table->string('amount_applied');
            $table->string('amount_approved')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('date_approved')->nullable();
            $table->string('due_date')->nullable();

            $table->string('loan_witness_name')->nullable();
            $table->string('loan_witness_phone')->nullable();
            $table->string('loan_witness_relationship')->nullable();

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
