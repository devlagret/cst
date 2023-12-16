<x-base-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Produk</h3>
            <div class="card-toolbar">
                {{-- <a type="button" href="{{ route('product.export') }}" class="btn btn-m btn-light-primary me-2">
                    <i class="bi bi-download fs-2"></i>
                    {{ __('Export Data Client') }}
                </a> --}}
                {{-- <a type="button" href="{{ route('invoice.add') }}" class="btn btn-m btn-light-primary me-1">
                    <i class="bi bi-plus-square fs-2"></i>
                    {{ __('Tambah Invoice Baru') }}
                </a> --}}
                <a href="{{ url()->previous() }}" class="btn btn-light align-self-center">
                    <i class="bi bi-arrow-left fs-2 font-bold"></i>
                    {{ __('Kembali') }}</a>
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

        </div>
    </div>
</x-base-layout>
