<x-base-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Perkiraan</h3>
            <div class="card-toolbar">
                <a type="button" href="{{ route('account.add') }}"  class="btn btn-sm btn-light-primary">
                    <i class="bi bi-plus-square fs-2"></i>
                    {{ __('Tambah Perkiraan Baru') }}
                </a>
            </div>
        </div>
        <div class="card-body pt-6">
            @include('content.Account.List._table')
        </div>
    </div>
</x-base-layout>