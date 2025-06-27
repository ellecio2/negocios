<php lang="en">

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


  </head>

  <body>

    <div class="ugf-wrapper flat-grey-bg">
      <div class="ugf-content-block">
        <div class="logo">
          <a href="../register.php">
            <!-- <img class="light-logo" src="assets/images/logo.png" alt=""> -->
            <!-- <img class="" src="assets/images/logo-dark2.png" alt=""> -->
          </a>
        </div>
        <div class="container-md">
          <div class="row">
            <div class="col-lg-7 offset-lg-5 p-sm-0">
              <div class="ugf-content pt340">
                <a href="{{  route('shop.view.register')  }}" class="prev-page"> <i class="las la-arrow-left"></i> Volver Atrás</a>
                <h2>Verificación de correo electrónico</h2>
                <p>Por favor verifica tu dirección de correo electrónico <strong><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="7e131f17123e1b061f130e121b501d1113">[email&#160;protected]</a></strong> <br> y coloca el código de verificación aqui!</p>
                <form  class="form-flex email-verification-form">
                  <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" maxlength="1">
                  </div>
                  <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" maxlength="1">
                  </div>
                  <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" maxlength="1">
                  </div>
                  <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" maxlength="1">
                  </div>
                  <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" maxlength="1">
                  </div>
                  <a href="{{ route('shop.view.personal-account') }}"  class="btn"><span>Siguiente</span> <i class="las la-arrow-right"></i></a>
                </form>
                <p class="resend-code">No lo has recibido aún? <a href="#"> Reenviar código</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-2 sidebar-steps">
        <div class="steps">
          <div class="step step-onprocess">
            <span>1</span>
            <p>Verificación de Correo</p>
          </div>
          <!-- <div class="step">
            <span>2</span>
            <p>Categoria</p>
          </div> -->
          <div class="step">
            <span>2</span>
            <p>Detalles de la Cuenta</p>
          </div>
          <div class="step">
            <span>3</span>
            <p>Detalle de Tienda</p>
          </div>
          <!-- <div class="step">
            <span>5</span>
            <p>Detalle de Facturación</p>
          </div> -->
        </div>
      </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    {{-- <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/jquery.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>

    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>

    <script href="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>
  </body>
</php>

<script>
  // Esperar a que el documento esté listo
  $(document).ready(function() {

    console.log("listco");
    // Obtener el valor del Local Storage
    var correoElectronicoGuardado = localStorage.getItem("correoElectronico");

    // Mostrar el valor en el div con el id "result"
    if (correoElectronicoGuardado) {
      console.log(correoElectronicoGuardado)
    } else {
      console.log("No se ha guardado ningún correo electrónico.");
    }
  });
</script>

