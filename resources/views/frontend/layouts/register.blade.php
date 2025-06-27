<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Pieza.DO | Registro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">
    <!-- External Css -->
    <link rel="stylesheet"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" href="{{ static_asset('assets/css/sweetalert2.min.css') }}">
    <link rel="stylesheet"
          href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet"
          href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/main.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/theme-2.css') }}">
    @yield('styles')
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

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script
        src='https://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key={{ env('GOOGLE_MAPS_API_KEY') }}'
        async></script>

    @yield('head-scripts')
</head>
<body>
{{-- <div id="preloader">
    <div id="loader"></div>
</div> --}}
<div class="ugf-wrapper theme-bg">
    <div class="ugf-content-block">
        <div class="logo"></div>
        <div class="container-md">
            <div class="row">
                <div class="col-lg-10 offset-lg-3 p-sm-0">
                    <div class="ugf-content pt150">
                        <a href="{{ route('shop.view.account.type') }}" class="prev-page"> <i
                                class="las la-arrow-left"></i> Atrás</a>
                        <h3 class="margin-bc">Bienvenido!<span
                                style="font-size: 2.3rem;">Registra tu @yield('register-type')</span></h3>
                        {{--<p>Regístrate de manera sencilla y rápida siguiendo estos pasos o conectándote a través de tus redes sociales favoritas. ¡Únete a nuestra comunidad hoy mismo y descubre todas las ventajas que tenemos para ti!</p>
                        <div class="form-group" style="">
                            <ul class="list-inline social colored mb-4">
                                <li class="list-inline-item">
                                    <a href="--}}{{-- route('social.login', ['provider' => 'facebook']) --}}{{--" class="facebook">
                                        <i class="lab la-facebook-f" style="position: relative;top: 5px;"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="--}}{{-- route('social.login', ['provider' => 'google']) --}}{{--" class="google">
                                        <i class="lab la-google" style="position: relative;top: 5px;"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="--}}{{-- route('social.login', ['provider' => 'twitter']) --}}{{--" class="twitter">
                                        <i class="lab la-twitter" style="position: relative;top: 5px;"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>--}}
                        {{-- formulario con correo --}}
                        @yield('register-form')
                        <div id="result"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="alternet-access">
            <p style="font-size: 1.5rem; font-weight: bold;">Ya tienes una cuenta?<a href="{{ route('user.login') }}">&nbsp;
                    Entra aqui!</a></p>
        </div>
    </div>
    <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-lapieza sidebar-steps">
        <div class="logo">
            <a href="">
                <img class="loguito" src="/public/assets/img/logo_blanco.png" alt="">
                <img class="dark-logo" src="/public/assets/img/logo_black.png" alt="">
            </a>
        </div>
        <div class="steps">
            <div class="step">
                <span>1</span>
                <p>Tipo de Cuenta</p>
            </div>
            <div class="step step-onprocess">
                <span>2</span>
                <p>Verificación de Inicio</p>
            </div>
            <div class="step">
                <span>3</span>
                <p>Confirmación de Datos</p>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
{{-- <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/jquery.min.js') }}"></script> --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
<script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>
<script src='{{ static_asset('assets/js/sweetalert2.min.js') }}' defer></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- main-js-Link -->
<script src="{{ static_asset('assets/registrocomercio/js/main.js') }}"></script>
<script>
    const url = "{{ config('app.url') }}";
    //const phoneCheckUrl = `${url}/phone/check?user_type=`;
    const phoneCheckUrl = "{{ route('phone.check') }}";
    const emailCheckUrl = "{{ route('email.check') }}";
    const cedulaCheckUrl = "{{ route('email.checkCedula') }}";
    const rncCheckUrl = "{{ route('email.checkRnc') }}";
</script>

<script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000, 9999) }}"></script>
<script>
    $(document).ready(function () {
        // Cerrar automáticamente después de 5 segundos
        setTimeout(function () {
            $(".flash-message").fadeOut('slow');
        }, 8000); // 5000 ms = 5 segundos
    });
</script>
@yield('scripts')
</body>
</html>

