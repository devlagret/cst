    
  
<style>
    body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
    } 
    
    main {
        flex: 1 0 auto;
    }
    
    h1.title,
    .footer-copyright a {
        font-family: 'Architects Daughter', cursive;
        text-transform: uppercase;
        font-weight: 900;
    }
  
    /* start welcome animation */
  
    body.welcome {
        background: #ffffff;
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
    }
  
    .welcome .splash {
        height: 0px;
        padding: 0px;
        border: 130em solid #019ef7;
        position: fixed;
        left: 50%;
        top: 100%;
        display: block;
        box-sizing: initial;
        overflow: hidden;
    
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: puff 0.5s 1.8s cubic-bezier(0.55, 0.055, 0.675, 0.19) forwards, borderRadius 0.2s 2.3s linear forwards;
    }
  
    .welcome #welcome {
        background: #ffffff ;
        width: 56px;
        height: 56px;
        position: absolute;
        left: 50%;
        top: 50%;
        overflow: hidden;
        opacity: 0;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        animation: init 0.5s 0.2s cubic-bezier(0.55, 0.055, 0.675, 0.19) forwards, moveDown 1s 0.8s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards, moveUp 1s 1.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards, materia 0.5s 2.7s cubic-bezier(0.86, 0, 0.07, 1) forwards;
    }
     
    /* moveIn */
    .welcome header,
    .welcome .form {
        opacity: 0;
        animation: moveIn 2s 2.67s ease forwards;
    }
  
    @keyframes init {
        0% {
            width: 0px;
            height: 0px;
        }
        100% {
            width: 56px;
            height: 56px;
            margin-top: 0px;
            opacity: 1;
        }
    }
  
    @keyframes puff {
        0% {
            top: 100%;
            height: 0px;
            padding: 0px;
        }
        100% {
            top: 50%;
            height: 100%;
            padding: 0px 100%;
        }
    }
  
    @keyframes borderRadius {
        0% {
            border-radius: 50%;
        }
        100% {
            border-radius: 0px;
        }
    }
  
    @keyframes moveDown {
        0% {
            top: 50%;
        }
        50% {
            top: 40%;
        }
        100% {
            top: 100%;
        }
    }
  
    @keyframes moveUp {
        0% {
            background: #ffffff;
            top: 100%;
        }
        50% {
            top: 40%;
        }
        100% {
            top: 50%;
            background: #019ef7;
        }
    }
  
    @keyframes materia {
        0% {
            opacity: 0.3;
            height: 0%;
            width: 0%;
        }
        50% {
            opacity: 0.1;
        }
        100% {
            background: #ffffff;
            opacity: 0;
            height: 30%;
            width: 20%;
        }
    }
  
    @keyframes moveIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
  
    @keyframes hide {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    } 
</style>
@section('scripts')
<script>
    "use strict";

// Class definition
var KTSigninGeneral = function () {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'username': {
                        validators: {
                            notEmpty: {
                                message: 'Username is required'
                            },
                            emailAddress: {
                                message: 'The value is not a valid email address'
                            }
                        }
                    },
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            },
                            callback: {
                                message: 'Please enter valid password',
                            }
                        }
                    }
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

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status === 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Simulate ajax request
                    axios.post(submitButton.closest('form').getAttribute('action'), new FormData(form))
                        .then(function (response) {
                            // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            Swal.fire({
                                text: "You have successfully logged in!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    form.querySelector('[name="email"]').value = "";
                                    form.querySelector('[name="password"]').value = "";
                                    window.location.reload();
                                }
                            });
                        })
                        .catch(function (error) {
                            let dataMessage = error.response.data.message;
                            let dataErrors = error.response.data.errors;
                            console.log(error);
                            for (const errorsKey in dataErrors) {
                                if (!dataErrors.hasOwnProperty(errorsKey)) continue;
                                dataMessage += "\r\n" + dataErrors[errorsKey];
                            }

                            if (error.response) {
                            console.log(dataMessage);
                            console.log(dataErrors);
                                Swal.fire({
                                    text: dataMessage,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        })
                        .then(function () {
                            // always executed
                            // Hide loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;
                        });
                // } else {
                //     // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                //     Swal.fire({
                //         text: "Sorry, looks like there are some errors detected, please try again.",
                //         icon: "error",
                //         buttonsStyling: false,
                //         confirmButtonText: "Ok, got it!",
                //         customClass: {
                //             confirmButton: "btn btn-primary"
                //         }
                //     });
                // }
            });
        });
    }

    // Public functions
    return {
        // Initialization
        init: function () {
            form = document.querySelector('#kt_sign_in_form');
            submitButton = document.querySelector('#kt_sign_in_submit');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSigninGeneral.init();
});

</script>
@stop
<body class="welcome">
    <span id="splash-overlay" class="splash"></span>
    <span id="welcome" class="z-depth-4"></span>

    <x-auth-layout>
        <form method="POST" action="{{ theme()->getPageUrl('login') }}" class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
        @csrf
            <div class="text-center my-10">
                <h1 class="text-4xl font-bold mb-3">
                    {{ __('Ciptasolutindo') }}
                </h1>
            </div>
            <div class="fv-row mb-10">
                <label class="form-label fs-6 fw-bolder text-dark">{{ __('Username') }}</label>
                <input class="form-control form-control-lg form-control-solid" type="username" name="username" autocomplete="off" value="{{ old('username', 'administrator') }}" required autofocus/>
            </div>
            <div class="fv-row mb-10">
                <div class="d-flex flex-stack mb-2">
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Sandi') }}</label>
                    @if (Route::has('password.request'))
                        <a href="{{ theme()->getPageUrl('password.request') }}" class="link-primary fs-6 fw-bolder">
                            {{ __('Lupa Sandi ?') }}
                        </a>
                    @endif
                </div>
                <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" value="{{old('password')}}" required/>
            </div>
            <div class="text-center">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                    @include('partials.general._button-indicator', ['label' => __('Masuk')])
                </button>
            </div>
        </form>
    </x-auth-layout>
  
    <footer class="page-footer deep-purple darken-3">
    </footer>
</body>
