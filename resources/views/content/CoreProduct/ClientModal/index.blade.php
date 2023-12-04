@extends('base.base_modal')
@section('content')
    <!--begin::Table-->
    {{ $dataTable->table() }}
    <!--end::Table-->

    {{-- Inject Scripts --}}
    @push('dt-script')
        {{ $dataTable->scripts() }}
    @endpush
@endsection
