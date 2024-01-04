<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Daftar Piutang') }}</h3>
            </div>
        </div>

        <div id="kt_deposito_report_view">
            <form id="kt_deposito_report_view_form" class="form" method="POST" action="{{ route('in-report.viewport') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <div class="col-lg-3 fv-row">
                            <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tanggal Mulai') }}</label>
                            <input name="start_date" id="start_date" class="date form-control form-control-solid form-select-lg" placeholder="Pilih tanggal"/>
                        </div>
                        <div class="col-lg-3 fv-row">
                            <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Tanggal Akhir') }}</label>
                            <input name="end_date" id="end_date" class="date form-control form-control-solid form-select-lg" placeholder="Pilih tanggal"/>
                        </div>
                        <div class="col-lg-3 fv-row">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Client') }}</label>
                            <select name="client_id" id="client_id" aria-label="{{ __('Client') }}" data-control="select2" data-placeholder="{{ __('Pilih Client..') }}" data-allow-clear="true" class="form-select form-select-solid form-select-lg">
                                <option value="">{{ __('Pilih Client..') }}</option>
                                @foreach($core_client as $key => $value)
                                    <option data-kt-flag="{{ $key }}" value="{{ $key }}" {{ $key === old('client_id', '' ?? '') ? 'selected' :'' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 fv-row">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Tipe') }}</label>
                            <select name="tipe" id="tipe" aria-label="{{ __('Tipe') }}" data-control="select2" data-placeholder="{{ __('Pilih Tipe..') }}" data-allow-clear="true" class="form-select form-select-solid form-select-lg">
                                <option value="">{{ __('Pilih Tipe..') }}</option>
                                @foreach($core_client_address as $address => $address)
                                    <option data-kt-flag="{{ $address }}" value="{{ $address }}" {{ $address === old('invoice_id', '' ?? '') ? 'selected' :'' }}>{{ $address}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary me-2" id="kt_nominative_deposito_submit" id="view" name="view" value="excel">
                        <i class="bi bi-file-earmark-excel"></i> {{__('Export Excel')}}
                    </button>
                    <button type="submit" class="btn btn-primary" id="kt_deposito_report_submit" id="view" name="view" value="pdf">
                        <i class="bi bi-file-earmark-pdf"></i> {{__('Export PDF')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>