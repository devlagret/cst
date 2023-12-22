
<td class="text-center">
    @if($model->invoice_type==3)
    <a type="button" href="{{route('invoice.print-maintenance',$model->invoice_id)}}" class="btn btn-sm btn-primary btn-active-light-primary">
        Nota Maintenance
    </a>
    @if($model->invoice_status==0)
        <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_pay_{{$model->invoice_id}}" class="btn p-2 btn-sm btn-success btn-active-light-success">
            <i class="bi bi-cash  fs-2"></i>Bayar
        </button>
    @endif
    @else
    <a type="button" href="{{route('invoice.product',$model->invoice_id)}}" class="btn btn-sm btn-info btn-active-light-info">
        Nota Produk
    </a>
    <a type="button" href="{{route('invoice.product',$model->invoice_id)}}" class="btn btn-sm btn-success btn-active-light-success">
        Nota Addon
    </a>
    @endif
</td>

<div class="modal fade" tabindex="-1" id="kt_modal_delete_{{$model->invoice_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Produk</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus Produk?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                <a href="{{route('invoice.delete',$model->invoice_id)}}" class="btn btn-danger">Iya</a>
            </div>
        </div>
    </div>
</div>
@if($model->invoice_status==0)
<div class="modal fade" tabindex="-1" id="kt_modal_pay_{{$model->invoice_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Bayar Invoice</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <form action="{{route('invoice.pay',$model->invoice_id)}}" id="form_pay_{{$model->invoice_id}}" method="post">
            <div class="modal-body">
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Jumlah Tagihan') }}</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="total_amount_view" id="total_amount_view_{{$model->invoice_id}}"
                            class="form-control form-control-lg form-control-solid"
                            placeholder="Jumlah Tagihan"
                            value="{{ number_format($model->total_amount,2) }}" readonly
                            autocomplete="off"/>
                        <input type="hidden" name="product_id" id="product_id_{{$model->invoice_id}}"
                            value="{{ $model->invoice_id }}" />
                        <input type="hidden" name="total_amount" id="total_amount_{{$model->invoice_id}}"
                            value="{{ $model->total_amount }}" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Metode Pembayaran') }}</label>
                    <div class="col-lg-8 fv-row">
                        {!! html()->select('payment_type_{{$model->invoice_id}}', $paymentType,0)->attributes(['data-control'=>"select2", 'aria-label'=>"Pilih No.Perkiraan", 'data-placeholder'=>"Pilih No. Perkiraan",'autocomplete'=>'off', 'data-allow-clear'=>"true",'onchange'=>"function_elements_add(this.name, this.value)" ])->class(['form-select form-select-solid form-select-lg']) !!}
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Dibayar') }}</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="payed_amount_view" onchange="calcReturn({{$model->invoice_id}})" id="payed_amount_view_{{$model->invoice_id}}"
                            class="form-control form-control-lg form-control-solid required"
                            placeholder="Jumlah Dibayar"
                            autocomplete="off"/>
                        <input type="hidden" name="payed_amount" id="payed_amount_{{$model->invoice_id}}"/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6 ">{{ __('Kembalian') }}</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="change_amount" id="change_amount_view_{{$model->invoice_id}}"
                            class="form-control form-control-lg form-control-solid"
                            placeholder="Jumlah Kembalian" readonly
                            autocomplete="off"/>
                        <input type="hidden" name="change_amount" id="change_amount_{{$model->invoice_id}}"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tidak</button>
                <button type="button" onclick="checkPayed({{$model->invoice_id}})" class="btn btn-success">Iya</button>
            </div>
            </form
        </div>
    </div>
</div>
@endif
