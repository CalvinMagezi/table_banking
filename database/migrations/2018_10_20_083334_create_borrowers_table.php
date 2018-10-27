<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowersTable extends Migration
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

            $table->string('member_id', 36);
            $table->string('credit_score')->nullable();
            $table->string('borrower_status_id')->nullable();

            $table->string('spouse_type')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_id_number')->nullable();
            $table->string('spouse_phone')->nullable();
            $table->string('spouse_address')->nullable();

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
