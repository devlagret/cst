@section('styles')
    <style type="text/css">
        table,
        tr,
        td {
            border: 1px solid !important;
            border-color: #B5B5C3 !important;
            border-bottom-color: #B5B5C3 !important;
        }
        td {
            border: 1px solid !important;
            border-color: #B5B5C3 !important;
            border-bottom-color: #B5B5C3 !important;
        }
        tr {
            border-color: #B5B5C3 !important;
            border-bottom-color: #B5B5C3 !important;
        }
        table {
            border-radius: 0.25rem !important;
        }
        .table tr:first-child,
        .table th:first-child,
        .table td:first-child {
            padding: 0.75rem !important;
        }
        .table tr:last-child,
        .table th:last-child,
        .table td:last-child {
            padding: 0.75rem !important;
        }
    </style>
@endsection
<x-base-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Form Detail Client') }}</h3>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-light align-self-center">
                <i class="bi bi-arrow-left fs-2 font-bold"></i>
                {{ __('Kembali') }}</a>
        </div>
        <div id="kt_client_add_view">
            <form id="kt_client_add_view_form" class="form" method="POST" action="{{ route('client.process-edit') }}"
                enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body border-top p-9">
                    <div class="row">
                        <div class="row mb-5">
                            <b class="col-lg-12 fw-bold fs-3 text-center text-primary">{{ __('Data Anggota') }}</b>
                        </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Nama Lengkap') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <input type="text" name="name" id="name"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Masukan Nama Client"
                                        value="{{ old('name', $data->name??'-') }}" autocomplete="off"
                                        readonly  />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Contact Person') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <input type="text" name="contact_person" id="contact_person"
                                        class="form-control form-control-lg form-control-solid" placeholder="Nama CP"
                                        value="{{ old('contact_person', $data->contact_person??'-') }}"
                                        autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('No. Hp') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <input name="phone" id="phone"
                                        class="form-control form-control-solid form-select-lg"
                                        placeholder="Nomor Handphone"
                                        value="{{ old('phone', $data->phone??'-') }}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-2 col-form-label fw-bold fs-6">{{ __('Email') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <input name="email" id="email"
                                        class="form-control form-control-solid form-select-lg"
                                        placeholder="Masukan Email"
                                        value="{{ old('email', $data->email??'-') }}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label
                                    class="col-lg-2 col-form-label fw-bold fs-6 required">{{ __('Alamat') }}</label>
                                <div class="col-lg-10 fv-row">
                                    <textarea id="address" name="address" class="form-control form-control form-control-solid" data-kt-autosize="true"
                                        placeholder="Alamat Lengkap" readonly>{{ old('address', $data->address??'-') }}</textarea>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table
                                id="table-member"class="table table-bordered table-auto border-collapse table-striped table-hover align-middle rounded datatable">
                                <thead class="font-bold text-xl2">
                                    <th class="w-0.4">No</th>
                                    <th class="w-1/2">Nama</th>
                                    <th>Jabatan/PIC</th>
                                    <th>No Handphone</th>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody class="table-group-divider" id="table-member-content">
                                    @foreach ($data->member as $key => $val)
                                        <tr class="client-member" id="cm-{{ $key }}"
                                            data-id="{{ $key }}">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $val['name'] }}</td>
                                            <td>{{ $val['position']??'-' }}</td>
                                            <td>{{ $val['phone']??'-' }}</td>
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
                    <button type="reset"
                        class="btn btn-white btn-active-light-primary me-2">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-primary" id="kt_member_add_submit">
                        @include('partials.general._button-indicator', ['label' => __('Simpan')])
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>
