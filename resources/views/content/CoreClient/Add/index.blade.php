@section('scripts')
<script>
const form = document.getElementById('kt_client_add_view_form');

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Nama Lengkap harus diisi'
                    }
                }
            },
            'address': {
                validators: {
                    notEmpty: {
                        message: 'Alamat harus diisi'
                    }
                }
            },
            'contact_person': {
                validators: {
                    notEmpty: {
                        message: 'Contact Person harus diisi'
                    }
                }
            },
            'phone': {
                validators: {
                    notEmpty: {
                        message: 'Nomer Handphone harus diisi'
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
$(document).ready(function () {
    $('#add-member').click(function (e) { 
        e.preventDefault();
        if ($('#member_name').val()=='') {
            toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": true,
            "showDuration": "300",
            "timeOut": "10000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            }
            toastr["error"]("Harap Masukan Nama Member", "Perhatian")
        }else{
            addMember();
        }
    });
});
function function_elements_add(name, value){
    $.ajax({
        type: "POST",
        url : "{{route('client.element-add')}}",
        data : {'name':name,'value': value,'_token': '{{csrf_token()}}'
    },
        success: function(msg){}
    });
}
function addMember(){
    var length = $('.client-member').length+1;
    var name = $('#member_name').val();
    var position = $('#member_position').val();
    var phone = $('#member_phone').val();
    $.ajax({
        type: "POST",
        url : "{{route('client.add-member')}}",
        data : {'length':length,'name':name,'position': position,'phone': phone,'_token': '{{csrf_token()}}',
    },
        success: function(msg){
            console.log(msg);
            if(length==0){
                $('#table-member').html(msg);
            }else{
                $('#table-member').append(msg);
            }
        }
    });
}
function editMember(name, value){
    $.ajax({
        type: "POST",
        url : "{{route('client.edit-member')}}",
        data : {'name':name,'value': value,'_token': '{{csrf_token()}}'
    },
        success: function(msg){}
    });
}
function deleteMember(name, value){
    $.ajax({
        type: "POST",
        url : "{{route('client.delete-member')}}",
        data : {'name':name,'value': value,'_token': '{{csrf_token()}}'
    },
        success: function(msg){}
    });
}
//==============================END VALIDATION FORM ADD MEMBER ===============================\\
</script>
@endsection
@section('styles')
<style type="text/css">
 table,tr,td {
  border: 1px solid !important;
  border-color: #B5B5C3 !important;
  border-bottom-color: #B5B5C3 !important;
}
td {
  border: 1px solid !important;
  border-color: #B5B5C3 !important;
  border-bottom-color: #B5B5C3 !important;
}
tr{
    border-color: #B5B5C3 !important;
  border-bottom-color: #B5B5C3 !important;
}
table{
    border-radius: 0.25rem !important;
}
.table tr:first-child, .table th:first-child, .table td:first-child {
  padding: 0.75rem !important;
}
.table tr:last-child, .table th:last-child, .table td:last-child {
    padding: 0.75rem !important;
}
</style>
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
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Nama') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="member_name" id="member_name" class="form-control form-control-lg form-control-solid" placeholder="Nama" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Jabatan/PIC') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="member_position" id="member_position" class="form-control form-control-lg form-control-solid" placeholder="Jabatan/PIC" />
                                </div>
                            </div> 
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('No. Hp') }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="member_phone" id="member_phone" class="form-control form-control-lg form-control-solid" placeholder="Nomer Handphone" />
                                </div>
                            </div>
                            <div class="row mb-6 justify-content-end">
                                <div class="col-auto text-right fv-row">
                                    <button class="btn btn-primary" type="button" id="add-member">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="table-member"class="table table-bordered table-auto border-collapse table-striped table-hover align-middle rounded datatable">
                                    <thead class="font-bold text-xl2">
                                        <th class="w-0.4">No</th>
                                        <th class="w-1/2">Nama</th>
                                        <th>Jabatan/PIC</th>
                                        <th>No Handphone</th>
                                        <th class="w-0.2">Aksi</th>
                                    </thead>
                                    @php
                                        $no = 1;
                                    @endphp
                                    <tbody class="table-group-divider" id="table-member">
                                        @foreach ($member as $key=>$val)
                                        <tr class="client-member" id="cm-{{$key}}" data-id="{{$key}}">
                                            <td>{{$no++}}</td>
                                            <td>{{$val['name']}}</td>
                                            <td>{{$val['position']}}</td>
                                            <td>{{$val['phone']}}</td>
                                            <td class="text-center"><button class="btn btn-sm btn-danger" onclick="deleteMember({{$key}})" type="button">Hapus</button></td>
                                        </tr>
                                        @endforeach
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

