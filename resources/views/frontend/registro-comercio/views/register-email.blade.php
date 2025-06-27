<html lang="en">



    <head>

        <!-- Required meta tags -->

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



        <meta name="csrf-token" content="{{ csrf_token() }}">



        <title>La Pieza.DO | Registro</title>



        <!-- Bootstrap CSS -->

        <link rel="stylesheet"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/bootstrap.min.css') }}">



        <!-- External Css -->

        <link rel="stylesheet"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/line-awesome.min.css') }}">

        <link rel="stylesheet"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/owl.carousel.min.css') }}" />

        <link rel="stylesheet"

            href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">

        <link rel="stylesheet"

            href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <!-- Custom Css -->

        <link rel="stylesheet" type="text/css"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/main.css') }}">

        <link rel="stylesheet" type="text/css"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/theme-1.css') }}">



        <!-- Fonts -->

        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">





        <!-- Favicon -->

        <link rel="icon"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/favicon.png') }}">

        <link rel="apple-touch-icon"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/apple-touch-icon.png') }}">

        <link rel="apple-touch-icon" sizes="72x72"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-72x72.png') }}">

        <link rel="apple-touch-icon" sizes="114x114"

            href="{{ static_asset('assets/registrocomercio/registro-form/assets/images/icon-114x114.png') }}">









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

                                <a href="{{ route('shop.view.account.type') }}" class="prev-page"> <i

                                    class="las la-arrow-left"></i> Atrás</a>

                                <h2>Vamos! <span>Únete a nuestra plataforma</span></h2>

                                <p>Introduce un correo electrónico válido para completar algunos sencillos pasos <br>

                                    para registrar tu cuenta.</p>

                                <form class="form-flex email-form">

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

            <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-2 sidebar-steps">

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



    </body>

</html>



<script>

    // Esperar a que el documento esté listo

    $(document).ready(function() {



        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });



        // Asignar evento clic al botón

        var btnSubmit = $("#btnSubmit");

        // Asignar evento clic al botón

        btnSubmit.click(function(event) {

            event.preventDefault(); // Evitar el envío del formulario por defecto



            // Obtener el valor del campo de correo electrónico

            var correoElectronico = $("#inputMail").val();



            // Validar si el campo de correo electrónico contiene un valor válido de correo

            if (isValidEmail(correoElectronico)) {

                // Hacer la solicitud AJAX usando jQuery

                $.ajax({

                    url: "{{ route('api.user.register.email') }}", // URL de la API

                    method: "POST",

                    data: {

                        email: correoElectronico

                    }, // Datos a enviar en la solicitud POST

                    dataType: "json", // Tipo de datos esperados en la respuesta

                    success: function(response) {

                        // Éxito en la solicitud, puedes manejar la respuesta aquí

                        console.log(response);

                        localStorage.setItem("correoElectronico", correoElectronico);

                        window.location.href =

                            "{{ route('shop.view.email.verification') }}";

                    },

                    error: function(error) {

                        // Error en la solicitud, puedes manejar el error aquí

                        console.error(error);

                    }

                });

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

