@section('scripts')
<script>
const form = document.getElementById('kt_asset_edit_view_form');

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Nama harus diisi'
                    }
                }
            },
            'buy_date': {
                validators: {
                    notEmpty: {
                        message: 'Tanggal Beli harus diisi'
                    }
                }
            },
            'price': {
                validators: {
                    notEmpty: {
                        message: 'Harga Beli harus diisi'
                    }
                }
            },
            'acquisition_amount': {
                validators: {
                    notEmpty: {
                        message: 'Nilai Perolehan harus diisi'
                    }
                }
            },
            'estimated_age': {
                validators: {
                    notEmpty: {
                        message: 'Status Perkiraan harus diisi'
                    }
                }
            },
            'residual_amount': {
                validators: {
                    notEmpty: {
                        message: 'Taksiran Usia harus diisi'
                    }
                }
            },
            'remark': {
                validators: {
                    notEmpty: {
                        message: 'Nilai Residu harus diisi'
                    }
                }
            },
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

const submitButton = document.getElementById('kt_bank_account_edit_submit');
submitButton.addEventListener('click', function (e) {
    e.preventDefault();

    if (validator) {
        validator.validate().then(function (status) {
            if (status == 'Valid') {
                submitButton.setAttribute('data-kt-indicator', 'on');

                submitButton.disabled = true;

                setTimeout(function () {
                    submitButton.removeAttribute('data-kt-indicator');

                    // submitButton.disabled = false;

                    // Swal.fire({
                    //     text: "Form has been successfully submitted!",
                    //     icon: "success",
                    //     buttonsStyling: false,
                    //     confirmButtonText: "Ok, got it!",
                    //     customClass: {
                    //         confirmButton: "btn btn-primary"
                    //     }
                    // });

                    form.submit(); // Submit form
                }, 2000);
            }
        });
    }
});
</script>
@endsection

<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Form Ubah Data Asset') }}</h3>
            </div>

            <a href="" class="btn btn-light align-self-center">
                {!! theme()->getSvgIcon("icons/duotune/arrows/arr079.svg", "svg-icon-4 me-1") !!}
                {{ __('Kembali') }}</a>
        </div>

        <div id="kt_user_edit_view">
            <form id="kt_asset_edit_view_form" class="form" method="POST" action="{{ route('as-report.process-edit') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Nama') }}</label>
                        <div class="col-lg-8 fv-row">
                            <input type="hidden" name="asset_id" class="form-control form-control-lg form-control-solid" value="{{ old('asset_id', $add_asset->asset_id ?? '') }}"/>
                            <input type="text" name="name" class="form-control form-control-lg form-control-solid" placeholder="Nama" value="{{ old('name', $add_asset->name ?? '') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Harga Beli') }}</label>
                        <div class="col-lg-8 fv-row">
                            <input type="number" name="price" class="form-control form-control-lg form-control-solid" placeholder="Harga Beli" value="{{ old('price', $add_asset->price ?? '') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Nilai Perolehan') }}</label>
                        <div class="col-lg-8 fv-row">
                            <input type="number" name="acquisition_amount" class="form-control form-control-lg form-control-solid" placeholder="Nilai Perolehan" value="{{ old('acquisition_amount', $add_asset->acquisition_amount ?? '') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Taksiran Usia') }}</label>
                        <div class="col-lg-8 fv-row">
                            <input type="number" name="estimated_age" class="form-control form-control-lg form-control-solid" placeholder="Taksiran Usia" value="{{ old('estimated_age', $add_asset->estimated_age ?? '') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Nilai Residu') }}</label>
                        <div class="col-lg-8 fv-row">
                            <input type="number" name="residual_amount" class="form-control form-control-lg form-control-solid" placeholder="Nilai Residu" value="{{ old('residual_amount', $add_asset->residual_amount ?? '') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Keterangan') }}</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="remark" class="form-control form-control-lg form-control-solid" placeholder="Keterangan" value="{{ old('remark', $add_asset->remark ?? '') }}" autocomplete="off"/>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>
    
                    <button type="submit" class="btn btn-primary" id="kt_bank_account_edit_submit">
                        @include('partials.general._button-indicator', ['label' => __('Simpan')])
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>

