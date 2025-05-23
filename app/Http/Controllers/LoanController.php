<?php



namespace App\Http\Controllers;

use App\DataTables\LoanDataTable;
use App\Helper\Reply;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use AWS\CRT\HTTP\Request;

class LoanController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'app.menu.loans';

        $this->middleware(function ($request, $next) {
            abort_403(!in_array('loans', $this->user->modules));

            return $next($request);
        });
    }

    public function index(LoanDataTable $dataTable)
    {

        // Check permission for viewing loans
        $viewPermission = user()->permission('view_loans');
        $addLoanPermission = User()->permission('add_loans');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));


        return $dataTable->render('loans.index', $this->data,compact('addLoanPermission'));
    }




//     public function applyQuickAction(Request $request)
// {
//     if ($request->action_type == 'delete') {
//         Loan::whereIn('id', explode(',', $request->row_ids))->delete();
//         return Reply::success(__('messages.deleteSuccess'));
//     }

//     return Reply::error(__('messages.selectAction'));
// }
public function create()
{


    $this->addPermission = user()->permission('add_loans');
    $this->addEmployeePermission = user()->permission('add_employees');
    abort_403(!in_array($this->addPermission, ['all', 'added']));

    $this->employees = User::allEmployees(null, true, ($this->addPermission == 'all' ? 'all' : null));

    $this->currentDate = now()->format('Y-m-d');

    $dateFormat = Company::DATE_FORMATS;
    $this->dateformat = isset($dateFormat[$this->company->date_format]) ? $dateFormat[$this->company->date_format] : 'DD-MM-YYYY';

    if ($this->addPermission == 'added' && $this->addEmployeePermission == 'none') {
        $this->defaultAssign = user();
    }
    else if (request()->has('default_assign')) {
        $this->defaultAssign = User::with('roles')->findOrFail(request()->default_assign);
    }

    if (request()->ajax()) {
        $this->pageTitle = __('modules.loan.addLoan');
        $html = view('loans.ajax.create', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
    }

    $this->view = 'loans.ajax.create';

    return view('loans.create', $this->data);
}

}
