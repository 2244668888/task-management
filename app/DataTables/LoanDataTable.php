<?php

namespace App\DataTables;

use App\Models\Loan;
use Yajra\DataTables\Services\DataTable;

class LoanDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('date', function ($row) {
                return optional($row->date)->format('d M Y');
            })
            ->editColumn('loan_start_date', function ($row) {
                return optional($row->loan_start_date)->format('d M Y');
            })
            ->editColumn('loan_end_date', function ($row) {
                return optional($row->loan_end_date)->format('d M Y');
            })

            ->addColumn('action', function($row){
                return '
                    <a href="'.route('loans.edit', $row->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <form action="'.route('loans.destroy', $row->id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
            })



            ->rawColumns(['action']);
    }


    public function query(Loan $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('loans-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false],
            ['data' => 'code', 'name' => 'code', 'title' => 'Code'],
            ['data' => 'date', 'name' => 'date', 'title' => 'Date'],
            ['data' => 'employee_name', 'name' => 'employee_name', 'title' => 'Employee Name'],
            ['data' => 'company', 'name' => 'company', 'title' => 'Company'],
            ['data' => 'department', 'name' => 'department', 'title' => 'Department'],
            // ['data' => 'designation', 'name' => 'designation', 'title' => 'Designation'],
            // ['data' => 'loan_start_date', 'name' => 'loan_start_date', 'title' => 'Start Date'],
            // ['data' => 'loan_end_date', 'name' => 'loan_end_date', 'title' => 'End Date'],
            // ['data' => 'loan_amount', 'name' => 'loan_amount', 'title' => 'Loan Amount'],
            // ['data' => 'per_installment_amount', 'name' => 'per_installment_amount', 'title' => 'Installment'],
            // ['data' => 'number_of_installments', 'name' => 'number_of_installments', 'title' => 'Installments'],
            // ['data' => 'loan_deduction_amount', 'name' => 'loan_deduction_amount', 'title' => 'Deduction'],
            // ['data' => 'loan_paid', 'name' => 'loan_paid', 'title' => 'Paid'],
            // ['data' => 'balance', 'name' => 'balance', 'title' => 'Balance'],
            // ['data' => 'remarks', 'name' => 'remarks', 'title' => 'Remarks'],
            // ['data' => 'auto_deduction', 'name' => 'auto_deduction', 'title' => 'Auto Deduct'],
            // ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
        ];
    }


    protected function filename(): string
    {
        return 'Loans_' . date('YmdHis');
    }

}
