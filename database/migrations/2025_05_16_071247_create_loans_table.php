<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., LOAN-001
            $table->date('date');
            $table->string('employee_name');
            $table->string('company');
            $table->string('department');
            $table->string('designation');
            $table->date('loan_start_date');
            $table->date('loan_end_date');
            $table->decimal('loan_amount', 10, 2);
            $table->decimal('per_installment_amount', 10, 2);
            $table->integer('number_of_installments');
            $table->decimal('loan_deduction_amount', 10, 2)->default(0);
            $table->decimal('loan_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->boolean('auto_deduction')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
