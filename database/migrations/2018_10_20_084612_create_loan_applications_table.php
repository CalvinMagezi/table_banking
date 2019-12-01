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

            $table->string('id', 36)->primary()->unique();
            $table->string('branch_id', 36);

            $table->string('member_id', 36);
            $table->string('loan_officer_id', 36);

            $table->string('loan_type_id', 36);
            $table->string('interest_type_id', 36)->nullable();
            $table->string('interest_rate')->nullable();
            $table->string('service_fee')->nullable();

            $table->string('penalty_type_id', 36)->nullable();
            $table->string('penalty_value')->nullable();
            $table->string('penalty_frequency_id', 36)->nullable();

            $table->string('amount_applied');
            $table->string('repayment_period')->nullable();

            $table->string('payment_frequency_id', 36)->nullable();
            $table->string('periodic_payment_amount')->nullable(); //**

            $table->date('application_date');
            $table->string('witness_type_id', 36)->nullable();
            $table->string('witness_first_name')->nullable();
            $table->string('witness_last_name')->nullable();
            $table->string('witness_country')->nullable();
            $table->string('witness_county')->nullable();
            $table->string('witness_city')->nullable();
            $table->string('witness_national_id')->nullable();
            $table->string('witness_phone')->nullable();
            $table->string('witness_email')->nullable();
            $table->string('witness_postal_address')->nullable();
            $table->string('witness_residential_address')->nullable();

            $table->string('disburse_method_id')->nullable();
            $table->string('mpesa_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('other_banking_details')->nullable();
            $table->string('status_id', 36)->nullable();
            $table->string('witnessed_by_user_id', 36)->nullable();
            $table->string('reviewed_by_user_id', 36)->nullable();
            $table->string('reviewed_on')->nullable();
            $table->string('approved_on')->nullable();
            $table->string('rejected_on')->nullable();
            $table->string('rejection_notes')->nullable();
            $table->string('attach_application_form')->nullable();

            $table->string('created_by', 36)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->string('deleted_by', 36)->nullable();

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
