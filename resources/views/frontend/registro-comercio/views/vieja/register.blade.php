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

        <div class="ugf-wrapper theme-bg">
            <div class="ugf-content-block">
                <div class="logo">

                </div>
                <div class="container-md">
                    <div class="row">
                        <div class="col-lg-7 offset-lg-5 p-sm-0">
                            <div class="ugf-content pt270">
                                <h2>Vamos! <span>Únete a nuestra plataforma</span></h2>
                                <p>Introduce un correo electrónico válido para completar algunos sencillos pasos <br>
                                    para registrar tu cuenta.</p>
                                <form  class="form-flex email-form">
                                    <div class="form-group">
                                        <label for="inputMail">Correo Electrónico</label>
                                        <input type="email" placeholder="micorreo@email.com"
                                            class="form-control email" id="inputMail" required>
                                    </div>
                                    <button id="btnSubmit" class="btn"><span>Iniciemos</span> <i
                                            class="las la-arrow-right"></i></button>
                                </form>
                                <div id="result"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alternet-access">
                    <p>Ya tienes una cuenta?<a href="login.php">&nbsp; Entra aqui!</a></p>
                </div>
            </div>
            <div class="ugf-sidebar flex-bottom ugf-sidebar-bg">
                <div class="testimonial-carousel owl-carousel">
                    <div class="item">
                        <div class="quote">
                            <img src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/quote.png') }}"
                                alt="">
                        </div>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a
                            page when looking at its layout.</p>
                        <h5 class="name">Ronna E. Gomez</h5>
                        <span class="designation">CEO, Google</span>
                    </div>
                    <div class="item">
                        <div class="quote">
                            <img src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/quote.png') }}"
                                alt="">
                        </div>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a
                            page when looking at its layout.</p>
                        <h5 class="name">Ronna E. Gomez</h5>
                        <span class="designation">CEO, Google</span>
                    </div>
                    <div class="item">
                        <div class="quote">
                            <img src="{{ static_asset('assets/registrocomercio/registro-form/assets/images/quote.png') }}"
                                alt="">
                        </div>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a
                            page when looking at its layout.</p>
                        <h5 class="name">Ronna E. Gomez</h5>
                        <span class="designation">CEO, Google</span>
                    </div>
                </div>

                <!-- <div class="clients">
                    <div class="client">
                        <img src="assets/images/client/1.png" class="img-fluid" alt="">
                    </div>
                    <div class="client">
                        <img src="assets/images/client/2.png" class="img-fluid" alt="">
                    </div>
                    <div class="client">
                        <img src="assets/images/client/3.png" class="img-fluid" alt="">
                    </div>
                    <div class="client">
                        <img src="assets/images/client/4.png" class="img-fluid" alt="">
                    </div>
                </div> -->
            </div>
        </div>



        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/jquery.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/popper.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ static_asset('assets/registrocomercio/registro-form/assets/js/custom.js') }}"></script>

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

            // Obtener el valor del campo de correo electrónico
            var correoElectronico = $("#inputMail").val();

            // Validar si el campo de correo electrónico contiene un valor válido de correo
            if (isValidEmail(correoElectronico)) {
                // Redireccionar a "email-verification.php"
                localStorage.setItem("correoElectronico", correoElectronico);
                window.location.href = "{{ route('shop.view.email.verification') }}";
            } else {
                // Mostrar un mensaje de error en el div con el id "result"
            }
        });


        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
    
</script>
