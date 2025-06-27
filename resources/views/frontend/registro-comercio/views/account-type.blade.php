<html lang="en">



<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



    <title>La Pieza.DO | Registro</title>



    <!-- Bootstrap CSS -->

    <link rel="stylesheet"

        href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">



    <!-- External Css -->

    <link rel="stylesheet"

        href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/line-awesome.min.css') }}">

    <link rel="stylesheet"

        href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/owl.carousel.min.css') }}" />



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

    <!-- Preloader -->

    <div id="preloader">

        <div id="loader"></div>

    </div>



    <div class="ugf-wrapper flat-grey-bg">

        <div class="ugf-content-block">

            <div class="logo">

                <a>

                    <!-- <img class="loguito" src="assets/images/logo_blanco.png" alt=""> -->

                    <!-- <img class="dark-logo" src="assets/images/logo_black.png" alt=""> -->

                </a>

            </div>

            <div class="container-md">

                <div class="row">

                    <div class="col-lg-8 offset-lg-5 p-sm-0">

                        <div class="ugf-content pt270">

                            <h2>Vamos! <span>Únete a nuestra plataforma</span></h2>

                            <p>Selecciona el tipo de cuenta</p>

                            <div class="account-category">

                                <a href="{{ route('register.buyer.index') }}" class="account-type">

                                    <span class="account-icon"><img

                                            src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/account-personal.png') }}"

                                            alt=""></span>

                                    <p>Comprador </p>

                                    <span class="icon"><i class="las la-arrow-right"></i></span>

                                </a>

                                <a href="{{ route('register.business.index') }}" class="account-type">

                                    <span class="account-icon"><img

                                            src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/account-business.png') }}"

                                            alt=""></span>

                                    <p>Negocios </p>

                                    <span class="icon"><i class="las la-arrow-right"></i></span>

                                </a>

                                <a href="{{ route('register.workshop.index') }}" class="account-type ">

                                    <span class="account-icon"><img

                                            src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/account-workshop.png') }}"

                                            alt=""></span>

                                    <p>Taller </p>

                                    <span class="icon"><i class="las la-arrow-right"></i></span>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

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

                <div class="step step-onprocess">

                    <span>1</span>

                    <p>Tipo de Cuenta</p>

                </div>

                <div class="step">

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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"

        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>

    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>



    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>



    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>

    <!-- main-js-Link -->

    <script src="{{ static_asset('assets/registrocomercio/js/main.js') }}"></script>

    <!-- aos-js-Link -->

    <script src="{{ static_asset('assets/registrocomercio/js/aos.js') }}"></script>

</body>



</html>



<script></script>

