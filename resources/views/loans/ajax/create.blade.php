<div class="modal-header">
    <h5 class="modal-title">@lang('modules.loan.addLoan')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <x-form id="createLoanForm" method="POST" class="ajax-form" action="{{ route('loans.store') }}">
        @csrf

        <x-forms.select fieldId="employee_id" :fieldLabel="__('app.employee')" fieldName="employee_id" required="true">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </x-forms.select>

        <x-forms.text fieldId="amount" :fieldLabel="__('app.amount')" fieldName="amount" required="true" />
        <x-forms.date-picker fieldId="loan_date" :fieldLabel="__('app.date')" fieldName="loan_date" required="true" />

        <x-forms.textarea fieldId="remarks" :fieldLabel="__('app.remarks')" fieldName="remarks" />

        <x-forms.button-primary class="mt-3" icon="check">
            @lang('app.save')
        </x-forms.button-primary>
    </x-form>
</div>
