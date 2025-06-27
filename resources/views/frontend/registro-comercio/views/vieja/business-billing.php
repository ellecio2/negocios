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
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Custom Css --> 
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/theme-1.css">

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
            <!-- <img class="" src="assets/images/logo-dark2.png" alt=""> -->
          </a>
        </div>
        <div class="container-md">
          <div class="row">
            <div class="col-lg-7 offset-lg-5 p-sm-0">
              <div class="ugf-content pt150">
                <a href="business-payments.php" class="prev-page"><i class="las la-arrow-left"></i> Volver Atrás</a>
                <h2>Dirección de Facturación</h2>
                <p>Escribe los detalles de facturación de tu negocio, también puedes elegir tu ubicación en el mapa!</p>
                <form action="signup-complete.php" class="account-form">
                  <div class="row">
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group country-select">
                        <label for="inputCountry">Selecciona tu Provincia</label>
                        <div class="select-input choose-country">
                          <span></span>
                            <select id="inputCountry" class="form-control">
                              <option value="Seleccione" selected>Seleccione Provincia</option>
                              <option value="Azua">Azua</option>
                              <option value="Bahoruco">Bahoruco</option>
                              <option value="Barahona">Barahona</option>
                              <option value="Dajabón">Dajabón</option>
                              <option value="Duarte">Duarte</option>
                              <option value="El Seibo">El Seibo</option>
                              <option value="Elias Piña">Elias Piña</option>
                              <option value="Espaillat">Espaillat</option>
                              <option value="Hato Mayor">Hato Mayor</option>
                              <option value="Hermanas Mirabal">Hermanas Mirabal</option>
                              <option value="Independencia">Independencia</option>
                              <option value="La Altagracia">La Altagracia</option>
                              <option value="La Romana">La Romana</option>
                              <option value="La Vega">La Vega</option>
                              <option value="Maria Trinidad Sanchez">Maria Trinidad Sanchez</option>
                              <option value="Monseñor Nouel">Monseñor Nouel</option>
                              <option value="Monte Cristi">Monte Cristi</option>
                              <option value="Monte Plata">Monte Plata</option>
                              <option value="Pedernales">Pedernales</option>
                              <option value="Peravia">Peravia</option>
                              <option value="Puerto Plata">Puerto Plata</option>
                              <option value="Samaná">Samaná</option>
                              <option value="San Cristobal">San Cristobal</option>
                              <option value="San Jose de Ocoa">San Jose de Ocoa</option>
                              <option value="San Juan">San Juan</option>
                              <option value="San Pedro de Macorís">San Pedro de Macorís</option>
                              <option value="Sánchez Ramírez">Sánchez Ramírez</option>
                              <option value="Santiago">Santiago</option>
                              <option value="Santiago Rodríguez">Santiago Rodríguez</option>
                              <option value="Santo Domingo">Santo Domingo</option>
                              <option value="Valverde">Valverde</option>
                              <option value="Distrito Nacional">Distrito Nacional</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputCity">Ciudad</label>
                        <input type="text" placeholder="ej. Distrito Nacional" class="form-control" id="inputCity">
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputAddress2">Direccíon</label>
                        <input type="text" placeholder="ej. Calle 1 No.2" class="form-control" id="inputAddress2">
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputAddress2">Direccíon 2</label>
                        <input type="text" placeholder="ej. Villa Juana" class="form-control" id="inputAddress2">
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputzipcode">Referencia</label>
                        <input type="text" placeholder="ej. Cerca de un edificio azul" class="form-control" id="inputzipcode">
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputPhone">Teléfono</label>
                        <input type="text" placeholder="ej. 123 456 7890" class="form-control" id="inputPhone">
                      </div>
                    </div>
                  </div>
                  <div class="form-group check-flex mb10">
                    <div class="custom-checkbox mb10">
                      <input type="checkbox" class="custom-control-input" id="inputCheck" required>
                      <label class="custom-control-label" for="inputCheck">Acepto los <a href="#">Terminos y Condiciones</a> y <a href="#">las Politicas de Privacidad</a></label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6 p-sm-0">
                        <button class="btn"><span>FINALIZAR</span> <i class="las la-check"></i></button>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                        <a class="btn" style="width: 58%; background-color: #E63108"><span>ABRIR EL MAPA</span> <i class="las la-map"></i></a>
                    </div>
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
            <p>Detalles de la Cuenta de Negocios</p>
          </div>
          <div class="step complete-step">
            <span>4</span>
            <p>Detalle de Pagos</p>
          </div>
          <div class="step step-onprocess">
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