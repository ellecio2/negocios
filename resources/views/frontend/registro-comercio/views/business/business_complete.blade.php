<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>La Pieza.DO | Registro</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">
        <!-- External Css -->
        <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/owl.carousel.min.css') }}" />
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <!-- Custom Css -->
        <link rel="stylesheet" type="text/css" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/main.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/theme-1.css') }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <!-- Favicon -->
        <link rel="icon" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/apple-touch-icon.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-114x114.png') }}">
    </head>
    <body>
            <!-- Preloader -->
            <div id="preloader">
                <div id="loader"></div>
            </div>
        <div class="ugf-wrapper theme-bg">
            <div class="ugf-content-block">
                <div class="logo">
                </div>
                <div class="container-md">
                    <div class="row">
                        <div class="ol-12 col-lg-7 offset-lg-5 p-sm-0 mx-auto">
                            <div class="ugf-content pt270">
                                <h2>Bienvenido</h2>
                                <p>¡Gracias por registrarte! Para poder completar tu registro,<br> necesitamos que agregues los siguientes campos de negocio:</p>
                                <form class="form-flex email-form was-validated" id="emailGroup" method="POST" action="{{ route('register.business.business_complete_store') }}">
                                    @csrf
                                    <!-- Agrega el campo hidden para enviar el usuario -->
                                    <input type="hidden" name="user" value="{{ $user }}">
                                    <div class="container">
                                        <div class="form-row col-md-9">
                                            <div class="form-group mb-4">
                                                <label for="name" id="nameLabel">Nombre de la tienda</label>
                                                <input type="text" name="name" id="name" placeholder="Nombre" class="form-control name @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="address" id="addressLabel">Dirección</label>
                                                <input type="text" name="address" id="address" placeholder="Dirección" class="form-control address @error('address') is-invalid @enderror" value="{{ old('address') }}">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-12 d-flex">
                                                <button style="-ms-flex: 0 0 230px; flex: 0 0 230px;" id="" class="btn btn-primary btn-lg btn-block"><span>Registra su tienda</span> <i class="las la-arrow-right"></i></button>
                                            </div> 
                                        </div>
                                    </div>
                                </form>
                                <div id="result"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alternet-access">
                    <p><a href="{{ route('logout') }}">Cerrar sesión</a></p>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        {{-- <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/jquery.min.js') }}"></script> --}}
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>
        <!-- main-js-Link -->
        <script src="{{ static_asset('assets/registrocomercio/js/main.js') }}"></script>
    </body>
</html>
<script>
</script>
