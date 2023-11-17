@section('scripts')
<script>
const form = document.getElementById('kt_member_add_view_form');

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'member_name': {
                validators: {
                    notEmpty: {
                        message: 'Nama Lengkap harus diisi'
                    }
                }
            },
            'member_gender': {
                validators: {
                    notEmpty: {
                        message: 'Jenis Kelamin harus diisi'
                    }
                }
            },
            'member_place_of_birth': {
                validators: {
                    notEmpty: {
                        message: 'Tempat Lahir harus diisi'
                    }
                }
            },
            'member_date_of_birth': {
                validators: {
                    notEmpty: {
                        message: 'Tanggal Lahir harus diisi'
                    }
                }
            },
            'member_address': {
                validators: {
                    notEmpty: {
                        message: 'Alamat Sesuai KTP harus diisi'
                    }
                }
            },
            'member_address_now': {
                validators: {
                    notEmpty: {
                        message: 'Alamat Tinggal Sekarang harus diisi'
                    }
                }
            },
            'member_mother': {
                validators: {
                    notEmpty: {
                        message: 'Nama Ibu Kandung harus diisi'
                    }
                }
            },
            'member_principal_savings': {
                validators: {
                    notEmpty: {
                        message: 'Simpanan Pokok harus diisi'
                    }
                }
            },
            'province_id': {
                validators: {
                    notEmpty: {
                        message: 'Provinsi harus diisi'
                    }
                }
            },
            'city_id': {
                validators: {
                    notEmpty: {
                        message: 'Kabupaten harus diisi'
                    }
                }
            },
            'kecamatan_id': {
                validators: {
                    notEmpty: {
                        message: 'Kecamatan harus diisi'
                    }
                }
            },
            'kelurahan_id': {
                validators: {
                    notEmpty: {
                        message: 'Kelurahan harus diisi'
                    }
                }
            },
            'member_last_education': {
                validators: {
                    notEmpty: {
                        message: 'Pendidikan terakhir harus diisi'
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
function function_elements_add(name, value){
    $.ajax({
        type: "POST",
        url : "{{route('client.element-add')}}",
        data : {'name':name,'value': value,'_token': '{{csrf_token()}}'
    },
        success: function(msg){}
    });
}
//==============================END VALIDATION FORM ADD MEMBER ===============================\\
</script>
@endsection


<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Form Tambah Anggota') }}</h3>
            </div>

            <a href="{{url()->previous()}}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>

        <div id="kt_client_add_view">
            <form id="kt_client_add_view_form" class="form" method="POST" action="{{ route('client.process-add') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <div class="col-lg-6">
                            <div class="row mb-6">
                                <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Anggota') }}</b>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Nama Lengkap') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid" placeholder="Masukan Nama Client" value="{{ old('name', $sessiondata['name'] ?? '') }}" autocomplete="off" onchange="function_elements_add(this.name, this.value)"/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Alamat') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <textarea id="address" name="address" class="form-control form-control form-control-solid" data-kt-autosize="true" placeholder="Alamat Lengkap" onchange="function_elements_add(this.name, this.value)">{{ old('address', $sessiondata['address'] ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('Contact Person') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="contact_person" id="contact_person" class="form-control form-control-lg form-control-solid" placeholder="Nama CP" value="{{ old('contact_person', $sessiondata['contact_person'] ?? '') }}" autocomplete="off" onchange="function_elements_add(this.name, this.value)"/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">{{ __('No. Hp') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="phone" id="phone" class="form-control form-control-solid form-select-lg" placeholder="Nomor Handphone" value="{{ old('phone', $sessiondata['phone'] ?? '') }}" onchange="function_elements_add(this.name, this.value)"/>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Email') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input name="email" id="email" class="form-control form-control-solid form-select-lg" placeholder="Masukan Email" value="{{ old('email', $sessiondata['email'] ?? '') }}" onchange="function_elements_add(this.name, this.value)"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-6">
                                <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Member') }}</b>
                            </div>
                            <div class="row mb-6 company">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nama') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="member_company_name" class="form-control form-control-lg form-control-solid" placeholder="Nama" value="{{ old('member_company_name', $sessiondata['member_company_name'] ?? '') }}" autocomplete="off" onchange="function_elements_add(this.name, this.value)"/>
                                </div>
                            </div>
                            <div class="row mb-6 company">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Jabatan/PIC') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="member_company_job_title" class="form-control form-control-lg form-control-solid" placeholder="Jabatan/PIC" value="{{ old('member_company_job_title', $sessiondata['member_company_job_title'] ?? '') }}" autocomplete="off" onchange="function_elements_add(this.name, this.value)" />
                                </div>
                            </div> 
                            <div class="row mb-6 company">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('No. Hp') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="member_company_specialities" class="form-control form-control-lg form-control-solid" placeholder="Nomer Handphone" value="{{ old('member_company_specialities', $sessiondata['member_company_specialities'] ?? '') }}" autocomplete="off" onchange="function_elements_add(this.name, this.value)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle border-3 rounded px-1 border-solid border-gray-500">
                                    <thead class="font-bold text-xl2">
                                        <th>Column 1</th>
                                        <th>Column 2</th>
                                        <th>Column 3</th>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr>
                                            <td scope="row">Item</td>
                                            <td>Item</td>
                                            <td>Item</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Item</td>
                                            <td>Item</td>
                                            <td>Item</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        
                                    </tfoot>
                            </table>
                        </div>
                        
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>

                    <button type="submit" class="btn btn-primary" id="kt_member_add_submit">
                        @include('partials.general._button-indicator', ['label' => __('Simpan')])
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>

