<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Negocios La Pieza.DO | Registrate o Inicia Sesión</title>
    <meta name="description" content="Registro e Inicio de Sesión para negocios de La Pieza.DO">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ static_asset('img/favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ static_asset('css/fontawesome-all.min.css') }}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{ static_asset('font/flaticon.css') }}">
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ static_asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/sweetalert2.min.css') }}">
    <style>
        .register-btn:hover {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        
        #submitForm:hover {
            background-color: #003b76 !important;
            border-color: #003b76 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 59, 118, 0.3);
        }
        
        #submitForm {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>

    <div id="preloader" class="preloader">
        <div class='inner'>
            <div class='line1'></div>
            <div class='line2'></div>
            <div class='line3'></div>
        </div>
    </div>
    <section class="fxt-template-animation fxt-template-layout9" data-bg-image="{{ static_asset('img/figure/bg9-l.jpg') }}">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-3">
                    <div class="fxt-header">
                        <a href="{{ url('/') }}" class="fxt-logo"><img src="{{ static_asset('img/logo-9.png') }}"
                                alt="Logos"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="fxt-content">
                        <h2>Inicie Sesión en su Cuenta de Negocios</h2>
                        <div class="fxt-form">
                            <form method="POST" id="FormLogin" action="{{ route('loginfront') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="fxt-transformY-50 fxt-transition-delay-1">
                                        <input type="email" id="email" class="form-control" name="email"
                                            placeholder="Correo Electrónico" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="fxt-transformY-50 fxt-transition-delay-2">
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="Contraseña" >
                                        <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="fxt-transformY-50 fxt-transition-delay-3">
                                        <div class="fxt-checkbox-area">
                                            <div class="checkbox">
                                                <input id="checkbox1" type="checkbox" name="remember">
                                                <label for="checkbox1">Mantener Mi Sesión Iniciada.</label>
                                            </div>
                                            <a href="{{ route('password.request') }}" class="switcher-text">Olvidó
                                                Contraseña?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="fxt-transformY-50 fxt-transition-delay-4">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <button type="submit" id="submitForm" class="fxt-btn-fill w-100">Iniciar
                                                    Sesión</button>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <a href="{{ route('register') }}" class="fxt-btn-fill w-100 text-center register-btn" style="display: inline-block; text-decoration: none; background-color: #f8f9fa; color: #333; border: 1px solid #ddd; transition: all 0.3s ease;">Registrarse</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jquery-->
    <script src="{{ static_asset('js/jquery.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ static_asset('js/bootstrap.min.js') }}"></script>
    <!-- Imagesloaded js -->
    <script src="{{ static_asset('js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- Validator js -->
    <script src="{{ static_asset('js/validator.min.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ static_asset('js/main.js') }}"></script>
    <script src='{{ static_asset('assets/js/sweetalert2.min.js') }}' defer></script>
    <script type="text/javascript">

        function autoFillSeller() {
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }

        function autoFillCustomer() {
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }

        function autoFillDeliveryBoy() {
            $('#email').val('deliveryboy@example.com');
            $('#password').val('123456');
        }
    </script>
    <script>
        $(document).ready(function () {

            $("#FormLogin").on("submit", function (e) {
                e.preventDefault();

                $("#submitForm").prop("disabled", true);
                $("#preloader").show();

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
                        if (response.state === true) {
                            window.location.href = response.redirect_url;
                        } else if (response.state === false) {
                            $("#submitForm").prop("disabled", false);
                            Swal({
                                type: 'error',
                                title: 'Oops!',
                                text: response.message.messages[0].message || 'Unknown error occurred',
                                timer: 6000
                            });
                        } else if (response.message) {
                            $("#submitForm").prop("disabled", false);
                            Swal({
                                type: 'error',
                                title: 'Oops!',
                                text: response.message,
                                timer: 6000
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                Swal({
                                    type: 'error',
                                    title: 'Oops!',
                                    text: response.message || 'An error occurred',
                                    timer: 600000
                                }).then(function () {
                                    $("#submitForm").prop("disabled", false);
                                    $("#preloader").hide();
                                });
                            }
                        } catch (e) {
                            Swal({
                                type: 'error',
                                title: 'Oops!',
                                text: 'An unknown error occurred',
                                timer: 600000
                            }).then(function () {
                                $("#submitForm").prop("disabled", false);
                                $("#preloader").hide();
                            });
                        }
                    },
                });
            });

            // Obtener el estado almacenado en la cookie
            var rememberMe = getCookie('rememberMe');
            // Establecer el estado del checkbox según el valor de la cookie
            if (rememberMe === 'true') {
                $('input[name="remember"]').prop('checked', true);
            }
            // Manejar el evento de cambio del checkbox
            $('input[name="remember"]').change(function () {
                // Obtener el estado actual del checkbox
                var isChecked = $(this).is(':checked');
                // Almacenar el estado en la cookie
                setCookie('rememberMe', isChecked, 365);
            });

            // Función para obtener el valor de una cookie
            function getCookie(name) {
                var value = "; " + document.cookie;
                var parts = value.split("; " + name + "=");
                if (parts.length === 2) {
                    return parts.pop().split(";").shift();
                }
            }

            // Función para establecer el valor de una cookie
            function setCookie(name, value, days) {
                var expires = new Date();
                expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = name + "=" + value + ";expires=" + expires.toUTCString() + ";path=/";
            }
        });
    </script>
</body>

</html>