<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'date', 'employee_name', 'company', 'department',
        'designation', 'loan_start_date', 'loan_end_date', 'loan_amount',
        'per_installment_amount', 'number_of_installments',
        'loan_deduction_amount', 'loan_paid', 'balance', 'remarks',
        'auto_deduction'
    ];


}
