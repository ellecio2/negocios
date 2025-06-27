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
            <!-- <img class="" src="assets/images/logo-dark2.png" alt=""> -->
          </a>
        </div>
        <div class="container-md">
          <div class="row">
            <div class="col-lg-7 offset-lg-5 p-sm-0">
              <div class="ugf-content pt150">
                <a href="personal-account.php" class="prev-page"><i class="las la-arrow-left"></i> Volver Atrás</a>
                <h2>Detalles de Pago</h2>
                <p>Escribe los datos de la cuenta de banco donde deseas recibir tus fondos luego de hacer una venta!</p>
                <form action="personal-billing.php" class="account-form">
                  <div class="row">
                  <div class="col-md-6 p-sm-0">
                    <div class="form-group">
                      <label for="inputDate">Nombre del Banco</label>
                       <div class="select-input">
                          <span></span>
                          <select id="inputDate" class="form-control">
                            <option value="">Selecciona el Banco</option>
                            <option value="Banco Ademi">Banco Ademi</option>
                            <option value="Banco BDI">Banco BDI</option>
                            <option value="Banco Bellbank">Banco Bellbank</option>
                            <option value="Banco BHD">Banco BHD</option>
                            <option value="Banco Caribe">Banco Caribe</option>
                            <option value="Banco Lafise">Banco Lafise</option>
                            <option value="Banco López de Haro">Banco López de Haro</option>
                            <option value="Banco Múltiple Activo">Banco Múltiple Activo</option>
                            <option value="Banco Popular">Banco Popular</option>
                            <option value="Banco Promerica">Banco Promerica</option>
                            <option value="Banco Santa Cruz">Banco Santa Cruz</option>
                            <option value="Banco Vimenca">Banco Vimenca</option>
                            <option value="Banesco">Banesco</option>
                            <option value="Banreservas">Banreservas</option>
                            <option value="Citibank">Citibank</option>
                            <option value="Scotiabank">Scotiabank</option>
                          </select>
                        </div>                      
                    </div>
                  </div>
                  <div class="col-md-6 p-sm-0">
                    <div class="form-group">
                      <label for="inputDate">Tipo de Cuenta</label>
                       <div class="select-input">
                          <span></span>
                          <select id="inputDate" class="form-control">
                            <option value="select" selected>Selecciona Tipo</option>
                            <option value="Ahorros">Ahorros</option>
                            <option value="Corriente">Corriente</option>
                          </select>
                        </div>                      
                    </div>
                  </div>
                </div>
                  <div class="form-group">
                    <label for="inputFname">Número de Cuenta</label>
                    <input type="text" placeholder="ej. 0000 0000 0000 0000" class="form-control" id="inputFname">
                  </div>
                  <div class="form-group">
                    <label for="inputFname">Responsable de la Cuenta</label>
                    <input type="text" placeholder="ej. Juan Pérez" class="form-control" id="inputFname">
                  </div>
                  
                  <div class="btn-wrap">
                    <button class="btn"><span>Siguiente</span> <i class="las la-arrow-right"></i></button>
                    <a href="personal-billing.php">Saltar este paso</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-2 sidebar-steps">
        <div class="steps">
          <div class="step complete-step">
            <span>1</span>
            <p>Verificación de Correo</p>
          </div>
          <div class="step complete-step">
            <span>2</span>
            <p>Categoria</p>
          </div>
          <div class="step complete-step">
            <span>3</span>
            <p>Detalles de la Cuenta Personal</p>
          </div>
          <div class="step step-onprocess">
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