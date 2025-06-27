<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>La Pieza.DO | Registro</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">
    <!-- External Css -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/owl.carousel.min.css') }}"/>
    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/theme-1.css') }}">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-114x114.png') }}">
    <style>
        /* Estilo input */
        .form-input-edit {
            height: 60px;
            width: 100%;
            border-radius: 10px;
            outline: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            padding: 0 10px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            text-align: center;
            font-size: 30px;
        }

        .form-input-edit::placeholder {
            font-size: 30px;
            color: lightgray;
            text-align: center;
        }

        .input-error {
            border: 1px solid #dc3545 !important;
            box-shadow: 0 0 5px #dc3545 !important;
        }

        .input-confirmed {
            border: 1px solid rgb(0, 59, 114) !important;
            box-shadow: 0 0 5px rgb(0, 59, 114) !important;
        }

        .error-message {
            color: white;
            font-size: 1.4rem;
            width: 100%;
            padding: 7px 10px;
            font-weight: bold;
            text-align: center;
            background-color: #dc3545;
            border-radius: 15px;
        }

        .alert-success {
            color: #0a3622;
            font-size: 1.4rem;
            width: 100%;
            padding: 7px 10px;
            font-weight: bold;
            text-align: center;
            background-color: #c2eec2;
            border-radius: 15px;
        }

        h2{
            font-size: 2.5rem!important;
            margin-bottom: 10px!important;
            text-align: center;
        }

        form{
            margin: 0 auto;
        }

        form button{
            margin: 30px 0;
            width: 100%;
            padding: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px;
            background-color: #0b5ed7;
            color: white;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
        }

        form button:hover{
            background-color: #0a58ca;
        }

        form button:disabled{
            background-color: #3175db;
        }

        .subtitle {
            color: #0b0b0b;
            font-size: 1.2rem;
            margin: 0 0 15px 0;
            text-align: center;
        }

        @media (min-width: 768px) {
            .subtitle{
                font-size: 1.6rem;
            }

            h2{
                font-size: 3.5rem!important;
                margin-bottom: 10px!important;
            }
        }

        .subtitle span {
            color: #E63118;
        }
    </style>
</head>
<body>
<div class="ugf-wrapper flat-grey-bg">
    <div class="ugf-content-block">
        <div class="container-md">
            <div class="row">
                <div class="col-lg-7 offset-lg-5 p-sm-0">
                    <div class="ugf-content pt340" id="verified-code-section">
                        <h2>Verificación de Número Móvil</h2>

                        <h3 class="subtitle">Introduce el Código de 6 Dígitos que Hemos Enviado a tu WhatsApp
                            <span id="actual-phone-number">{{ auth()->user()->phone }}</span>
                        </h3>

                        <form action="{{ route('phone.verified') }}" method="post">
                            @csrf
                            <div class="d-flex email-verification-form">
                                <div class="form-group" style="margin: 10px;">
                                    <input type="text" name="verification1" id="verification1" placeholder="0" class="form-input-edit" maxlength="1" disabled>
                                </div>
                                <div class="form-group" style="margin: 10px;">
                                    <input type="text" name="verification2" id="verification2" placeholder="0" class="form-input-edit" maxlength="1" disabled>
                                </div>
                                <div class="form-group" style="margin: 10px;">
                                    <input type="text" name="verification3" id="verification3" placeholder="0" class="form-input-edit" maxlength="1" disabled>
                                </div>
                                <div class="form-group" style="margin: 10px;">
                                    <input type="text" name="verification4" id="verification4" placeholder="0" class="form-input-edit" maxlength="1" disabled>
                                </div>
                                <div class="form-group" style="margin: 10px;">
                                    <input type="text" name="verification5" id="verification5" placeholder="0" class="form-input-edit" maxlength="1" disabled>
                                </div>
                                <div class="form-group" style="margin: 10px;">
                                    <input type="text" name="verification6" id="verification6" placeholder="0" class="form-input-edit" maxlength="1" disabled>
                                </div>
                            </div>

                            @error('verification_code')
                                <div class="error-message" id="error-message">{{ $message }}</div>
                            @enderror

                            <div class="error-message d-none" id="error-message"></div>

                            <div class="alert-success d-none" id="code-resended">
                                Se ah enviado un nuevo codigo
                            </div>

                            <button type="submit" id="manual-verification-button">
                                Verificar
                            </button>
                        </form>


                        <div class="d-flex align-items-center justify-content-between w-100">
                            <p class="resend-code">¿No lo has recibido aún?
                                <button id="reenviarBtn" type="submit" class="link-button" disabled>
                                    Reenviar código
                                </button>
                            </p>
                            <p class="resend-code">¿No es tu numero?
                                <button class="link-button" id="change-phone-number" disabled>
                                    Cambialo aqui
                                </button>
                            </p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                    <div class="ugf-content pt340 d-none" id="resave-phone-section">
                        <h2>Actualizar numero móvil de registro</h2>
                        <h3 class="subtitle">Introduce el nuevo numero telfonico al cual te enviaremos un nuevo codigo de 6 digitos via
                            <span>WhatsApp</span>
                        </h3>

                        <div class="form-group my-4 w-50 m-auto" id="phoneField">
                            <input type="tel" name="phone" id="phone"
                                   placeholder="8091234567"
                                   class="form-input-edit fs-3 phone @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" data-user="{{ auth()->user()->user_type }}" required>
                            <div id="phoneError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="phoneStatus"></div>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex w-75 align-items-center justify-content-around m-auto">
                            <button class="btn btn-primary bg-primary d-block w-25 p-3 fs-5 fw-bold my-3" disabled id="save-phone">
                                Enviar
                            </button>
                            <button class="btn btn-dangers bg-danger d-block w-25 p-3 fs-5 fw-bold my-5 text-white" id="cancel-save-phone">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="alternet-access">
            <a href="{{ route('logout') }}">Cerrar sesión</a>
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
</script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
{{-- <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/jquery.min.js') }}"></script> --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>
<script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>
</body>
</html>
<script>
    window.addEventListener('load', () => {
        const inputs = document.querySelectorAll("input[id*='verification']");
        const errorSpan = document.querySelector('#error-message');
        const confirmResendCode = document.querySelector('#code-resended');
        const resendButton = document.querySelector('#reenviarBtn');
        const changePhoneNumberButton = document.querySelector('#change-phone-number');
        const phone = document.getElementById('phone');
        const apiLoader = document.getElementById('api-check-loader');
        const manualVerificationButton = document.getElementById('manual-verification-button');

        const submitResavePhoneButton = document.getElementById('save-phone');
        const cancelResavePhoneButton = document.getElementById('cancel-save-phone');
        const actualPhoneNumberSpan = document.getElementById('actual-phone-number');

        const verificationSection = document.getElementById('verified-code-section');
        const resavePhoneSection = document.getElementById('resave-phone-section');

        let verification_code = "";
        let counter = localStorage.getItem('counter') ? parseInt(localStorage.getItem('counter')) : 0;

        /*
        * Agrega el +1 al input apenas cargue la pagina
        * */
        addPlusOneToPhoneInputs(phone);

        changePhoneNumberButton.disabled = false;
        submitResavePhoneButton.addEventListener('click', async function () {
            this.disabled = true;

            const response = await fetch(`${url}/api/v2/auth/save-new-register-phone`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phone: phone.value
                })
            })

            const res = await response.json();
            const {data} = res;

            if (res.result === true) {
                actualPhoneNumberSpan.textContent = data.phone;
                phone.value = '';
                verificationSection.classList.remove('d-none');
                resavePhoneSection.classList.add('d-none');
                changePhoneNumberButton.disabled = false;
                submitResavePhoneButton.disabled = true;
                addPlusOneToPhoneInputs(phone);
            }
        });

        changePhoneNumberButton.addEventListener('click', toggleSections);
        cancelResavePhoneButton.addEventListener('click', toggleSections);

        manualVerificationButton.addEventListener('click', function(e){
            this.disabled = true;

            inputs.forEach(input => {
                input.disabled = false;
            });

            this.innerHTML = `
                <div class="spinner-border text-white m-auto d-block" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            `;

            setTimeout(() => {
                e.target.parentElement.submit();
            }, 1000);
        })

        /*
        * Validar input
        * */
        phone.addEventListener('input', function () {
            const phoneField = document.getElementById('phone');
            const inputValue = phoneField.value.trim();
            const input = inputValue.replace(/\s/g, ''); // Eliminar espacios en blanco
            const errorSpan = document.getElementById('phoneError');
            const phoneInput = this; // Almacenar una referencia al elemento

            addPlusOneToPhoneInputs(phoneInput);

            // Validar si el campo está vacío
            if (input.length === 0) {
                errorSpan.textContent = 'El campo número móvil es obligatorio.';
                this.style.border = '1px solid #dc3545';
                this.style.boxShadow = '0 0 5px #dc3545';
                submitResavePhoneButton.disabled = true;
            }
            // Validar si el campo contiene solo números y el símbolo "+"
            else if (!/^[\d+]+$/.test(input)) {
                errorSpan.textContent = 'Solo se permiten números y el símbolo "+".';
                this.style.border = '1px solid #dc3545';
                this.style.boxShadow = '0 0 5px #dc3545';
                submitResavePhoneButton.disabled = true;
            }
            // Validar si el campo tiene el formato correcto para el código de área de la República Dominicana y los siguientes 8 números
            else if (!/^\+1\d{10}$/.test(input)) { // Eliminar el espacio después de "+1"
                errorSpan.textContent = 'El número móvil debe tener el formato +1 seguido de 10 números.';
                this.style.border = '1px solid #dc3545';
                this.style.boxShadow = '0 0 5px #dc3545';
                submitResavePhoneButton.disabled = true;
            } else {
                const user = this.dataset.user;
                errorSpan.textContent = '';
                this.style.border = '1px solid #198754';
                this.style.boxShadow = '0 0 5px #198754';
                // Realiza la llamada Ajax para verificar el número de teléfono
                fetch(phoneCheckUrl + user, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phone: input,
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            document.getElementById('phoneStatus').innerHTML = '<div style="font-size: 80%; color:#dc3545;" >El número de teléfono ya ha sido registrado.</div>';
                            phoneInput.style.border = '1px solid #dc3545'; // Usar la referencia almacenada
                            phoneInput.style.boxShadow = '0 0 5px #dc3545'; // Usar la referencia almacenada
                            submitResavePhoneButton.disabled = true;
                        } else {
                            submitResavePhoneButton.disabled = false;
                            document.getElementById('phoneStatus').innerHTML = '<div class="text-success"></div>';
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        document.getElementById('phoneStatus').innerHTML = '<div style="font-size: 80%; color:#dc3545;" >Error inesperado contacta un administrador.</div>';
                        submitResavePhoneButton.disabled = true;
                    });
            }
        });

        /*
        * Mostrar el contador al momento de reenviar el codigo
        * */
        const intervalId = setInterval(() => {
            if (counter > 0) {
                counter--;
                localStorage.setItem('counter', counter);
                resendButton.textContent = `Podrás enviar un nuevo codigo en ${counter} ${counter > 1 ? 'segundos' : 'segundo'}`;
            }

            if (counter <= 57) {
                confirmResendCode.classList.add('d-none');
                confirmResendCode.classList.remove('d-block');
            }

            if (counter === 0) {
                clearInterval(intervalId);
                resendButton.textContent = 'Reenviar código';
                resendButton.disabled = false;
                localStorage.removeItem('counter');
            }
        }, 1000);

        /*
        * Bloquear el boton de reenviar codigo durante 60 segundos y envia un nuevo codigo
        * */
        resendButton.addEventListener('click', () => {
            resendButton.disabled = true;
            resendCode();
        })

        /*
        * Agrega los listeners a cada input de codigo de verificación
        * */
        inputs.forEach(input => {
            // input.addEventListener('input', updateVerificationCode);
            input.addEventListener('keydown', moveToNextInput);
            input.addEventListener('input', validateInput);
            input.disabled = false;
        });

        /*
        * Llama a la API para reenviar un nuevo codigo
        * */
        async function resendCode() {
            const response = await fetch(`${url}/api/v2/auth/resend_code`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })

            await response.json();

            if (response.ok) {
                confirmResendCode.classList.add('d-block');
                confirmResendCode.classList.remove('d-none');
                let counter = localStorage.getItem('counter') ? parseInt(localStorage.getItem('counter')) : 60;
                const intervalId = setInterval(() => {
                    counter--;
                    localStorage.setItem('counter', counter.toString());
                    resendButton.textContent = `Podrás enviar un nuevo codigo en ${counter} ${counter > 1 ? 'segundos' : 'segundo'}`;

                    if (counter === 57) {
                        confirmResendCode.classList.add('d-none');
                        confirmResendCode.classList.remove('d-block');
                    }

                    if (counter === 0) {
                        clearInterval(intervalId);
                        resendButton.textContent = 'Reenviar código';
                        resendButton.disabled = false;
                        localStorage.removeItem('counter');
                    }
                }, 1000);
            }
        }

        /*
        * Revisa en tiempo real el codigo contra la API
        * */
        /*async function updateVerificationCode() {

            verification_code = "";

            inputs.forEach(input => {
                verification_code += input.value;
            });

            if (verification_code.length === 6) {
                manualVerificationButton.disabled = true;
                manualVerificationButton.innerHTML = `
                    <div class="spinner-border text-white m-auto d-block" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                `;
                inputs.forEach(input => input.disabled = true)
                const response = await fetch(`${url}/api/v2/auth/confirm_code`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        verification_code
                    })
                })

                const data = await response.json();
                if (response.ok) {
                    inputs.forEach(input => {
                        input.classList.remove('input-error');
                        input.classList.add('input-confirmed');
                    });

                    setTimeout(() => {
                        window.location.replace(`${url}/registro/verificacion-de-correo-electronico`);
                    }, 500)
                } else if (response.status === 422) {
                    inputs.forEach(input => {
                        input.disabled = false;

                        input.classList.add('input-error');
                        if (input.classList.contains('input-confirmed')) {
                            input.classList.remove('input-confirmed');
                        }

                        errorSpan.textContent = data.message;   // Asegurate que el mensaje de error viene así
                        errorSpan.classList.remove('d-none');
                        errorSpan.classList.add('d-block');

                        if (input.id === "verification6") {
                            input.focus();
                        }
                    });

                    manualVerificationButton.disabled = false;
                    manualVerificationButton.textContent = 'Verificar'
                } else {
                    manualVerificationButton.disabled = false;
                    manualVerificationButton.textContent = 'Verificar'
                }
            }
        }*/

        /*
        * Mueve al usuario al siguiente input una vez digitando un numero
        * */
        function moveToNextInput(e) {
            let id = parseInt(e.target.id.slice(12));
            console.log('event', e)

            console.log('id:' + id);

            if (e.key === "Backspace" && e.target.value === "") {
                const previousInput = document.getElementById('verification' + (id - 1));
                if (previousInput) {
                    previousInput.focus();
                }
            }

            if (e.key !== "Backspace" && e.target.value.length >= 1) {
                const nextInput = document.getElementById('verification' + (id + 1));

                if (nextInput && e.target.value !== "") {
                    nextInput.focus();
                }
            }

        }

        /*
        * Valida los inputs del codigo de verificación
        * */
        function validateInput(e) {
            const input = e.target;
            const inputValue = input.value.trim();

            // Inicialmente, supongamos que hay un error
            let errorMessage = '';
            input.classList.add('input-error');
            input.classList.remove('input-confirmed');
            errorSpan.classList.add('d-block');
            errorSpan.classList.remove('d-none');

            // Verificar condiciones para eliminar el error
            if (inputValue.length <= 1 && /^\d+$/.test(inputValue)) {
                if (inputValue.length === 1) {
                    input.classList.remove('input-error');
                    input.classList.add('input-confirmed');
                    errorMessage = '';
                    errorSpan.classList.add('d-none');
                    errorSpan.classList.remove('d-block');
                } else {
                    errorMessage = 'Solo se permite un número';
                }
            } else {
                errorMessage = inputValue.length === 0 ? 'El campo es obligatorio.' : 'Solo se permiten números.';
            }

            errorSpan.textContent = errorMessage;
        }

        /*
        * Agrega el +1 al input de numero de telefono
        * */
        function addPlusOneToPhoneInputs(phoneInput) {
            const inputValue = phoneInput.value;
            if (!inputValue || !inputValue.startsWith('+1')) {
                phoneInput.value = '+1';
            }

            if (inputValue && inputValue.length <= 2) {
                phoneInput.value = '+1';
            }
        }

        /*
        * Oculta y muestra las secciones
        * */
        function toggleSections(){
            if (verificationSection.classList.contains('d-none')) {
                verificationSection.classList.remove('d-none');
                resavePhoneSection.classList.add('d-none');
            } else {
                verificationSection.classList.add('d-none');
                resavePhoneSection.classList.remove('d-none');
            }
        }
    });
</script>
