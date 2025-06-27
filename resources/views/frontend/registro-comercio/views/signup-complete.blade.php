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
    <div class="ugf-wrapper final-bg">
        <div class="final-content">
            <div class="icon">
                <img src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/big-green-check.png') }}" alt="">
            </div>
            <h2>Tu Registro fue Completado!</h2>
            @if($user->user_type == 'seller')
                <p>Bienvenido a La Pieza.DO.</p>
                <p>Tu cuenta esta en proceso de revisión te haremos llegar un email a tu correo {{ $user->email }} cuando hayamos confirmado la información de tu negocio.</p>
            @elseif($user->user_type == 'Customer')
                <p>Bienvenido a La Pieza.DO, en breve nuestro departamento de servicios te enviara un email con todos los detalles de tu cuenta y la confirmación de tu registro.</p>
            @elseif($user->user_type == 'workshop')
                <p>Bienvenido a La Pieza.DO, en breve nuestro departamento de servicios te enviara un email con todos los detalles de tu cuenta y la confirmación de tu registro.</p>
            @endif
            <p>Por favor espera mientras te redirigimos a tu panel de control.</p>
            @if ($user->user_type == 'customer')
                <script>
                    setTimeout(function() {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 3000);
                </script>
            @elseif($user->user_type == 'seller')
                <script>
                    setTimeout(function() {
                        window.location.href = "{{ route('seller.dashboard') }}";
                    }, 3000);
                </script>
            @elseif($user->user_type == 'workshop')
                <script>
                    setTimeout(function() {
                        window.location.href = "{{ route('workshop.dashboard') }}";
                    }, 3000);
                </script>
            @endif
        </div>
    </div>
  
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>
    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>
    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>
</body>
</html>
