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
        function valaddAddon() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": true,
                "showDuration": "300",
                "timeOut": "10000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            if ($('#addon_name').val() == '') {
                toastr["error"]("Harap Masukan Nama Addon", "Perhatian")
            } else if ($('#addon_amount_view').val() == '') {
                toastr["error"]("Harap Masukan Harga Addon", "Perhatian")
            } else {
                addAddon();
            }
        }
        function generateTermin(i) {
            @php $id = 0; $ct = $data->termin()->count(); @endphp
            var teminold = {
              @foreach ($data->termin as $t)
                  {{$id++}}:'{{$t->amount}}'{{$id!=$ct?',':''}}
              @endforeach
            };
            var terminLength = $('.termin-item').length
            if (terminLength == 0) {
                for (let index = 0; index < i; index++) {
                    var no = index + 1;
                    if (index == 0) {
                        $('#termin').html(`<div class="termin-item termin-${no} row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Termin ${no}</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="termin[${no}][view]" id="termin_${no}_view" onchange="formatTermin(this.id,${no})"
                                            class="form-control form-control-lg form-control-solid" placeholder="Nominal Termin" value="`+(teminold[index]===undefined?'':toRp(teminold[index]))+`"/>
                                        <input type="hidden" name="termin[${no}][amount]" id="termin_${no}" value="`+(teminold[index]===undefined?'':teminold[index])+`"/>
                                    </div>
                                </div>`);
                    } else {
                        $('#termin').append(`<div class="termin-item termin-${no} row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Termin ${no}</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="termin[${no}][view]" id="termin_${no}_view" onchange="formatTermin(this.id,${no})"
                                            class="form-control form-control-lg form-control-solid" placeholder="Nominal Termin" value="`+(teminold[index]===undefined?'':toRp(teminold[index]))+`"/>
                                        <input type="hidden" name="termin[${no}][amount]" id="termin_${no}" value="`+(teminold[index]===undefined?'':teminold[index])+`"/>
                                    </div>
                                </div>`);
                    }
                }
            } else {
                if (terminLength < i) {
                    for (let index = terminLength; index < i; index++) {
                        var no = index + 1;
                        $('#termin').append(`<div class="termin-item termin-${no} row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Termin ${no}</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="termin[${no}][view]" id="termin_${no}_view" onchange="formatTermin(this.id,${no})"
                                            class="form-control form-control-lg form-control-solid" placeholder="Nominal Termin" value="`+(teminold[index]===undefined?'':toRp(teminold[index]))+`"/>
                                        <input type="hidden" name="termin[${no}][amount]" id="termin_${no}" value="`+(teminold[index]===undefined?'':teminold[index])+`"/>
                                    </div>
                                </div>`);
                    }
                } else if (terminLength > i) {
                    for (let index = terminLength; index > i; index--) {
                        $('.termin-' + index).remove();
                    }
                }
            }
        }
        $(document).ready(function() {
            $('#add-addon').click(function(e) {
                e.preventDefault();
                valaddAddon();
            });
            $("#addon_name").keydown(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    valaddAddon();
                }
            });
            $("#addon_date").keydown(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    valaddAddon();
                }
            });
            $("#addon_amount_view").keydown(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    valaddAddon();
                }
            });
            $("#payment_period").keydown(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
                if (this.value < 1) {
                    $(this).val(1);
                }
                if (this.value > 5) {
                    $(this).val(5);
                }
            });
            $("#payment_period").change(function(e) {
                var i = this.value;
                if (this.value < 0) {
                    $(this).val(0);
                    i = 0;
                }
                if (this.value > 5) {
                    $(this).val(5);
                    i = 5;
                }
                generateTermin(i);
            });
            if ($("#payment_period").val() != '') {
                if ($("#payment_period").val() < 0) {
                    $(this).val(0);
                }
                if ($("#payment_period").val() > 5) {
                    $(this).val(5);
                }
                generateTermin($("#payment_period").val());
            }
            $('#open_modal_button').click(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('product.modal-client') }}",
                    success: function(msg) {
                        $('#kt_modal_client').modal('show');
                        $('#modal-body').html(msg);
                    }
                });
            });
            $('#addon_amount_view').change(function(e) {
                e.preventDefault();
                $('#addon_amount').val(this.value);
                $('#addon_amount_view').val(toRp(this.value));
            });
            $('#maintenance_price_view').change(function(e) {
                e.preventDefault();
                function_elements_add('maintenance_price', this.value);
                $('#maintenance_price').val(this.value);
                $('#maintenance_price_view').val(toRp(this.value));
            });
            totalTermin();
            if ($('#maintenance_price').val() != '') {
                $('#maintenance_price_view').val(toRp($('#maintenance_price').val()));
            }
            $('#start_date').change(function(e) {
                var sd = moment($('#start_date').val()).format('YYYY-MM-DD');
                $('#trial_date').attr('min', sd);
                $('#usage_date').attr('min', sd);
                $('#maintenance_date').attr('min', sd);
                if (this.val > $('#trial_date').val()) {
                    $('#trial_date').val(sd)
                }
                if (this.val > $('#usage_date').val()) {
                    $('#usage_date').val(sd)
                }
                if (this.val > $('#maintenance_date').val()) {
                    $('#maintenance_date').val(sd)
                }
            });
            if ($('#start_date').val() != '') {
                var sd = moment($('#start_date').val()).format('YYYY-MM-DD');
                $('#trial_date').attr('min', sd);
                $('#usage_date').attr('min', sd);
                $('#maintenance_date').attr('min', sd);
            }
        });
        function function_elements_add(name, value) {
            $.ajax({
                type: "POST",
                url: "{{ route('product.element-add') }}",
                data: {
                    'name': name,
                    'value': value,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(msg) {}
            });
        }
        function addAddon() {
            var length = $('.product-addon').length;
            var name = $('#addon_name').val();
            var date = $('#addon_date').val();
            var amount = $('#addon_amount').val();
            var amount_view = $('#addon_amount_view').val();
            var remark = $('#addon_remark').val();
            $.ajax({
                type: "POST",
                url: "{{ route('product.add-addon') }}",
                data: {
                    'length': length,
                    'name': name,
                    'date': date,
                    'amount': amount,
                    'amount_view': amount_view,
                    'remark': remark,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(msg) {
                    $('#addon_name').val('');
                    $('#addon_date').val('');
                    $('#addon_amount_view').val('')
                    $('#addon_amount').val('')
                    $('#addon_remark').val('')
                    $('#addon_name').focus();
                    if (length == 0) {
                        $('#table-addon-content').html(msg);
                    } else {
                        $('#table-addon-content').append(msg);
                    }
                    toastr["success"]("Addon Berhasil Ditambah")
                }
            });
        }
        function deleteAddon(key) {
            var length = $('.product-addon').length;
            $.ajax({
                type: "POST",
                url: "{{ route('product.delete-addon') }}",
                data: {
                    'key': key,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(msg) {
                    if (msg.status == 1) {
                        $('#pa-' + key).remove();
                        if (length == 1) {
                            location.reload()
                        }
                    }
                }
            });
        }
        function formatTermin(id, key) {
            var val = $('#' + id).val();
            $('#termin_' + key).val(val);
            $('#' + id).val(toRp(val));
            totalTermin();
        }
        function totalTermin() {
            var terminLength = $('.termin-item').length
            var total = 0;
            for (let index = 1; index <= terminLength; index++) {
                var amount = $('#termin_' + index).val();
                if (amount == '') {
                    amount = 0;
                }
                total += parseInt(amount);
            }
            $('#termin_total').val(total);
            $('#termin_total_view').val(toRp(total));
        }
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
                <h3 class="fw-bolder m-0">{{ __('Form Tambah Produk') }}</h3>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>
        <div id="kt_product_add_view">
            <form id="kt_product_add_view_form" class="form" method="POST"
                action="{{ route('product.process-edit') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <div class="col-lg-6">
                            <div class="row mb-6">
                                <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Produk') }}</b>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Nama Produk') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" id="name"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Masukan Nama Produk"
                                        value="{{ old('name', $sessiondata['name'] ?? $data->name) }}" autocomplete="off"
                                        onchange="function_elements_add(this.name, this.value)" />
                                        <input type="hidden" name="product_id" id="product_id"
                                        value="{{ $data->product_id }}" /></div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Nama Klien') }}</label>
                                <div class="col-lg-auto fv-row">
                                    <input id="client_name" name="client_name"
                                        class="form-control form-control form-control-solid" data-kt-autosize="true"
                                        placeholder="Nama Client"
                                        value="{{ old('client_name', $sessiondata['client_name'] ?? $data->client->name) }}"
                                        readonly></input>
                                    <input id="client_id" name="client_id" type="hidden"
                                        value="{{ $sessiondata['client_id'] ?? $data->client_id }}"></input>
                                </div>
                                <div class="col-lg-auto fv-row">
                                    <button type="button" id="open_modal_button" class="btn btn-primary">
                                        {{ __('Cari Client') }}
                                    </button>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tipe') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="product_type_id" id="product_type_id" data-control="select2" autocomplete="off"
                                        aria-label="Pilih Tipe Produk" data-placeholder="{{ __('Pilih Tipe Produk') }}"
                                        data-allow-clear="true" class="form-select form-select-solid form-select-lg"
                                        onchange="function_elements_add(this.name, this.value)">
                                        <option value="">{{ __('Pilih') }}</option>
                                        @foreach ($type as $key => $value)
                                            <option data-kt-flag="{{ $key }}" value="{{ $key }}"
                                                {{ $key == old('product_type_id', $session['product_type_id'] ?? $data->product_type_id) ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tgl Mulai Pengerjaan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="start_date" id="start_date" type="date"
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('start_date', $sessiondata['start_date'] ?? $data->start_dev_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tgl Trial') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="trial_date" id="trial_date" type="date"
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('trial_date', $sessiondata['trial_date'] ?? $data->trial_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tgl Mulai Penggunaan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="usage_date" id="usage_date" type="date"
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('usage_date', $sessiondata['usage_date'] ?? $data->usage_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tgl Mulai Maintenance') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="maintenance_date" id="maintenance_date" type="date"
                                        class="form-control form-control-solid form-select-lg" placeholder="Tanggal"
                                        value="{{ old('maintenance_date', $sessiondata['maintenance_date'] ?? $data->maintenance_date) }}"
                                        onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Jenis Pembayaran') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="payment_type" id="payment_type" data-control="select2"
                                        aria-label="Pilih Tipe Produk" autocomplete="off"
                                        data-placeholder="{{ __('Pilih Jenis Pembayaran') }}" data-allow-clear="true"
                                        class="form-select form-select-solid form-select-lg"
                                        onchange="function_elements_add(this.name, this.value)">
                                        <option value="">{{ __('Pilih') }}</option>
                                        @foreach ($pymentType as $key => $value)
                                            <option data-kt-flag="{{ $key }}" value="{{ $key }}"
                                                {{ $key == old('payment_type', $session['payment_type'] ?? $data->payment_type) ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nominal per User Maintenance') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="maintenance_price_view" id="maintenance_price_view"
                                        class="form-control form-control-solid form-select-lg"
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
                                        placeholder="Msukan Keterangan Produk">{{ old('product_remark', $sessiondata['product_remark'] ?? $data->remark) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-6">
                                <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Termin') }}</b>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Jumlah Termin') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="number" min="1" max="5" name="payment_period" value="{{$data->termin()->count()??''}}"
                                        id="payment_period" class="form-control form-control-lg form-control-solid"
                                        placeholder="Jumlah Termin" />
                                </div>
                            </div>
                            <div class="termin" id="termin"></div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total Termin') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="termin_total_view" id="termin_total_view"
                                        class="form-control form-control-lg form-control-solid" readonly
                                        placeholder="Total Termin" />
                                    <input type="hidden" name="termin_total" id="termin_total" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Addon') }}</b>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nama') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="addon_name" id="addon_name"
                                        class="form-control form-control-lg form-control-solid" placeholder="Nama" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Tanggal') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="date" name="addon_date" id="addon_date"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Masukan Tanggal" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nominal') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="addon_amount_view" id="addon_amount_view"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Nominal/Harga Addon" />
                                    <input type="hidden" name="addon_amount" id="addon_amount" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Keterangan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <textarea type="text" name="addon_remark" id="addon_remark"
                                        class="form-control form-control-lg form-control-solid" placeholder="Msukan Keterangan Addon"></textarea>
                                </div>
                            </div>
                            <div class="row mb-6 justify-content-end">
                                <div class="col-auto text-right fv-row">
                                    <button class="btn btn-primary" type="button" id="add-addon">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table
                                id="table-product-addons"class="table table-bordered table-auto border-collapse table-striped table-hover align-middle rounded datatable">
                                <thead class="font-bold text-xl2">
                                    <th class="w-0.4">No</th>
                                    <th class="w-0.5">Tanggal</th>
                                    <th class="w-1/4">Nama</th>
                                    <th>Amount</th>
                                    <th>Keterangan</th>
                                    <th class="w-0.4">Aksi</th>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody class="table-group-divider" id="table-addon-content">
                                        @foreach ($addons as $key => $val)
                                        <tr class="product-addon" id="pa-{{ $key }}"
                                            data-id="{{ $key }}">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $val['date'] }}</td>
                                            <td>{{ $val['name'] }}</td>
                                            <td>Rp.{{ $val['amount_view'] }}</td>
                                            <td>{{ $val['remark'] }}</td>
                                            <td class="text-center"><button class="btn btn-sm btn-danger"
                                                    onclick="deleteAddon('{{ $key }}')"
                                                    type="button">Hapus</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
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
