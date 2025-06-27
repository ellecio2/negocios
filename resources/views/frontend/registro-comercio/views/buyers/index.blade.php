@extends('frontend.layouts.register')
@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/frontend/register.css') }}">
@endsection
@section('register-type', 'Cuenta')

@section('register-form')
    {{-- formulario con correo --}}
    <form class="form-flex email-form was-validated" id="registerFormClient" method="POST"
          action="{{ route('register.buyer.store') }}">
        @csrf
        <div class="container">
            <div class="form-row col-md-12">
                <div class="row">
                    <div class="col-lg-6" hidden>
                        <div class="form-group mb-4">
                            <label for="add_user_type">Tipo de Cliente</label>
                            <select name="add_user_type" id="add_user_type" class="form-input-edit" required>
                                <option value="B02" selected>Cliente Generico</option>
                                <option value="B01">Empresa o Persona Fisica</option>
                                <option value="B15">Cliente Gubernamental</option>
                            </select>
                            @error('client_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="client_typeError" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-6" id="doc_rnc_input">
                        <div class="form-group mb-4">
                            <label for="cedula_rnc" id="rc">RNC o Cedula</label>
                            <label for="cedula_rnc" id="rn">RNC</label>
                            <input type="number" name="cedula_rnc" id="cedula_rnc"
                                   class="form-input-edit @error('cedula_rnc') is-invalid @enderror">
                            @error('cedula_rnc')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="cedula_rncError" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label for="name_user" id="nameLabel">Nombre y Apellido</label>
                            <input type="text" name="name_user" id="name_user" placeholder="Nombre y Apellido"
                                   class="form-input-edit name_user @error('name_user') is-invalid @enderror"
                                   value="{{ old('name_user') }}" required>
                            @error('name_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span id="nameError" style="color: #dc3545; font-size: 80%;"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-4" id="phoneField">
                            <label for="phone" id="numeroLabel">Número Móvil</label>
                            <input type="tel" name="phone" id="phone" data-user="customer"
                                   placeholder="Ejemplo: +10123456789"
                                   class="form-input-edit phone @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" required>
                            <div id="phoneError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="phoneStatus"></div> <!-- Agrega este elemento para mostrar el mensaje -->
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-4" id="emailField">
                            <label for="email" id="emailLabel">Correo Electrónico</label>
                            <input type="email" name="email" id="email" data-user="customer"
                                   placeholder="Correo Electrónico"
                                   class="form-input-edit email @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                            <div id="emailError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="emailStatus"></div> <!-- Agrega este elemento para mostrar el mensaje -->
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" placeholder="Contraseña"
                                   class="form-input-edit @error('password') is-invalid @enderror" id="inputPassword"
                                   required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="password1Error" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="password_confirmation">Repetir Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   placeholder="Confirmar contraseña"
                                   class="form-input-edit @error('password_confirmation') is-invalid @enderror"
                                   required>
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="passwordError" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <div class="form-check">
                        <input style="width: 18px; height: 18px; margin-right: 10px;"
                               class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                               name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                        <label style="font-size: 1.5rem;" for="terms" style="cursor: pointer;">
                            Estoy de acuerdo con los
                            <a style="font-size: 1.5rem;" href="https://soporte.lapieza.do/?q=terminos" target="_blank">
                                Términos y Condiciones
                            </a>,
                            <a style="font-size: 1.5rem;" href="https://soporte.lapieza.do/?q=devoluciones"
                               target="_blank">
                                Política de Devoluciones
                            </a> &
                            <a style="font-size: 1.5rem;" href="https://soporte.lapieza.do/?q=politicas-privacidad"
                               target="_blank">
                                Política de Privacidad.
                            </a>
                        </label>
                    </div>
                    @if ($errors->has('terms'))
                        <p style="font-size: 80%;" class="text-danger">
                            {{ $errors->first('terms') }}
                        </p>
                    @endif
                </div>
                <div class="form-group mb-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @if ($errors->has('g-recaptcha-response'))
                        <p style="font-size: 80%;" class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                    @endif
                </div>
                <div class="form-group mb-12 d-flex">
                    <button id="submitButton" class="btn btn-primary btn-lg btn-block">
                        <span>Iniciemos</span>
                        <i class="las la-arrow-right"></i>
                    </button>
                </div>
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $("#submitButton").prop("disabled", true);
            $("#doc_rnc_input").hide();
            $("#rc").hide();
            $("#rn").hide();
            const $selectElement = $('#add_user_type');
            if ($selectElement.val()) {
                $selectElement.addClass('success');
            }

            $("#add_user_type").change(function () {
                if ($(this).val() === "B01") {
                    $("#doc_rnc_input").show();
                    $("#rn").hide();
                    $("#rc").show();
                    $('#cedula_rnc').attr('placeholder', 'RNC o Cedula');
                    $('#cedula_rnc').attr('required', true);
                } else if ($(this).val() === "B15") {
                    $("#doc_rnc_input").show();
                    $("#rc").hide();
                    $("#rn").show();
                    $('#cedula_rnc').attr('placeholder', 'RNC');
                    $('#cedula_rnc').attr('required', true);
                } else {
                    $("#doc_rnc_input").hide();
                    $("#rc").hide();
                    $("#rn").hide();
                    $('#cedula_rnc').attr('required', false);
                }
            });
            const phoneunoInput = $("#phone");

            function addPlusOneToPhoneInputs(phoneInput) {
                const inputValue = phoneInput.val().trim();
                if (!inputValue || !inputValue.startsWith("+1")) {
                    phoneInput.val("+1");
                }
            }

            function checkFormValidity() {
                const nameValid = $("#name_user").val().trim().length >= 3 &&
                    /^[a-zA-ZñÑ\s]+$/.test($("#name_user").val().trim());

                const phoneValid = /^\+1\d{10}$/.test($("#phone").val().trim());

                const emailValid = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.(com|do|net|com\.do)$/.test($("#email").val().trim());

                const passwordValid = $("#password").val().trim().length >= 8 &&
                    $("#password").val().trim() === $("#password_confirmation").val().trim();

                const termsAccepted = $("#terms").is(":checked");

                /* const recaptchaValid = grecaptcha.getResponse();*/

                const formIsValid = nameValid && phoneValid && emailValid && passwordValid && termsAccepted;

                if (formIsValid) {
                    $("#submitButton").prop("disabled", false);
                }

            }

            $("#name_user").on("input", function () {
                const input = $(this).val().trim();
                const errorSpan = $("#nameError");

                if (input.length === 0) {
                    errorSpan.text("El campo nombre y apellido es obligatorio.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (!/^[a-zA-ZñÑ\s]+$/.test(input)) {
                    errorSpan.text("Solo se permiten letras y espacios.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (input.length < 3) {
                    errorSpan.text("Debe tener al menos 3 letras.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else {
                    errorSpan.text("");
                    $(this).css({"border": "1px solid #198754", "box-shadow": "0 0 5px #198754"});
                }

                checkFormValidity();
            });

            $("#phone").on("input", function () {
                const input = $(this).val().trim().replace(/\s/g, "");
                const errorSpan = $("#phoneError");

                addPlusOneToPhoneInputs(phoneunoInput);

                if (input.length === 0) {
                    errorSpan.text("El campo número móvil es obligatorio.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (!/^[\d+]+$/.test(input)) {
                    errorSpan.text('Solo se permiten números y el símbolo "+".');
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (!/^\+1\d{10}$/.test(input)) {
                    errorSpan.text("El número móvil debe tener el formato +1 seguido de 10 números.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else {
                    errorSpan.text("");
                    $(this).css({"border": "1px solid #198754", "box-shadow": "0 0 5px #198754"});
                    // Llamada AJAX para verificar el número
                    fetch(phoneCheckUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({
                            phone: input,
                            user_type: $(this).data("user"),
                        }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                $("#phoneStatus").html('<div style="font-size: 80%; color:#dc3545;">El número de teléfono ya ha sido registrado.</div>');
                                $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                            } else {
                                $("#phoneStatus").html('<div class="text-success"></div>');
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }

                checkFormValidity();
            });

            $("#email").on("input", function () {
                const input = $(this).val().trim();
                const errorSpan = $("#emailError");

                if (input.length === 0) {
                    errorSpan.text("El campo correo electrónico es obligatorio.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (!/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.(com|do|net|com\.do)$/.test(input)) {
                    errorSpan.text("Ingrese un correo electrónico válido.");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else {
                    errorSpan.text("");
                    $(this).css({"border": "1px solid #198754", "box-shadow": "0 0 5px #198754"});
                    // Llamada AJAX para verificar el correo
                    fetch(emailCheckUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({
                            email: input,
                            user_type: $(this).data("user"),
                        }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                $("#emailStatus").html('<div style="font-size: 80%; color:#dc3545;">El correo electrónico ya ha sido registrado.</div>');
                                $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                            } else {
                                $("#emailStatus").html('<div class="text-success"></div>');
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }

                checkFormValidity();
            });

            $("#cedula_rnc").on('input', function () {
                const input = $(this).val().trim();
                const errorSpan = $("#cedula_rncError");
                if (input.length === 0) {
                    errorSpan.text("Este campo debe contener mas de 4 dígitos");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (input.length < 4) {
                    errorSpan.text("Este campo debe contener mas de 4 dígitos");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else if (!/^\d+$/.test(input)) {
                    errorSpan.text("Este campo solo admite números");
                    $(this).css({"border": "1px solid #dc3545", "box-shadow": "0 0 5px #dc3545"});
                } else {
                    errorSpan.text("");
                    $(this).css({"border": "1px solid #198754", "box-shadow": "0 0 5px #198754"});
                    fetch("{{ route('verification.rcn_doc.verify') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            rnc: input
                        }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            console.log(data);
                            if (data.state) {
                                $("#name_user").val(data.message[0].valor);
                                $("#name_user").attr('readonly', true);
                                $("#name_user").addClass('success');
                            } else {
                                $("#name_user").val('');
                                $("#name_user").attr('readonly', false);
                                $("#name_user").removeClass('success');
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                        });
                }
                checkFormValidity();
            });

            $("#password, #password_confirmation").on("input", function () {
                const password = $("#password").val().trim();
                const confirmPassword = $("#password_confirmation").val().trim();
                const errorMessage = password.length < 8 ? "La contraseña debe tener al menos 8 caracteres." : (password !== confirmPassword ? "Las contraseñas no coinciden." : "");

                $("#password1Error").text(errorMessage);
                $("#password").css("border", errorMessage ? "1px solid #dc3545" : "1px solid #198754");
                $("#password_confirmation").css("border", password === confirmPassword ? "1px solid #198754" : "1px solid #dc3545");

                checkFormValidity();
            });

            $("#cedula_input").on("input", function () {
                const input = $(this).val().trim();
                const cedulaStatus = $("#cedulaStatus");
                const errorSpan = $("#cedulaError");

                if (!input) {
                    errorSpan.text("El campo Cédula Representante es obligatorio.");
                    $(this).css("border", "1px solid #dc3545").css("box-shadow", "0 0 5px #dc3545");
                    return;
                }

                fetch(cedulaCheckUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({cedula: input, user_type: $("#cedula_input").data("user")}),
                })
                    .then(response => response.json())
                    .then(data => {
                        cedulaStatus.html(data.exists ? '<div style="font-size: 80%; color:#dc3545;">La cédula ya ha sido registrada.</div>' : '');
                        $(this).css("border", data.exists ? "1px solid #dc3545" : "1px solid #198754").css("box-shadow", data.exists ? "0 0 5px #dc3545" : "0 0 5px #198754");
                    })
                    .catch(error => console.error("Error:", error));

                checkFormValidity();
            });

            $("#terms, #password, #password_confirmation").on("input change", checkFormValidity);

            $("#registerFormClient").on("submit", function (e) {
                e.preventDefault();

                $("#submitButton").prop("disabled", true);
                $("#preloader").show();
                const recaptchaResponse = grecaptcha.getResponse();

                if (recaptchaResponse.length === 0) {
                    Swal({
                        type: "error",
                        /*title: "Oops...",*/
                        title: "Por favor, verifica que no eres un robot.",
                        timer: 6000,
                    });
                    $("#preloader").hide();
                    $("#submitButton").prop("disabled", false);
                    return;
                }
                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    success: function (response) {
                        console.log(response, ' response')
                        if (!response.state) {
                            $("#preloader").hide();
                            $("#submitButton").prop("disabled", false);
                            Swal({
                                type: "error",
                                /*title: "Oops...",*/
                                title: response.message,
                                timer: 6000,
                            }).then(() => {
                                grecaptcha.reset();
                            });
                        } else {
                            window.location.href = response.redirect;
                        }
                    },
                });
            });
        });

    </script>
@endsection
