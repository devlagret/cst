
<td class="text-center">
    @if($model->invoice_status==0)
    <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_print_{{$model->invoice_id}}" class="btn ms-1 mt-1 btn-sm btn-success btn-active-light-success">
        <i class="bi bi-printer-fill fs-2"></i>Cetak
    </button>
    <a type="button" href="{{route('invoice.print',$model->invoice_id)}}" class="btn ms-1 mt-1 btn-sm btn-warning btn-active-light-warning">
        <i class="bi bi-pencil-square fs-2"></i>
        Edit
    </a>
    <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_reject_{{$model->invoice_id}}" class="btn ms-1 mt-1 btn-sm btn-danger btn-active-light-danger">
        <i class="fa-regular fa-circle-xmark fs-2"></i>Reject
    </button> 
    @endif
    @if($model->invoice_status==1)
        <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_pay_{{$model->invoice_id}}" class="btn ms-1 mt-1 btn-sm btn-success btn-active-light-success">
            <i class="bi bi-cash  fs-2"></i>Bayar
        </button>
        <a type="button" href="{{route('invoice.print',$model->invoice_id)}}" class="btn ms-1 mt-1 btn-sm btn-primary btn-active-light-primary">
            Cetak
        </a>
    @endif
</td>
@if($model->invoice_status==0)
<div class="modal fade" tabindex="-1" id="kt_modal_reject_{{$model->invoice_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Cetak Invoice</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-2">Produk</div>
                    <div class="col-auto">:</div>
                    <div class="col-9 text-start">{{str($model->product->name)->limit(33)}}</div>
                </div>
                <div class="row mb-6">
                    <div class="col-2">Client</div>
                    <div class="col-auto">:</div>
                    <div class="col-9 text-start">{{str($model->client->name)->limit(33)}}</div>
                </div>
                <div class="row mb-6 text-start">
                    <div class="col">
                        Apakah Anda Yakin Ingin Mereject Invoice?
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tidak</button>
                <a type="button" href="{{route('invoice.print',$model->invoice_id)}}" class="btn btn-danger btn-active-light-danger">
                    Iya
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="kt_modal_print_{{$model->invoice_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Cetak Invoice</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-2">Produk</div>
                    <div class="col-auto">:</div>
                    <div class="col-9 text-start">{{str($model->product->name)->limit(33)}}</div>
                </div>
                <div class="row mb-6">
                    <div class="col-2">Client</div>
                    <div class="col-auto">:</div>
                    <div class="col-9 text-start">{{str($model->client->name)->limit(33)}}</div>
                </div>
                <div class="row mb-6 text-start">
                    <div class="col">
                        Apakah Anda Yakin Ingin Mencetak Nota Untuk Pertama Kali? Setelah dicetak invoice <b>tidak bisa diubah atau diedit</b>!
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tidak</button>
                <a type="button" href="{{route('invoice.print',$model->invoice_id)}}" class="btn btn-success btn-active-light-success">
                    Iya
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@if($model->invoice_status==1)
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
