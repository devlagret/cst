<x-base-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Invoice</h3>
            <div class="card-toolbar">
                <button type="button" id="print-batch-btn" class="btn btn-m btn-light-primary me-2">
                    <i class="bi bi-printer fs-2"></i>Cetak <span id="batch-count"></span> Nota
                </button>
                <a type="button" href="{{ route('invoice.list-add') }}" class="btn btn-m btn-light-primary me-1">
                    <i class="bi bi-plus-square fs-2"></i>
                    {{ __('Tambah Invoice Baru') }}
                </a>
            </div>
        </div>
        <div class="card-body pt-6">

            <!--begin::Table-->
            {{ $dataTable->table() }}
            <!--end::Table-->

            {{-- Inject Scripts --}}
            @push('scripts')
                {{ $dataTable->scripts() }}
            @endpush
            @section('scripts')
            <script>
                var count = 0;
                var print ={};
                function printAdd(id=null) {
                    if($('#cb-print-batch-'+id).is(':checked')){
                        print[id]=id;
                        count++;
                    }else if(count!=0){
                        delete print[id];
                        count--;
                    }
                    if(count){
                        $('#batch-count').html(count);
                        $('#print-batch-btn').attr('disabled', false);
                        $('#print-batch-btn').removeClass('disabled');
                    }else{
                        $('#batch-count').html('');
                        $('#print-batch-btn').attr('disabled', true);
                        $('#print-batch-btn').addClass('disabled');
                    }
                 
                }
                function cPymt(type,id){ 
                    if(type){
                        $('#payment_bank_view_'+id).show();
                        $('#payment_bank_view_'+id).removeClass('d-none');
                    }else{
                        $('#payment_bank_view_'+id).hide();
                    }
                }
                function calcReturn(id) { 
                    var total = $('#total_amount_'+id).val();
                    var payed = parseInt($('#payed_amount_view_'+id).val());
                    if(payed<0){
                        payed =0;
                    }
                    $('#payed_amount_'+id).val(payed);
                    $('#change_amount_'+id).val((payed-total));
                    $('#change_amount_view_'+id).val(toRp((payed-total)));
                    $('#payed_amount_view_'+id).val(toRp(payed));
                 }
                 function checkPayed(id) {
                    var total = parseInt($('#total_amount_'+id).val());
                    var payed = parseInt($('#payed_amount_'+id).val()||0);
                    if(total>payed){
                        if(payed==0){
                            alert("Kolom dibayar harus diisi");
                            return 0;
                        }
                        // if(confirm("Uang yang dibayarkan masih kurang, Apakah "))
                        alert("Uang dibayar setidaknya sama dengan total tagihan")
                        return 0;
                    }else{
                        $('#form_pay_'+id).submit();
                    }
                 }
                 $(document).ready(function () {
                    if(!$('.cb-batch').length){
                        $('#batch-count').html('');
                        $('#print-batch-btn').attr('disabled', true);
                        $('#print-batch-btn').addClass('disabled');
                    }
                 });
            </script>
            @stop
        </div>
    </div>
</x-base-layout>
