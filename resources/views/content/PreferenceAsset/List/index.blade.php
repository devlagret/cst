<x-base-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Asset</h3>
            <div class="card-toolbar">
                {{-- <a type="button" href="{{ route('product.export') }}" class="btn btn-m btn-light-primary me-2">
                    <i class="bi bi-download fs-2"></i>
                    {{ __('Export Data Client') }}
                </a> --}}
                <a type="button" href="" class="btn btn-m btn-light-primary me-1">
                    <i class="bi bi-plus-square fs-2"></i>
                    {{ __('Tambah Asset Baru') }}
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
                function calcReturn(id) { 
                    var total = $('#total_amount_'+id).val();
                    var payed = parseInt($('#payed_amount_view_'+id).val());
                    if(payed<0){x
                        payed =0;
                    }
                    $('#payed_amount_'+id).val(payed);
                    $('#change_amount_'+id).val((payed-total));
                    $('#change_amount_view_'+id).val(toRp((payed-total)));
                    $('#payed_amount_view_'+id).val(toRp(payed));
                 }
                 function checkPayed(id) {
                    var total = parseInt($('#total_amount_'+id).val());
                    var payed = parseInt($('#payed_amount_view_'+id).val()||0);
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
            </script>
            @stop
        </div>
    </div>
</x-base-layout>
