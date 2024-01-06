@section('scripts')
    <script>
        function function_elements_add(name, value) {
            $.ajax({
                type: "POST",
                url: "{{ route('invoice.element-add') }}",
                data: {
                    'name': name,
                    'value': value,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(msg) {}
            });
        }

        function totalAll() {
            var sbs = parseInt($('#sbs_amount').val());
            var adj = parseInt(($('#adjustmen').val() || 0));
            $('#sbs_after_adsjustment').val((sbs + adj));
            countDiscont()
        }

        function countDiscont() {
            var sbs = parseInt($('#sbs_after_adsjustment').val() || 0);
            var discountpercentage = $('#discount_percentage').val() || 0;
            var discountamount = (sbs * discountpercentage / 100);
            var total = sbs - discountamount;
            if (discountpercentage) {
                $('#discount_amount').val(discountamount);
                $('#discount_amount_view').val(toRp(discountamount));
                function_elements_add('discount_amount', discountamount);
            } else {
                $('#discount_amount').val('');
                $('#discount_amount_view').val('');
                function_elements_add('discount_amount', '');
            }
            $('#total_amount').val(total);
            $('#total_amount_view').val(toRp(total));
            countPPN()
        }
        function countPPN() {
            var sbs = parseInt($('#sbs_after_adsjustment').val() || 0);
            var ppnpercentage = $('#ppn_percentage').val() || 0;
            var ppnamount = parseInt(sbs * ppnpercentage / 100);
            var total = parseInt($('#total_amount').val());
            if (ppnpercentage) {
                $('#ppn_amount').val(ppnamount);
                $('#ppn_amount_view').val(toRp(ppnamount));
                function_elements_add('ppn_amount', ppnamount);
            } else {
                $('#ppn_amount').val('');
                $('#ppn_amount_view').val('');
                function_elements_add('ppn_amount', '');
            }
            $('#total_amount').val((total+ppnamount));
            $('#total_amount_view').val(toRp((total+ppnamount)));
        }
        function getMonth() {
            loading();
            var year = $('#year').val();
            var id = $('#product_id').val();
            $.ajax({
                type: "post",
                url: "{{route('invoice.month')}}",
                data: {
                '_token'        : '{{csrf_token()}}',
                'product_id'    :id,
                'year'          :year
                },
                success: function (response) {
                    $('#month').html(response);
                    autoRemark();
                    setTimeout(() => {
                        loading(0);
                    }, 200);
                }
            });
        }
        function autoRemark() {
            var month =$( "#month option:selected" ).text();;
            var year = $('#year').val();
            var name = $('#name').val();
            var text = `Maintenance Bulan ${month} ${year} ${name}`;
            $('#remark').html(text);
        }
        $(document).ready(function() {
            totalAll();
            if ($('#discount_amount').val() != '') {
                var dskon = $('#discount_amount').val();
                var total = $('#sbs_after_adsjustment').val();
                $('#discount_percentage').val((parseInt((dskon)) / parseInt(total) * 100));
                $('#discount_amount_view').val(toRp(dskon));
                totalAll();
            }
            if ($('#ppn_amount').val() != ''&&$('#ppn_amount').val() !== undefined) {
                var total = $('#sbs_after_adsjustment').val();
                var ppn = $('#ppn_amount').val();
                $('#ppn_percentage').val((parseInt((ppn)) / parseInt(total) * 100));
                $('#ppn_amount_view').val(toRp(ppn));
                totalAll();
            }
            $('#discount_percentage').change(function(e) {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                if ($(this).val() > 100) {
                    alert("Diskon tidak boleh diatas 100% !");
                    $(this).val(100);
                }
                totalAll();
            });
            $('#adjustmen_view').change(function(e) {
                if (this.value != '') {
                    $('#adjustmen').val(this.value);
                    $('#adjustmen_view').val(toRp(this.value));
                } else {
                    $('#adjustmen').val(0);
                }
                totalAll();
            });
            $('#discount_amount_view').change(function(e) {
                var total = $('#sbs_after_adsjustment').val();
                if (total != '') {
                    if (parseInt(this.value) > parseInt(total)) {
                        alert("Diskon tidak boleh diatas harga total !");
                        $('#discount_amount_view').val(total);
                    }
                    $('#discount_percentage').val((parseInt((this.value)) / parseInt(total) * 100));
                    $('#discount_amount').val(this.value);
                    $('#discount_amount_view').val(toRp(this.value));
                }
                totalAll();
            });
            $('#ppn_percentage').change(function(e) {
                e.preventDefault();
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                totalAll();
            });
            $('#ppn_amount_view').change(function(e) {
                e.preventDefault();
                var total = $('#sbs_after_adsjustment').val();
                if (total != '') {
                    $('#ppn_percentage').val((parseInt((this.value)) / parseInt(total) * 100));
                    $('#ppn_amount').val(this.value);
                    $('#ppn_amount_view').val(toRp(this.value));
                }
                totalAll();
            });
            if ($('#year').val() != ''){
                getMonth();
            }
        });
        //==============================END VALIDATION FORM ADD MEMBER ===============================\\
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
        .input-group > .select2-container--bootstrap5 {
            width: auto !important;
            flex: 1 1 auto !important;
        }
        .input-group > .select2-container--bootstrap5 .select2-selection--single {
            height: 100% !important;
            line-height: inherit !important;
            padding: 0.5rem 1rem !important;
        }
        .input-group:not(.has-validation) > .select2-container--bootstrap5 .select2-selection--single :not(:last-child) {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    </style>
@endsection
<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Form Tambah Invoice Maintenance') }}</h3>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>
        <div id="kt_product_add_view">
            <form id="kt_product_add_view_form" class="form" method="POST"
                action="{{ route('invoice.process-maintenance') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <div class="row  mb-6">
                            <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Produk') }}</b>
                        </div>
                        <div class="col-lg-6">

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Nama Produk') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" id="name"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Masukan Nama Produk"
                                        value="{{ old('name', $sessiondata['name'] ?? $data->name) }}" readonly
                                        autocomplete="off" onchange="function_elements_add(this.name, this.value)" />
                                    <input type="hidden" name="product_id" id="product_id"
                                        value="{{ $data->product_id }}" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Nama Klien') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input id="client_name" name="client_name"
                                        class="form-control form-control form-control-solid" data-kt-autosize="true"
                                        placeholder="Nama Client"
                                        value="{{ old('client_name', $sessiondata['client_name'] ?? $data->client->name) }}"
                                        readonly></input>
                                    <input id="client_id" name="client_id" type="hidden"
                                        value="{{ $sessiondata['client_id'] ?? $data->client_id }}"></input>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tipe') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="product_type_id"
                                        class="form-control form-control form-control-solid" data-kt-autosize="true"
                                        placeholder="Tipe Produk" id="product_type_id" readonly
                                        value="{{ $data->type->name }}">
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tgl Mulai Pengerjaan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="start_date" id="start_date" type="date" readonly
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('start_date', $sessiondata['start_date'] ?? $data->start_dev_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tgl Trial') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="trial_date" id="trial_date" type="date" readonly
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('trial_date', $sessiondata['trial_date'] ?? $data->trial_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>


                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tgl Mulai Penggunaan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="usage_date" id="usage_date" type="date" readonly
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('usage_date', $sessiondata['usage_date'] ?? $data->usage_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tgl Mulai Maintenance') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="maintenance_date" id="maintenance_date" type="date" readonly
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('maintenance_date', $sessiondata['maintenance_date'] ?? $data->maintenance_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Jenis Pembayaran') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="payment_type"
                                        class="form-control form-control form-control-solid" data-kt-autosize="true"
                                        readonly placeholder="Tipe Produk" id="payment_type"
                                        value="{{ $pType[$data->payment_type] }}">
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nominal per User Maintenance') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="maintenance_price_view" id="maintenance_price_view"
                                        class="form-control form-control-solid form-select-lg" readonly
                                        value="{{ number_format($data->maintenance_price, 2) }}"
                                        placeholder="Masukan Nominal per User Maintenance" />
                                    <input name="maintenance_price" type="hidden" id="maintenance_price"
                                        value="{{ old('maintenance_price', $sessiondata['maintenance_price'] ?? $data->maintenance_price) }}" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Keterangan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <textarea type="text" name="product_remark" id="product_remark"
                                        class="form-control form-control-lg form-control-solid" onchange="function_elements_add(this.name, this.value)"
                                        readonly placeholder="Msukan Keterangan Produk">{{ old('product_remark', $sessiondata['product_remark'] ?? $data->remark) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <div class="border row border-slate-400 rounded p-5">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Subtotal') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="sbs_amount_view"
                                    class="form-control form-control form-control-solid"
                                    value="{{ number_format($data->maintenance_price, 2) }}" data-kt-autosize="true"
                                    readonly placeholder="Total" id="sbs_amount_view" />
                                <input type="hidden" name="sbs_amount" value="{{ $data->maintenance_price }}"
                                    id="sbs_amount" />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Penyesuaian') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="adjustmen_view"
                                    class="form-control form-control form-control-solid" data-kt-autosize="true"
                                    placeholder="Penyesuaian" id="adjustmen_view" />
                                <input type="hidden" name="adjustmen" value="0" id="adjustmen" />
                                <input type="hidden" name="sbs_after_adsjustment" value="0"
                                    id="sbs_after_adsjustment" />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Bulan') }}</label>
                            <div class="col-lg-8 fv-row">
                                <div class="input-group">
                                {{ html()->select('month', $bln, ($sessiondata['month'] ?? 0))->class(['form-select', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Bulan', 'data-placeholder' => 'Pilih Bulan', 'data-allow-clear' => 'true', 'autocomplete' => 'off','onchange'=>'autoRemark()']) }}
                                <span class="input-group-text">Tahun</span>
                                {{ html()->select('year', $year, ($sessiondata['year'] ?? date('Y')))->class(['form-select', 'form-select-lg'])->attributes(['data-control' => 'select2', 'aria-label' => 'Pilih Tahun', 'data-placeholder' => 'Pilih Tahun', 'data-allow-clear' => 'true', 'autocomplete' => 'off','onchange'=>'getMonth()']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Diskon') }}</label>
                            <div class="col-lg-8 fv-row">
                                <div class="input-group">
                                    <input type="number" name="discount_percentage" min='0' max='100'
                                        class="form-control" autocomplete="off"
                                        value="{{ old('discount_percentage', $sessiondata['discount_percentage'] ?? 0) }}"
                                        data-kt-autosize="true"
                                        onchange="function_elements_add(this.name, this.value)" placeholder="Diskon %"
                                        id="discount_percentage" />
                                    <span class="input-group-text">%</span>
                                    <span class="input-group-text">Rp.</span>
                                    <input type="text" name="discount_amount_view" class="form-control"
                                        data-kt-autosize="true" placeholder="Jumlah Diskon" autocomplete="off"
                                        id="discount_amount_view" />
                                    <input name="discount_amount" type="hidden" autocomplete="off"
                                        value="{{ old('discount_amount', $sessiondata['discount_amount'] ?? 0) }}"id="discount_amount" />
                                </div>
                            </div>
                        </div>
                        @if(appHelper()->config('use_ppn'))
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('PPN') }}</label>
                            <div class="col-lg-8 fv-row">
                                <div class="input-group">
                                    <input type="number" name="ppn_percentage" min='0'
                                        class="form-control" autocomplete="off"
                                        value="{{ old('ppn_percentage', $sessiondata['ppn_percentage'] ?? appHelper()->config('ppn_percentage')) }}"
                                        data-kt-autosize="true"
                                        onchange="function_elements_add(this.name, this.value)" placeholder="PPN %"
                                        id="ppn_percentage" />
                                    <span class="input-group-text">%</span>
                                    <span class="input-group-text">Rp.</span>
                                    <input type="text" name="ppn_amount_view" class="form-control" autocomplete="off"
                                        data-kt-autosize="true" placeholder="Jumlah PPN" id="ppn_amount_view" />
                                    <input name="ppn_amount" type="hidden"
                                        value="{{ old('ppn_amount', $sessiondata['ppn_amount'] ?? 0) }}"id="ppn_amount" />
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="total_amount_view" autocomplete="off"
                                    class="form-control form-control form-control-solid" data-kt-autosize="true"
                                    readonly placeholder="Total" id="total_amount_view" />
                                <input type="hidden" name="total_amount" id="total_amount" />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Keterangan') }}</label>
                            <div class="col-lg-8 fv-row">
                                <textarea name="remark" class="form-control form-control-solid" placeholder="Keterangan" autocomplete="off" id="remark"></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <button type="reset"
                                    class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>
                                <button type="submit" class="btn btn-success" id="kt_product_add_submit">
                                    @include('partials.general._button-indicator', [
                                        'label' => __('Tambah'),
                                    ])
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="kt_modal_client">
        <div class="modal-dialog modal-lg">
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
