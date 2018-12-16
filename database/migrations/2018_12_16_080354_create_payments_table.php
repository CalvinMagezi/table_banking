<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('uuid', 36)->primary()->unique();

            $table->string('loan_id');
            $table->string('payment_amount');
            $table->string('payment_method_id');

            $table->string('payment_date');
            $table->string('paid_to')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('attachment')->nullable();
            $table->string('payment_notes')->nullable();

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
        Schema::dropIfExists('payments');
    }
}
