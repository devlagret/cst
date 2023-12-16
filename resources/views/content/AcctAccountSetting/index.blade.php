@section('scripts')
    <script>
        const form = document.getElementById('kt_product_add_view_form');
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Nama Produk harus diisi'
                            }
                        }
                    },
                    'client_name': {
                        validators: {
                            notEmpty: {
                                message: 'Klien harus diisi'
                            }
                        }
                    },
                    'product_type_id': {
                        validators: {
                            notEmpty: {
                                message: 'Tipe Produk harus diisi'
                            }
                        }
                    },
                    'phone': {
                        validators: {
                            notEmpty: {
                                message: 'Nomer Handphone harus diisi'
                            }
                        }
                    },
                },
                plugins: {
                    kt_client_add_submit: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );
    </script>
@endsection
@section('styles')
    <style type="text/css">
        table,
        tr,
        td {
            border: 1px solid !important;
            border-color: #B5B5C3 !important;
            border-bottom-color: #B5B5C3 !important;
        }

        td {
            border: 1px solid !important;
            border-color: #B5B5C3 !important;
            border-bottom-color: #B5B5C3 !important;
        }

        tr {
            border-color: #B5B5C3 !important;
            border-bottom-color: #B5B5C3 !important;
        }

        table {
            border-radius: 0.25rem !important;
        }

        .table tr:first-child,
        .table th:first-child,
        .table td:first-child {
            padding: 0.75rem !important;
        }

        .table tr:last-child,
        .table th:last-child,
        .table td:last-child {
            padding: 0.75rem !important;
        }
    </style>
@endsection
<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Pengaturan Akun') }}</h3>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>
        <div id="kt_product_add_view">
            <form id="kt_product_add_view_form" class="form" method="POST"
                action="{{ route('account-setting.process-add') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row mb-6 px-12 mx-12">
                        <div class="row mb-4">
                            <b
                                class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Penjualan Software') }}</b>
                        </div>
                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Kas') }}</label>
                            <div class="col-lg-8 fv-row">
                                {{ html()->select('application_receivables', $account, $data->where('account_setting_name', 'application_receivables')->pluck('account_id'))->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                            <div class="col-lg-2 fv-row">
                                {{ html()->select('application_receivables_status', $status, $data->where('account_setting_name', 'application_receivables')->pluck('account_setting_status'))->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Hutang') }}</label>
                            <div class="col-lg-8 fv-row">
                                {{ html()->select('application_cash_receivables', $account, $data->where('account_setting_name', 'application_cash_receivables')->pluck('account_id'))->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                            <div class="col-lg-2 fv-row">
                                {{ html()->select('application_cash_receivables_status', $status, $data->where('account_setting_name', 'application_cash_receivables')->pluck('account_setting_status')[0])->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6 px-12 mx-12">
                        <div class="row mb-4">
                            <b
                                class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Penjualan Multimedia') }}</b>
                        </div>
                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Kas') }}</label>
                            <div class="col-lg-8 fv-row">
                                {{ html()->select('application_receivables', $account, $data->where('account_setting_name', 'application_receivables')->pluck('account_id'))->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                            <div class="col-lg-2 fv-row">
                                {{ html()->select('application_receivables_status', $status, $data->where('account_setting_name', 'application_receivables')->pluck('account_setting_status'))->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Hutang') }}</label>
                            <div class="col-lg-8 fv-row">
                                {{ html()->select('application_cash_receivables', $account, $data->where('account_setting_name', 'application_cash_receivables')->pluck('account_id'))->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                            <div class="col-lg-2 fv-row">
                                {{ html()->select('application_cash_receivables_status', $status, $data->where('account_setting_name', 'application_cash_receivables')->pluck('account_setting_status')[0])->class(['form-select', 'form-select-solid', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Akun', 'data-placeholder' => 'Pilih Akun', 'data-allow-clear' => 'true', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset"
                        class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-primary" id="kt_product_add_submit">
                        @include('partials.general._button-indicator', ['label' => __('Simpan')])
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="kt_modal_client">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Daftar Client</h3>

                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="bi bi-x-lg"></span>
                    </div>
                </div>

                <div class="modal-body" id="modal-body">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-base-layout>
