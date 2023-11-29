@section('scripts')
    <script>
        const form = document.getElementById('kt_product_type_form');
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Nama Tipe harus diisi'
                            }
                        }
                    },
                    'code': {
                        validators: {
                            notEmpty: {
                                message: 'Kode harus diisi'
                            }
                        }
                    },
                    'account_id': {
                        validators: {
                            notEmpty: {
                                message: 'No. Perkiraan harus diisi'
                            }
                        }
                    },
                },
                plugins: {
                    kt_product_type_submit: new FormValidation.plugins.SubmitButton(),
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
        function function_elements_add(name, value) {
            $.ajax({
                type: "POST",
                url: "{{ route('product-type.element-add') }}",
                data: {
                    'name': name,
                    'value': value,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(msg) {}
            });
        }
        //==============================END VALIDATION FORM ADD MEMBER ===============================\\
    </script>
@endsection
<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Form Tambah Tipe Produk') }}</h3>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>
        <div id="kt_product_type_view">
            <form id="kt_product_type_form" class="form" method="POST" action="{{ route('product-type.process-edit') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        {{-- @dump($sessiondata) --}}
                            <div class="row mb-6">
                                <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Tipe Produk') }}</b>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Nama') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <input type="text" name="name" id="name"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Masukan Nama Tipe Produk"
                                        value="{{ old('name', $sessiondata['name'] ?? $data->name) }}" autocomplete="off"
                                        onchange="function_elements_add(this.name, this.value)" />
                                    <input type="hidden" name="product_type_id" id="product_type_id"
                                    value="{{ $data->product_type_id }}" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Kode') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <input type="text" id="code" name="code" class="form-control form-control form-control-solid" data-kt-autosize="true"
                                        placeholder="Masukan Kode Tipe Produk" onchange="function_elements_add(this.name, this.value)" value="{{ old('code', $sessiondata['code'] ?? $data->code) }}"></input>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('No. perkiraan') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <select name="account_id" id="account_id" data-control="select2" aria-label="Pilih No.Perkiraan" data-placeholder="{{ __('Pilih No.Perkiraan') }}" data-allow-clear="true" class="form-select form-select-solid form-select-lg" onchange="function_elements_add(this.name, this.value)">
                                        <option value="">{{ __('Pilih') }}</option>
                                        @foreach($account as $key => $value)
                                            <option data-kt-flag="{{ $key }}" value="{{ $key }}" {{ $key == old('account_id', $session['account_id'] ?? $data->account_id) ? 'selected' :'' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                {{-- {!! html()->select('account_id', $account,0)->attributes(['data-control'=>"select2", 'aria-label'=>"Pilih No.Perkiraan", 'data-placeholder'=>"Pilih No. Perkiraan",'autocomplete'=>'off', 'data-allow-clear'=>"true",'onchange'=>"function_elements_add(this.name, this.value)" ])->class(['form-select form-select-solid form-select-lg']) !!} --}}
                                </div>
                            </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset"
                        class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-primary" id="kt_product_type_submit">
                        @include('partials.general._button-indicator', ['label' => __('Simpan')])
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>
