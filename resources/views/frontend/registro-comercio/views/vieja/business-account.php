<php lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>La Pieza.DO | Registro</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- External Css -->
    <link rel="stylesheet" href="assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css" />

    <!-- Custom Css --> 
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/theme-1.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    

    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/icon-114x114.png">


  </head>
  <body>
    
    <div class="ugf-wrapper flat-grey-bg">
      <div class="ugf-content-block">
        <div class="logo">
          <a href="../register.php">
            <!-- <img class="light-logo" src="assets/images/logo.png" alt=""> -->
            <!-- <img class="" src="assets/images/logo-dark2.png" alt="">
          </a> -->
        </div>
        <div class="container-md">
          <div class="row">
            <div class="col-lg-7 offset-lg-5 p-sm-0">
              <div class="ugf-content pt150">
                <a href="account-category.php"class="prev-page"> <i class="las la-arrow-left"></i> Volver Atrás</a>
                <h2>Detalles de la cuenta de negocios</h2>
                <p>Escribe las informaciones de tu negocio y una contraseña segura para continuar al siguiente paso.</p>
                <form action="business-payments.php" class="account-form">
                  <div class="row">
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputFname">Nombre del Negocio</label>
                        <input type="text" placeholder="ej. Repuestos La Pieza" class="form-control" id="inputBname">
                      </div>
                    </div>
                    <div class="col-sm-3 p-sm-0">
                      <div class="form-group">
                        <label for="inputLname">R.N.C.</label>
                        <input type="text" placeholder="ej. 1-11-11111-1" class="form-control" id="inputBrnc">
                      </div>
                    </div>
                    <div class="col-sm-3 p-sm-0">
                      <div class="form-group">
                        <label for="inputLname">Teléfono.</label>
                        <input type="text" placeholder="ej. 123 456 7890" class="form-control" id="inputBtel">
                      </div>
                    </div>
                    <div class="col-md-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputPhone">Nombre Responsable</label>
                        <input type="text" placeholder="ej. Juan Pérez" class="form-control" id="inputBresponsable">
                      </div>
                    </div>
                    <div class="col-sm-3 p-sm-0">
                      <div class="form-group">
                        <label for="inputLname">Cédula.</label>
                        <input type="text" placeholder="ej. 000-0000000-0" class="form-control" id="inputBcedula">
                      </div>
                    </div>
                    <div class="col-sm-3 p-sm-0">
                      <div class="form-group">
                        <label for="inputLname">Móvil.</label>
                        <input type="text" placeholder="ej. 123 456 7890" class="form-control" id="inputBmovil">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group pass-group">
                      <label for="inputPass">Contraseña</label>
                      <input type="password" placeholder="8+ Caracteres" class="form-control" id="inputPass">
                      <div class="pass-toggler-btn">
                      <i id="eye" class="lar la-eye"></i>
                      <i id="eye-slash" class="lar la-eye-slash"></i>
                    </div>
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group pass-group">
                        <label for="inputPass">Confirmar Contraseña</label>
                        <input type="password" placeholder="8+ Caracteres" class="form-control" id="inputPass">
                        <div class="pass-toggler-btn">
                          <i id="eye" class="lar la-eye"></i>
                          <i id="eye-slash" class="lar la-eye-slash"></i>
                        </div>
                      </div>
                    </div>
                  <button class="btn"><span>Siguiente</span> <i class="las la-arrow-right"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-2 sidebar-steps">
        <div class="steps">
          <div class="step">
            <span>1</span>
            <p>Verificación de Correo</p>
          </div>
          <div class="step">
            <span>2</span>
            <p>Categoria</p>
          </div>
          <div class="step step-onprocess">
            <span>3</span>
            <p>Detalles de la Cuenta de Negocios</p>
          </div>
          <div class="step">
            <span>4</span>
            <p>Detalle de Pagos</p>
          </div>
          <div class="step">
            <span>5</span>
            <p>Detalle de Facturación</p>
          </div>
        </div>
      </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <script src="assets/js/owl.carousel.min.js"></script>

    <script src="assets/js/custom.js"></script>
  </body>
</php>