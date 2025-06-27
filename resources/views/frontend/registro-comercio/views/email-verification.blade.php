<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Pieza.DO | Registro</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">
    <!-- External Css -->
    <link rel="stylesheet"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/owl.carousel.min.css') }}"/>
    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/main.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/theme-1.css') }}">
    <link rel="stylesheet"
          href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet"
          href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/favicon.png') }}">
    <link rel="apple-touch-icon"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-114x114.png') }}">
</head>
<body>
<div class="ugf-wrapper flat-grey-bg">
    <div class="ugf-content-block">
        <div class="container-md">
            <div class="row">
                <div class="col-lg-7 offset-lg-5 p-sm-0" id="informative-section">
                    <div class="ugf-content pt340">
                        <h3 style="color: black;">Verificación <br>de <br>Correo Electrónico</h3>
                        @if (!is_null($user) && !$user->email_verified_at)
                            <p style="font-size: 13px;">Enviamos un correo electrónico a
                                <strong>
                                    <a href="#" class="__cf_email__" data-cfemail="7e131f17123e1b061f130e121b501d1113"
                                       id="actual-email">{{ $user->email }}</a>
                                </strong>
                                con un enlace de verificación, dirigete a tu correo electrónico<br> y continua el
                                proceso.
                            </p>
                        @else
                            <div class="alert alert-success" role="alert">
                                Correo verificado correctamente, ahora puedes cerrar esta pestaña
                            </div>
                            {{--@if (auth()->check())
                                @if ($user->user_type == 'customer')
                                    <script>
                                        setTimeout(function () {
                                            window.location.href = "{{ route('dashboard') }}";
                                        }, 1000);
                                    </script>
                                @elseif($user->user_type == 'seller')
                                    <script>
                                        setTimeout(function () {
                                            window.location.href = "{{ route('seller.dashboard') }}";
                                        }, 1000);
                                    </script>
                                @endif
                            @else
                                <script>
                                    setTimeout(function () {
                                        window.location.href = "{{ route('home') }}";
                                    }, 1000);
                                </script>
                            @endif--}}
                        @endif
                        <div class="d-flex align-items-center justify-content-around">
                            <form id="reenviarForm" action="{{ route('reenviarCodigo.codigo') }}" method="POST">
                                @csrf
                                <p class="resend-code" style="font-size: 13px;">¿No recibiste un Correo?
                                    <button id="reenviarBtn" type="button" class="link-button"
                                            style="font-weight: bold; font-size: 12px;">
                                        Reenviar Correo
                                    </button>
                                </p>
                            </form>
                            <form action="#" method="get">
                                <p class="resend-code" style="font-size: 13px;">¿No es tu Correo?
                                    <button class="link-button" id="change-email" disabled
                                            style="font-weight: bold; font-size: 12px;">Cámbialo Aqui
                                    </button>
                                </p>
                            </form>
                        </div>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7 offset-lg-5 p-sm-0 d-none" id="resend-mail-section">
                    <div class="ugf-content pt340">
                        <h2>Actualización de correo eléctronico</h2>
                        <p>Ingresa tu nuevo correo electronico de registro, te enviaremos un nuevo correo de
                            verificacion</p>

                        <div class="form-group my-4 w-50 m-auto" id="phoneField">
                            <input type="email" name="email" id="email" placeholder="tucorreo@correo.com"
                                   class="form-control p-2 fs-3 phone @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" data-user="{{ auth()->user()->user_type }}" required>
                            <div id="emailError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="emailStatus"></div>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex w-75 align-items-center justify-content-around m-auto">
                            <button class="btn btn-primary bg-primary d-block w-25 p-3 fs-5 fw-bold my-3" disabled
                                    id="save-email">
                                Enviar
                            </button>
                            <button class="btn btn-dangers bg-danger d-block w-25 p-3 fs-5 fw-bold my-5 text-white"
                                    id="cancel-save-email">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="alternet-access">
            <p>
                <!-- <a href="{{ route('logout') }}">Cerrar sesión</a> -->
            </p>
        </div>
    </div>
    <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-2 sidebar-steps">
        <div class="steps">
            <div class="step">
                <span>1</span>
                <p>Tipo de Cuenta</p>
            </div>
            <div class="step ">
                <span>2</span>
                <p>Verificación de Inicio</p>
            </div>
            <div class="step step-onprocess">
                <span>3</span>
                <p>Confirmación de Datos</p>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->

<script>
    const url = "{{ config('app.url') }}";
    const phoneCheckUrl = `${url}/phone/check?user_type=`;
    const emailCheckUrl = "{{ route('email.check') }}";
    const email_user = "{{ $user->email }}";
</script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
{{-- <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> --}}
{{-- <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/jquery.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>
<script>
    $(document).ready(function () {
        const changeEmailButton = document.querySelector('#change-email');
        const email = document.getElementById('email');

        const submitResaveEmailButton = document.getElementById('save-email');
        const cancelResaveEmailButton = document.getElementById('cancel-save-email');
        const actualEmailSpan = document.getElementById('actual-email');

        const informativeSection = document.getElementById('informative-section');
        const resendMailSection = document.getElementById('resend-mail-section');

        changeEmailButton.disabled = false;

        cancelResaveEmailButton.addEventListener('click', toggleSections);
        changeEmailButton.addEventListener('click', toggleSections);

        $("#reenviarBtn").click(function () {
            $(this).prop("disabled", true);
            $(this).text("Espere un momento, enviando mensaje...");
            $("#reenviarForm").submit();
        });

        $('#email').on('input', function () {
            const input = $(this).val().trim();
            const errorSpan = $('#emailError');
            const emailInput = $(this); // Almacenar una referencia al elemento
            // Validar si el campo está vacío
            if (input.length === 0) {
                errorSpan.text('El campo correo electrónico es obligatorio.');
                $(this).css('border', '1px solid #dc3545');
                $(this).css('box-shadow', '0 0 5px #dc3545');
                submitResaveEmailButton.disabled = true;
            }
            // Validar si el campo contiene un correo electrónico válido
            else if (!/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.(com|do|net|com\.do)$/.test(input)) {
                errorSpan.text('Ingrese un correo electrónico válido');
                $(this).css('border', '1px solid #dc3545');
                $(this).css('box-shadow', '0 0 5px #dc3545');
                submitResaveEmailButton.disabled = true;
            } else {
                errorSpan.text('');
                $(this).css('border', '1px solid #198754');
                $(this).css('box-shadow', '0 0 5px #198754');
                submitResaveEmailButton.disabled = false;
                // Realiza la llamada Ajax para verificar el correo electrónico
                fetch(emailCheckUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: input
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data, ' data')
                        if (data.exists) {
                            $('#emailStatus').html('<div style="font-size: 80%; color:#dc3545;" >El correo electrónico ya ha sido registrado.</div>');
                            emailInput.css('border', '1px solid #dc3545');
                            emailInput.css('box-shadow', '0 0 5px #dc3545');
                            submitResaveEmailButton.disabled = true;
                        } else {
                            submitResaveEmailButton.disabled = false;
                            $('#emailStatus').html('<div class="text-success"></div>');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }
        });

        submitResaveEmailButton.addEventListener('click', async function () {
            this.disabled = true;

            const response = await fetch(`${url}/api/v2/auth/save-new-register-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email.value
                })
            })

            const res = await response.json();
            const {data} = res;

            if (res.result === true) {
                console.log(res, ' res')
                actualEmailSpan.textContent = data.email;
                email.value = '';
                informativeSection.classList.remove('d-none');
                resendMailSection.classList.add('d-none');
                changeEmailButton.disabled = false;
                submitResaveEmailButton.disabled = true;
            }
        });

        /*
        * Oculta y muestra las secciones
        * */
        function toggleSections(e) {
            e.preventDefault();
            if (informativeSection.classList.contains('d-none')) {
                informativeSection.classList.remove('d-none');
                resendMailSection.classList.add('d-none');
            } else {
                informativeSection.classList.add('d-none');
                resendMailSection.classList.remove('d-none');
            }
        }

        function validate_state() {
            fetch(emailCheckUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email_user
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.user.email_verified_at != null) {
                        location.href = `https://lapieza.do/registro/registro-completo`;
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }

        function startValidation() {
            validate_state();
            setTimeout(startValidation, 4000);
        }

        startValidation();
    });
</script>
</body>
</html>
