<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    {!! theme()->printHtmlAttributes('html') !!} {{ theme()->printHtmlClasses('html') }}>
{{-- begin::Head --}}
<head>
    <meta charset="utf-8"/>
    <title>{{ ucfirst(theme()->getOption('meta', 'title')) }}</title>
    <meta name="description" content="{{ ucfirst(theme()->getOption('meta', 'description')) }}"/>
    <meta name="keywords" content="{{ theme()->getOption('meta', 'keywords') }}"/>
    <link rel="canonical" href="{{ ucfirst(theme()->getOption('meta', 'canonical')) }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="{{ asset('img/logo/logo-180x180.png')}}"/>
    <link rel="manifest" href="{{asset('manifest.json')}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo/logo-180x180.png')}}" />
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset(theme()->getDemo().'/plugins/custom/datatables/datatables.bundle.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/sass/app.scss')
    <link rel="stylesheet" type="text/css" href="{{ asset('demo2/css/style.bundle.css') }}">
    @vite('resources/js/app.js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- begin::Fonts --}}
    {{ theme()->includeFonts() }}
    {{-- end::Fonts --}}
    @if (theme()->hasOption('assets', 'css'))
    {{-- begin::Global Stylesheets Bundle(used by all pages) --}}
    @foreach (theme()->getOption('assets', 'css') as $file)
        <link href="{{ assetCustom($file) }}" rel="stylesheet" type="text/css"/>
    @endforeach
    {{-- end::Global Stylesheets Bundle --}}
@endif
@vite('resources/sass/rewrite.scss')

@yield('styles')
</head>
{{-- end::Head --}}

{{-- begin::Body --}}
<body {!! theme()->printHtmlAttributes('body') !!} {!! theme()->printHtmlClasses('body') !!} {!! theme()->printCssVariables('body') !!}>
@if (theme()->getOption('layout', 'loader/display') === true)
    {{ theme()->getView('layout/_loader') }}
@endif

@yield('content')


{{-- begin::Javascript --}}
@if (theme()->hasOption('assets', 'js'))
    {{-- begin::Global Javascript Bundle(used by all pages) --}}
    @foreach (theme()->getOption('assets', 'js') as $file)
        <script src="{{ asset(theme()->getDemo() . '/' .$file) }}"></script>
    @endforeach
    {{-- end::Global Javascript Bundle --}}
@endif

@if (theme()->hasOption('page', 'assets/vendors/js'))
    {{-- begin::Page Vendors Javascript(used by this page) --}}
    @foreach (theme()->getOption('page', 'assets/vendors/js') as $file)
        <script src="{{ asset(theme()->getDemo() . '/' .$file) }}"></script>
    @endforeach
    {{-- end::Page Vendors Javascript --}}
@endif

@if (theme()->hasOption('page', 'assets/custom/js'))
    {{-- begin::Page Custom Javascript(used by this page) --}}
    @foreach (theme()->getOption('page', 'assets/custom/js') as $file)
        <script src="{{ asset(theme()->getDemo() . '/' .$file) }}"></script>
    @endforeach
    {{-- end::Page Custom Javascript --}}
@endif
{{-- end::Javascript --}}
<style>
    .toast{
        opacity: 1 !important;
        width: 50%;
        top: 10px;
    }
    th{
        font-weight: bold !important;
        text-align: center !important;
    }
    .show-border tr{
        border-width: 2px !important;
        border-style : solid !important;
    }
    
    input[readonly], textarea[readonly] {
        cursor: not-allowed;
    }

    input[readonly], textarea[readonly] {
        background-color : #e3e4e4 !important; 
    }
    /* #toast-container > .toast {
        background-image: none !important;
    } */
    .select2-selection__rendered{
        color: #5E6278 !important;
        font-weight: 500;
    }
    .table td:first-child {
        text-align: center !important;
    }
</style>
<script>
    @if(Session::has('pesan'))
        toastr.options = {
            positionClass: 'toast-top-center',
        };
        // toastr.{{Session::get('alert')}}("{{Session::get('pesan')}}").css("width","500px");
        toastr.{{Session::get('alert')}}("{{Session::get('pesan')}}");
    @endif
    @if ($errors->any())
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-center",
        "timeOut": "10000",
        "progressBar": true
        };
        @foreach ($errors->all() as $error)
        toastr.error("{{$error}}");
        @endforeach
    @endif
    $(".date").daterangepicker({
        autoApply: true,
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format("YYYY"),12),
        locale: {
            format: 'DD-MM-YYYY',
        }
    });

    function toRp(number) {
        var number = number.toString(), 
        rupiah = number.split('.')[0], 
        cents = (number.split('.')[1] || '') +'00';
        rupiah = rupiah.split('').reverse().join('')
            .replace(/(\d{3}(?!$))/g, '$1,')
            .split('').reverse().join('');
        return rupiah + '.' + cents.slice(0, 2);
    }

    var prevScrollpos = window.pageYOffset;
    if (window.innerHeight == document.body.scrollHeight) {
        if (document.getElementsByTagName("BODY")[0].hasAttribute('data-kt-scrollbottom') === true) {
            document.getElementsByTagName("BODY")[0].removeAttribute('data-kt-scrollbottom');
        }
    } else { 
        if (document.getElementsByTagName("BODY")[0].hasAttribute('data-kt-scrollbottom') === false) {
            document.getElementsByTagName("BODY")[0].setAttribute('data-kt-scrollbottom', 'on');
        }
    }

    window.onscroll = function() {
        if (prevScrollpos = window.pageYOffset) {
            if (document.getElementsByTagName("BODY")[0].hasAttribute('data-kt-scrollbottom') === true) {
                document.getElementsByTagName("BODY")[0].removeAttribute('data-kt-scrollbottom');
            }
        } else { 
            if (document.getElementsByTagName("BODY")[0].hasAttribute('data-kt-scrollbottom') === false) {
                document.getElementsByTagName("BODY")[0].setAttribute('data-kt-scrollbottom', 'on');
            }
        }
    }

    $(document).ready(function(){
        $('#kt_scrollbottom').click(function(){
            $('html, body').animate({
                scrollTop: document.body.scrollHeight
            }, 900, 'linear');
        })
        $('.form-select').click(function(){
            $('.select2-search__field').each(function (
                key,
                value,
            ){
                value.focus();
            })
        })
        $('.datatable').DataTable({
                    responsive: true,
                    "aLengthMenu": [
                        [5, 15, 20, -1],
                        [5, 15, 20, "All"] // change per page values here
                    ],
                    "iDisplayLength": 5,
                    "dom":'frtip',

        });
        $(document.querySelectorAll('[type=reset]')).click(function(){
            // location.reload();
            // $("#account_type_id").empty();
            // $("#account_type_id").select2("val", "");
            // if ($('#account_type_id').hasClass("select2-hidden-accessible")) {
            //     console.log('ada');
            // }else{
            //     console.log('tidak ada');
            // }
            // document.getElementsByClassName("select2-selection__clear").click();
            // var el = document.getElementsByClassName('select2-selection__clear');
            // console.log(el);
            // console.log(document.getElementById('account_type_id').value);
            // el[0].click();
            // console.log(document.getElementById('account_type_id').value);
            // $('.select2-selection__clear').each(function (
            //     key,
            //     value,
            // ){
            //     console.log(document.getElementById('account_type_id').value);
            //     value.click();
            // })
        })
    }); 
</script>
{{-- @if(session('pesan'))
    @dd(session('pesan'))
@endif --}}
<script type="text/javascript" charset="utf8" src="{{ asset(theme()->getDemo().'/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@yield('scripts')
@yield('bladeScripts')
@stack('scripts')
</body>
{{-- end::Body --}}
</html>
