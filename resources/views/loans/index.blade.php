@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('filter-section')
<x-filters.filter-box>

    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
        <div class="select-status d-flex">
            <input type="text" class="form-control border-0 p-2 f-14" id="datatableRange" placeholder="@lang('placeholders.dateRange')">
        </div>
    </div>


    <div class="task-search d-flex py-1 px-lg-3 px-0 border-right-grey align-items-center">
        <form class="w-100">
            <div class="input-group bg-grey rounded">
                <div class="input-group-prepend">
                    <span class="input-group-text border-0 bg-additional-grey"><i class="fa fa-search"></i></span>
                </div>
                <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field" placeholder="@lang('app.startTyping')">
            </div>
        </form>
    </div>

    <div class="select-box d-flex py-1 px-lg-2 px-0">
        <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
            @lang('app.clearFilters')
        </x-forms.button-secondary>
    </div>

</x-filters.filter-box>
@endsection



@section('content')
<div class="content-wrapper">
    <div class="d-grid d-lg-flex d-md-flex action-bar">

        <div id="table-actions" class="flex-grow-1 align-items-center">

            <x-forms.link-primary :link="route('loans.create')" class="mr-3 openRightModal" icon="plus">
                Add New Loan
            </x-forms.link-primary>

        </div>



        <x-datatable.actions>
            <div class="select-status mr-3 pl-3">
                <select class="form-control select-picker" id="quick-action-type" disabled>
                    <option value="">@lang('app.selectAction')</option>
                    <option value="delete">@lang('app.delete')</option>
                </select>
            </div>
        </x-datatable.actions>
    </div>

    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
    </div>
</div>
@endsection


@push('scripts')
@include('sections.datatable_js')

<script>
    $('#loan-table').on('preXhr.dt', function (e, settings, data) {
        let range = $('#datatableRange').data('daterangepicker');
        let startDate = $('#datatableRange').val();

        if (!startDate) {
            data.startDate = null;
            data.endDate = null;
        } else {
            data.startDate = range.startDate.format('{{ company()->moment_date_format }}');
            data.endDate = range.endDate.format('{{ company()->moment_date_format }}');
        }

        data.searchText = $('#search-text-field').val();
    });

    const showTable = () => {
        window.LaravelDataTables["loan-table"].draw();
    };

    $('#search-text-field').on('keyup change', function () {
        $('#reset-filters').removeClass('d-none');
        showTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#search-text-field').val('');
        $('#datatableRange').val('');
        $('#reset-filters').addClass('d-none');
        showTable();
    });

    $('#quick-action-type').change(function () {
        $('#quick-action-apply').prop('disabled', !$(this).val());
    });

    $('#quick-action-apply').click(function () {
        let action = $('#quick-action-type').val();
        if (action === 'delete') {
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
            }).then((result) => {
                if (result.isConfirmed) applyQuickAction();
            });
        } else {
            applyQuickAction();
        }
    });

    function applyQuickAction() {
        let ids = $("#loan-table input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        $.easyAjax({
            url: "{{ route('loans.apply_quick_action') }}?row_ids=" + ids,
            container: '#quick-action-form',
            type: 'POST',
            data: $('#quick-action-form').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    showTable();
                }
            }
        });
    }

    $('body').on('click', '.delete-table-row', function () {
        let id = $(this).data('loan-id');
        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('app.cancel')",
        }).then((result) => {
            if (result.isConfirmed) {
                $.easyAjax({
                    type: 'POST',
                    url: "{{ route('loans.destroy', ':id') }}".replace(':id', id),
                    data: {
                        '_token': "{{ csrf_token() }}",
                        '_method': 'DELETE'
                    },
                    success: function (response) {
                        if (response.status == 'success') showTable();
                    }
                });
            }
        });
    });
</script>
@endpush

