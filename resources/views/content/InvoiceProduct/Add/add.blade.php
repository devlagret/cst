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
        function total(id,ammount) {
            var total = parseInt($('#sbs_amount').val());
            if($('#'+id).is(":checked")){
                $('#sbs_amount_view').val(toRp((total+parseInt(ammount))));
                $('#sbs_amount').val((total+parseInt(ammount)));
                countDiscont();
                function_elements_add(id,'checked');
            }else{
                $('#sbs_amount_view').val(toRp((total-parseInt(ammount))));
                $('#sbs_amount').val((total-parseInt(ammount)));
                countDiscont();
                function_elements_add(id,'');
            }
        }
        function countDiscont(){
            var sbs = parseInt($('#sbs_amount').val());
            var discountpercentage =  $('#discount_percentage').val()||0;
            var total = sbs-(sbs*discountpercentage/100);
            $('#total_amount').val(total);
            $('#total_amount_view').val(toRp(total));
            countPPN();
        }
        function countPPN() {
            var sbs = parseInt($('#sbs_amount').val() || 0);
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
        $(document).ready(function () {
            countDiscont();
            var total = 0;
            $('.cb-termin').each(function (index, element) {
                if($(this).is(":checked")){
                    total += parseInt($('#termin-amount-'+$(this).val()).val());
                }
                $('#sbs_amount').val(total);
                $('#sbs_amount_view').val(toRp(total));
                countDiscont();
            });
            // if ($('#ppn_amount').val() != ''&&$('#ppn_amount').val() !== undefined) {
            //     var total = $('#sbs_amount').val();
            //     var ppn = $('#ppn_amount').val();
            //     $('#ppn_percentage').val((parseInt((ppn)) / parseInt(total) * 100));
            //     $('#ppn_amount_view').val(toRp(ppn));
            //     countDiscont();
            // }
            $('.cb-addon').each(function (index, element) {
                if($(this).is(":checked")){
                    total += parseInt($('#addon-amount-'+$(this).val()).val());
                }
                $('#sbs_amount').val(total);
                $('#sbs_amount_view').val(toRp(total));
                countDiscont();
            });
            if($('#discount_percentage').val()!=''){
                countDiscont();
            }
            $('#discount_percentage').change(function (e) {
               if($(this).val()<0){
                $(this).val(0);
               }
               if($(this).val()>100){
                $(this).val(100);
               }
               countDiscont();
            });
            $('#ppn_percentage').change(function(e) {
                e.preventDefault();
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                countDiscont();
            });
            $('#ppn_amount_view').change(function(e) {
                e.preventDefault();
                var total = $('#sbs_amount').val();
                if (total != '') {
                    $('#ppn_percentage').val((parseInt((this.value)) / parseInt(total) * 100));
                    $('#ppn_amount').val(this.value);
                    $('#ppn_amount_view').val(toRp(this.value));
                }
                countDiscont();
            });
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
    </style>
@endsection
<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Form Tambah Invoice') }}</h3>
            </div>
            <a href="{{ route('invoice.list-add') }}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>
        <div id="kt_product_add_view">
            <form id="kt_product_add_view_form" class="form" method="POST"
                action="{{ route('invoice.process-add') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <div class="row  mb-6">
                            <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Produk') }}</b>
                        </div>
                        <div class="col-lg-6">

                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Nama Produk') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" id="name"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Masukan Nama Produk"
                                        value="{{ old('name', $sessiondata['name'] ?? $data->name) }}" readonly autocomplete="off"
                                        onchange="function_elements_add(this.name, this.value)" />
                                    <input type="hidden" name="product_id" id="product_id" value="{{ $data->product_id }}" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Nama Klien') }}</label>
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
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tipe') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="product_type_id" class="form-control form-control form-control-solid" data-kt-autosize="true"
                                    placeholder="Tipe Produk" id="product_type_id" readonly value="{{ $data->type->name}}">
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
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Tgl Trial') }}</label>
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
                                    <input type="text" name="payment_type" class="form-control form-control form-control-solid" data-kt-autosize="true" readonly
                                    placeholder="Tipe Produk" id="payment_type" value="{{$pType[$data->payment_type]}}">
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nominal per User Maintenance') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="maintenance_price_view" id="maintenance_price_view"
                                        class="form-control form-control-solid form-select-lg" readonly value="{{number_format($data->maintenance_price,2)}}"
                                        placeholder="Masukan Nominal per User Maintenance" />
                                    <input name="maintenance_price" type="hidden" id="maintenance_price"
                                        value="{{ old('maintenance_price', $sessiondata['maintenance_price'] ?? $data->maintenance_price) }}" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Keterangan') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <textarea type="text" name="product_remark" id="product_remark"
                                        class="form-control form-control-lg form-control-solid" onchange="function_elements_add(this.name, this.value)" readonly
                                        placeholder="Msukan Keterangan Produk">{{ old('product_remark', $sessiondata['product_remark'] ?? $data->remark) }}</textarea>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                    @if($data->termin->count())
                    <div class="row">
                        <div class="row mb-1">
                            <b class="col-lg-12 fw-bold fs-1 text-center text-primary">{{ __('Bayar Termin') }}</b>
                        </div>
                        <div class="row p-3">
                        <div class="table-responsive mx-4">
                            <table
                                id="table-product-addons"class="table table-bordered table-auto border-collapse table-striped table-hover align-middle rounded datatable">
                                <thead class="font-bold text-xl2">
                                    <th class="w-0.1">No</th>
                                    <th class="w-5">Harga </th>
                                    <th class="w-1/2">Keterangan</th>
                                    <th class="w-0.2">Aksi</th>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody class="table-group-divider" id="table-termin-content">
                                    @foreach ($data->termin as $key => $val)
                                        <tr class="product-termin" id="pa-{{ $key }}"
                                            data-id="{{ $key }}">
                                            <td>{{ $no++ }}</td>
                                            <td>Rp.{{ number_format($val->amount,2) }} <input type="hidden" name="termin[{{$val->termin_id}}][amount]" class="termin-amount" id="termin-amount-{{$val->termin_id}}" value="{{$val->amount}}"> </td>
                                            <td class="p-0"><input name="termin[{{$val->termin_id}}][remark]" id="termin_remark_{{$val->termin_id}}"
                                                class="form-control form-control-solid form-control-sm "
                                                placeholder="Masukan Keterangan Termin " /></td>
                                            <td class="text-center checkbox-xl"><input type="checkbox" id="termin-{{$val->termin_id}}" class="cb-termin form-check-input w-4 h-4" name="termin[{{$val->termin_id}}][id]" {{ $sessiondata["termin-".$val->termin_id]??'' }} onclick="total(this.id,{{$val->amount}})" value="{{$val->termin_id}}"/></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        </div>
                    </div>
                    @endif
                    @if($data->addons->count())
                    <div class="row">
                        <div class="row mb-1">
                            <b class="col-lg-12 fw-bold fs-1 text-center text-primary">{{ __('Bayar Addons') }}</b>
                        </div>
                        <div class="table-responsive">
                            <table
                                id="table-product-addons"class="table table-bordered table-auto border-collapse table-striped table-hover align-middle rounded datatable">
                                <thead class="font-bold text-xl2">
                                    <th class="w-0.2">No</th>
                                    <th class="w-0.2">Tanggal</th>
                                    <th class="w-1/4">Nama</th>
                                    <th class="w-0.2">Harga</th>
                                    <th class="w-1/2">Keterangan</th>
                                    <th class="w-0.5">Aksi</th>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody class="table-group-divider" id="table-addon-content">
                                    @foreach ($data->addons as $key => $val)
                                        <tr class="product-addon" id="pa-{{ $key }}"
                                            data-id="{{ $key }}">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $val['date'] }}</td>
                                            <td>{{ $val['name'] }}</td>
                                            <td>Rp.{{ number_format($val->amount,2) }} <input type="hidden" name="addon[{{$val->product_addon_id}}][amount]" class="addon-amount" id="addon-amount-{{$val->product_addon_id}}" value="{{$val->amount}}"> </td>
                                            <td>{{ $val['remark'] }}</td>
                                            <td class="text-center checkbox-xl"><input type="checkbox" id="addon-{{$val->product_addon_id}}" class="cb-addon form-check-input w-4 h-4" name="addon[{{$val->product_addon_id}}][id]" value="{{$val->product_addon_id}}" onclick="total(this.id,{{$val->amount}})"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <div class="border row border-slate-400 rounded p-3">
                        <div class="row mb-6">
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Subtotal') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="sbs_amount_view" class="form-control form-control form-control-solid" autocomplete="off" data-kt-autosize="true" readonly
                                placeholder="Total" id="sbs_amount_view" />
                                <input type="hidden" name="sbs_amount" value="0" autocomplete="off" id="sbs_amount" />
                            </div>
                        </div>
                        @if(appHelper()->config('use_discount'))
                        <div class="row mb-6">
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Diskon') }}</label>
                            <div class="col-lg-8 fv-row">
                                <div class="input-group">
                                    <input type="number" name="discount_percentage" min='0' max='100' class="form-control" value="{{old('discount_percentage',$sessiondata['discount_percentage']??0)}}" placeholder="Diskon" id="discount_percentage" />
                                    <span class="input-group-text">%</span>
                                    <span class="input-group-text">Rp.</span>
                                    <input type="text" name="discount_amount" class="form-control w-25" value="{{old('discount_amount',$sessiondata['discount_amount']??0)}}" data-kt-autosize="true" placeholder="Diskon" id="discount_amount" />
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(appHelper()->config('use_ppn'))
                        <div class="row mb-6">
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">{{ __('PPN') }}</label>
                            <div class="col-lg-8 fv-row">
                                <div class="input-group">
                                    <input type="number" name="ppn_percentage" min='0' max='100' class="form-control" value="{{old('ppn_percentage',$sessiondata['ppn_percentage']??appHelper()->config('ppn_percentage'))}}" autocomplete="off" placeholder="PPN" id="ppn_percentage" />
                                    <span class="input-group-text">%</span>
                                    <span class="input-group-text">Rp.</span>
                                    <input type="text" name="ppn_amount_view" class="form-control w-25" value="{{old('ppn_amount_view',$sessiondata['ppn_amount_view']??0)}}" autocomplete="off" data-kt-autosize="true" placeholder="PPN" id="ppn_amount_view" />
                                    <input type="hidden" name="ppn_amount" value="{{old('ppn_amount',$sessiondata['ppn_amount']??0)}}" autocomplete="off" id="ppn_amount" />
                                 </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-6">
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Total') }}</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="total_amount_view" class="form-control form-control form-control-solid" data-kt-autosize="true" readonly
                                placeholder="Total" id="total_amount_view" />
                                <input type="hidden" name="total_amount" value="0" id="total_amount" />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Keterangan') }}</label>
                            <div class="col-lg-8 fv-row">
                                <textarea name="remark" class="form-control form-control-solid"  placeholder="Keterangan" id="remark" ></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-auto">

                                <button type="reset"
                                class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>
                                <button type="submit" class="btn btn-success" id="kt_product_add_submit">
                                    @include('partials.general._button-indicator', ['label' => __('Bayar')])
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