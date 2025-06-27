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
            <!-- <img class="" src="assets/images/logo-dark2.png" alt="">
          </a> -->
        </div>
        <div class="container-md">
          <div class="row">
            <div class="col-lg-7 offset-lg-5 p-sm-0">
              <div class="ugf-content pt150">
                <a href="{{ route('shop.view.email.verification') }}" class="prev-page"> <i class="las la-arrow-left"></i> Volver Atrás</a>
                <h2>Detalles de la cuenta personal</h2>
                <p>Escribe tus informaciones y una contraseña segura para registrar su tienda<!-- continuar al siguiente paso -->.</p>
                <form class="account-form" action="personal-shop.php">
                  <div class="row">
                    <div class="col-sm-12 p-sm-0">
                      <div class="form-group">
                        <label for="inputFname">Nombres *</label>
                        <input type="text" placeholder="Nombre" class="form-control" id="inputFname" required>
                      </div>
                    </div>
                    <!-- <div class="col-sm-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputLname">Apellidos *</label>
                        <input type="text" placeholder="ej. Pérez" class="form-control" id="inputLname" required>
                      </div>
                    </div> -->
                    <!-- <div class="col-md-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputPhone">Móvil</label>
                        <input type="text" placeholder="ej. +123 456 7890" class="form-control" id="inputPhone" required>
                      </div>
                    </div> -->
                    <!-- <div class="col-md-6 p-sm-0">
                      <div class="form-group">
                        <label for="inputDate">Fecha de Nacimiento</label>
                        <div class="birth-date">
                          <div class="select-input birth-date-input">
                            <span></span>
                            <select id="inputDate" class="form-control" required>
                              <option value="">Día</option>
                              <option value="">01</option>
                              <option value="">02</option>
                              <option value="">03</option>
                              <option value="">04</option>
                              <option value="">05</option>
                              <option value="">06</option>
                              <option value="">07</option>
                              <option value="">08</option>
                              <option value="">09</option>
                              <option value="">10</option>
                              <option value="">11</option>
                              <option value="">12</option>
                              <option value="">13</option>
                              <option value="">14</option>
                              <option value="">15</option>
                              <option value="">16</option>
                              <option value="">17</option>
                              <option value="">18</option>
                              <option value="">19</option>
                              <option value="">20</option>
                              <option value="">21</option>
                              <option value="">22</option>
                              <option value="">23</option>
                              <option value="">24</option>
                              <option value="">25</option>
                              <option value="">26</option>
                              <option value="">27</option>
                              <option value="">28</option>
                              <option value="">29</option>
                              <option value="">30</option>
                            </select>
                          </div>
                          <div class="select-input birth-date-input">
                            <span></span>
                            <select id="inputDate" class="form-control" required>
                              <option value="">Mes</option>
                              <option value="">01</option>
                              <option value="">02</option>
                              <option value="">03</option>
                              <option value="">04</option>
                              <option value="">05</option>
                              <option value="">06</option>
                              <option value="">07</option>
                              <option value="">08</option>
                              <option value="">09</option>
                              <option value="">10</option>
                              <option value="">11</option>
                              <option value="">12</option>
                            </select>
                          </div>
                          <div class="select-input birth-date-input">
                            <span></span>
                            <select id="inputDate" class="form-control" required>
                            <option value="">Año</option>
                            <option value="">1990</option>
                            <option value="">1991</option>
                            <option value="">1992</option>
                            <option value="">1993</option>
                            <option value="">1994</option>
                            <option value="">1995</option>
                            <option value="">1996</option>
                            <option value="">1997</option>
                            <option value="">1998</option>
                            <option value="">1999</option>
                            <option value="">2001</option>
                            <option value="">2002</option>
                            <option value="">2003</option>
                            <option value="">2004</option>
                            <option value="">2005</option>
                            <option value="">2006</option>
                            <option value="">2007</option>
                            <option value="">2008</option>
                            <option value="">2009</option>
                            <option value="">2010</option>
                          </select>
                          </div>
                        </div>
                      </div>
                    </div> -->
                  </div>
                  <div class="row">
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group pass-group">
                        <label for="inputPass">Contraseña *</label>
                        <input type="password" placeholder="8+ Caracteres" class="form-control" id="inputPass" required>
                        <div class="pass-toggler-btn">
                          <i id="eye" class="lar la-eye"></i>
                          <i id="eye-slash" class="lar la-eye-slash"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 p-sm-0">
                      <div class="form-group pass-group">
                        <label for="inputPass">Confirmar Contraseña *</label>
                        <input type="password" placeholder="8+ Caracteres" class="form-control" id="inputPass" required>
                        <div class="pass-toggler-btn">
                          <i id="eye" class="lar la-eye"></i>
                          <i id="eye-slash" class="lar la-eye-slash"></i>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="form-group check-gender">
                    <div class="custom-radio">
                      <input type="radio" name="gender" class="custom-control-input" id="Gmale" required>
                      <label class="custom-control-label" for="Gmale">Masculino</label>
                    </div>
                    <div class="custom-radio">
                      <input type="radio" name="gender" class="custom-control-input" id="Gfemale" required>
                      <label class="custom-control-label" for="Gfemale">Femenino</label>
                    </div>
                  </div> -->
                    <!-- <button class="btn"><span>Registre su tienda</span><i class="las la-arrow-right"></i></button>   
                </form> -->
                  </div>

                  
                    <button id="btnSubmit" class="btn"><span>Datos de tienda</span><i class="las la-arrow-right"></i></button>
                </form>
              </div>
              </form>
            </div>
          </div>
        </div>
        <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-2 sidebar-steps">
          <div class="steps">
            <div class="step complete-step">
              <span>1</span>
              <p>Verificación de Correo</p>
            </div>

            <div class="step step-onprocess">
              <span>2</span>
              <p>Detalles de la Cuenta Personal</p>
            </div>
            <div class="step">
              <span>3</span>
              <p>Detalle de Tienda</p>
            </div>

            <!-- by joan -->
            <!-- 
          <div class="step complete-step">
            <span>2</span>
            <p>Categoria</p>
          </div> -->

            <!-- <div class="step">
            <span>4</span>
            <p>Detalle de Pagos</p>
          </div>
          <div class="step">
            <span>5</span>
            <p>Detalle de Facturación</p>
          </div> -->
          </div>
        </div>
      </div>



      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      {{-- <script src="assets/js/jquery.min.js"></script> --}}
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
        // Asignar evento clic al botón
        var btnSubmit = $("#btnSubmit");
        // Asignar evento clic al botón
        btnSubmit.click(function(event) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            var name =  $("#inputFname").val();
            var password =  $("#inputPass").val();

            // valida los campos de nombre y contraseña
            if ( name && password ) {
                // Redireccionar a "personal-shop.php"
                localStorage.setItem("name", name);
                localStorage.setItem("password", password);


                window.location.href = "{{ route('shop.view.email.personal-shop') }}";
            } else {
                
            }
        });
    });
</script>