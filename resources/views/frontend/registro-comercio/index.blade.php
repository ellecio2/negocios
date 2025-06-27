<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Pieza.DO | Todo lo que buscas!</title>

    <!-- Font Awesome Style Link -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/css/icofont.min.css') }}">
    <!-- Owl-Carousel Style Link -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/css/owl.carousel.min.css') }}">
    <!-- Bootstrap Style Link -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/css/bootstrap.min.css') }}">
    <!-- Aos Style Link -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/css/aos.css') }}">
    <!-- Custom Style Link -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/css/style.css') }}">
    <!-- Responsive Style Link -->
    <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/css/responsive.css') }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ static_asset('assets/registrocomercio/img/favicon.png') }}" type="image/x-icon">

    {{-- <link rel="stylesheet" href="{{ static_asset('assets/registrocomercio/style.css') }}"> --}}


</head>

<body>

    <!-- Page-wrapper-Start -->
    <div class="page_wrapper">

        <!-- Preloader -->
        <div id="preloader">
            <div id="loader"></div>
        </div>

        <!-- Header Start -->


        <!-- Banner-Section-Start -->
        <section class="banner_section">
            <!-- hero bg -->
            <div class="hero_bg"> <img src="{{ static_asset('assets/registrocomercio/images/hero-bg.png') }}"
                    alt="image"> </div>
            <!-- container start -->
            <div class="container">
                <!-- row start -->
                <div class="row">
                    <div class="col-lg-6 col-md-12" data-aos="fade-right" data-aos-duration="1500">
                        <!-- banner text -->
                        <div class="banner_text">
                            <!-- h1 -->
                            <h1> Bienvenido a <br><span style="color: black;">La Pieza.<span
                                        style="color: #E63108;">DO</span></span></h1>
                            <!-- p -->
                            <p style="color: black;"><span style="color: #003b73;">Compra, Vende y adquiere tus piezas
                                    con tan solo un clic.</span>Tenemos las herramientas para que tu
                                negocio crezca.
                            </p>
                        </div>

                        <div class="trial_box">
                            <!-- form -->
                            <form action="" data-aos="fade-in" data-aos-duration="1500" data-aos-delay="100">
                                <!-- <div class="form-group">
                      <input type="email" class="form-control" placeholder="Enter your email">
                  </div> -->
                                <div class="form-group">
                                    <a href="{{ route('shop.view.account.type') }}" class="btn">COMIENZA AQUI!</a>
                                </div>
                            </form>
                        </div>

                        <!-- list -->
                        <div class="trial_box_list">
                            <ul>
                                <li><i class="icofont-check-circled"></i> Registrate Gratis!</li>

                            </ul>
                        </div>


                    </div>

                    <!-- banner images start -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-in" data-aos-duration="1500">
                        <div class="banner_images image_box1">
                            <span class="banner_image1"> <img class="moving_position_animatin"
                                    src="{{ static_asset('assets/registrocomercio/images/bannerimage1.png') }}"
                                    alt="image"> </span>
                            <span class="banner_image2"> <img class="moving_animation"
                                    src="{{ static_asset('assets/registrocomercio/images/bannerimage2.png') }} "
                                    alt="image"> </span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6" data-aos="fade-in" data-aos-duration="1500">
                        <div class="banner_images image_box2">
                            <span class="banner_image3"> <img class="moving_animation"
                                    src="{{ static_asset('assets/registrocomercio/images/bannerimage3.png') }} "
                                    alt="image"> </span>
                            <span class="banner_image4"> <img class="moving_position_animatin"
                                    src="{{ static_asset('assets/registrocomercio/images/bannerimage4.png') }} "
                                    alt="image"> </span>
                        </div>
                    </div>
                    <!-- banner slides end -->

                </div>
                <!-- row end -->
            </div>
            <!-- container end -->
        </section>
        <!-- Banner-Section-end -->

        <!-- Features-Section-Start -->
        <section class="row_am features_section" id="features">
            <!-- section bg -->
            <div class="feature_section_bg"> <img
                    src="{{ static_asset('assets/registrocomercio/images/hero-red-bg.png') }} " alt="image"> </div>
            <!-- container start -->
            <div class="container">
                <div class="features_inner">

                    <!-- feature image -->


                    <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
                        <!-- h2 -->
                        <h2 style="color:#003b73"><span style="color: black;">Lo</span style="color: #003b73;"> que hace
                            de <span style="color: black;">La Pieza.<span style="color: #E63108;">DO</span></span><br>
                            una opción única!</h2>
                        <!-- p -->
                        <p>Maneja tu inventario, recibe pagos, factura y vende en linea<br> de la manera segura y
                            efectiva.</p>
                    </div>

                    <!-- story -->
                    <div class="features_block">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="feature_box" data-aos="fade-up" data-aos-duration="1500">
                                    <div class="image">
                                        <img src="{{ static_asset('assets/registrocomercio/images/secure_data.png') }} "
                                            alt="image">
                                    </div>
                                    <div class="text">
                                        <h4>Datos Seguros</h4>
                                        <p>Contamos con los estándares internacionales de seguridad y SSL.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="feature_box" data-aos="fade-up" data-aos-duration="1700">
                                    <div class="image">
                                        <img src="{{ static_asset('assets/registrocomercio/images/functional.png') }} "
                                            alt="image">
                                    </div>
                                    <div class="text">
                                        <h4>Automatización de Servicios</h4>
                                        <p>Simple para vender, simple para ganar, Todo automatizado.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="feature_box" data-aos="fade-up" data-aos-duration="1900">
                                    <div class="image">
                                        <img src="{{ static_asset('assets/registrocomercio/images/live-chat.png') }} "
                                            alt="image">
                                    </div>
                                    <div class="text">
                                        <h4>Inteligencia Artificial</h4>
                                        <p>Para conocer el comportamiento de compra de tus clientes y ofrecerles lo
                                            mejor.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="feature_box" data-aos="fade-up" data-aos-duration="1900">
                                    <div class="image">
                                        <img src="{{ static_asset('assets/registrocomercio/images/support.png') }} "
                                            alt="image">
                                    </div>
                                    <div class="text">
                                        <h4>Tu inventario en linea</h4>
                                        <p>Te ayudamos a organizar tu inventario, ponerlo en línea y a vender!.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <!-- container end -->
        </section>
        <!-- Features-Section-end -->

        <!-- About-App-Section-Start -->
        <section class="row_am about_app_section">
            <!-- container start -->
            <div class="container">
                <!-- row start -->
                <div class="row">
                    <div class="col-lg-6">

                        <!-- about images -->
                        <!-- <div class="about_img" data-aos="fade-in" data-aos-duration="1500">
              <div class="frame_img">
                <img class="moving_position_animatin" src="{{ static_asset('assets/registrocomercio/images/about-frame.png') }} " alt="image" >
              </div>
              <div class="screen_img">
                <img class="moving_animation" src="{{ static_asset('assets/registrocomercio/images/about-screen.png') }} " alt="image" >
              </div>
            </div> -->
                    </div>
                    <div class="col-lg-6">

                        <!-- about text -->

                    </div>
                </div>
                <!-- row end -->
            </div>
            <!-- container end -->
        </section>
        <!-- About-App-Section-end -->

        <!-- ModernUI-Section-Start -->
        <section class="row_am modern_ui_section">
            <!-- section bg -->
            <div class="modernui_section_bg"> <img
                    src="{{ static_asset('assets/registrocomercio/images/hero-bg.png') }} " alt="image"> </div>
            <!-- container start -->
            <div class="container">
                <!-- row start -->
                <div class="row">
                    <div class="col-lg-6">
                        <!-- UI content -->
                        <div class="ui_text">
                            <div class="section_title" data-aos="fade-up" data-aos-duration="1500"
                                data-aos-delay="100">
                                <h2>Diseño moderno<br> <span style="color: #003b73">y pensado para ti!</span></h2>
                                <p>
                                    Nuestra misión es que tengas una herramienta que te permita realizar tus ventas de
                                    forma rápida y segura y súper fácil de usar.
                                </p>
                            </div>
                            <ul class="design_block">
                                <li data-aos="fade-up" data-aos-duration="1500">
                                    <h4>Fácil Acceso</h4>
                                    <p>Solo tienes que poner tu email, teléfono, facebook o instagram, tu contraseña y
                                        ¡listo!.</p>
                                </li>
                                <li data-aos="fade-up" data-aos-duration="1500">
                                    <h4>Sincronice con su sistema en la computadora</h4>
                                    <p>Cuando estés en línea, todo se sincroniza en tu computadora, para que puedas
                                        acceder a tu negocio desde cualquier lugar.</p>
                                </li>
                                <li data-aos="fade-up" data-aos-duration="1500">
                                    <h4>Tu inventario a la mano</h4>
                                    <p>Tenemos la gestión de inventario para ti desde tu móvil o computadora, todo con
                                        un solo clic.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- UI Image -->
                        <div class="ui_images" data-aos="fade-in" data-aos-duration="1500">
                            <div class="left_img">
                                <img class="moving_position_animatin"
                                    src="{{ static_asset('assets/registrocomercio/images/modern01.png') }} "
                                    alt="image">
                            </div>
                            <!-- UI Image -->
                            <div class="right_img">
                                <img class="moving_position_animatin"
                                    src="{{ static_asset('assets/registrocomercio/images/shield_icon.png') }} "
                                    alt="image">
                                <img class="moving_position_animatin"
                                    src="{{ static_asset('assets/registrocomercio/images/modern02.png') }} "
                                    alt="image">
                                <img class="moving_position_animatin"
                                    src="{{ static_asset('assets/registrocomercio/images/modern03.png') }} "
                                    alt="image">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- row end -->
            </div>
            <!-- container end -->
        </section>
        <!-- ModernUI-Section-end -->

        <!-- How-It-Workes-Section-Start -->
        <section class="row_am how_it_works" id="how_it_work">
            <!-- section bg -->
            <div class="how_section_bg"> <img
                    src="{{ static_asset('assets/registrocomercio/images/hero-red-bg.png') }} " alt="image">
            </div>
            <!-- container start -->
            <div class="container">
                <div class="how_it_inner">
                    <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
                        <!-- h2 -->
                        <h2><span style="color: #003b73">Cómo funciona <span style="color: black;">La Pieza.<span
                                        style="color: #E63108;">DO</span></span> en 3 sencillos pasos</h2>
                        <!-- p -->
                        <p>Usar <span style="color: black;">La Pieza.<span style="color: #E63108;">DO</span> es súper
                                fácil, solo sigue estos pasos.</p>
                    </div>
                    <div class="step_block">
                        <!-- UL -->
                        <ul>
                            <!-- step -->
                            <li>
                                <div class="step_text" data-aos="fade-right" data-aos-duration="1500">
                                    <h4>Descarga la app</h4>
                                    <div class="app_icon">
                                        <a href="#"><i class="icofont-brand-android-robot"></i></a>
                                        <a href="#"><i class="icofont-brand-apple"></i></a>

                                    </div>
                                    <p>Descarga la App en Google Play o Play Store</p>
                                </div>
                                <div class="step_number number1">
                                    <h3>01</h3>
                                </div>
                                <div class="step_img" data-aos="fade-left" data-aos-duration="1500">
                                    <img src="{{ static_asset('assets/registrocomercio/images/download_app.jpg') }} "
                                        alt="image">
                                </div>
                            </li>

                            <!-- step -->
                            <li>
                                <div class="step_text" data-aos="fade-left" data-aos-duration="1500">
                                    <h4>Registrate y crea una cuenta</h4>
                                    <span>Totalmente Gratis!</span>
                                    <p>Inicia sesión en la aplicación o en tu computadora. Una cuenta para todos los
                                        dispositivos.</p>
                                </div>
                                <div class="step_number number2">
                                    <h3>02</h3>
                                </div>
                                <div class="step_img" data-aos="fade-right" data-aos-duration="1500">
                                    <img src="{{ static_asset('assets/registrocomercio/images/create_account.jpg') }} "
                                        alt="image">
                                </div>
                            </li>

                            <!-- step -->
                            <li>
                                <div class="step_text" data-aos="fade-right" data-aos-duration="1500">
                                    <h4>Todo listo disfruta la app!</h4>
                                    <span>Si tienes alguna pregunta visita <a href="#">FAQs.</a></span>
                                    <p>Obten la experiencia más increíble. <br> Explora y comparte la app.</p>
                                </div>
                                <div class="step_number number3">
                                    <h3>03</h3>
                                </div>
                                <div class="step_img" data-aos="fade-left" data-aos-duration="1500">
                                    <img src="{{ static_asset('assets/registrocomercio/images/enjoy_app.jpg') }} "
                                        alt="image">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- container end -->
        </section>
        <!-- FAQ-Section start -->
        <section class="row_am faq_section">
            <!-- section bg -->
            <div class="faq_bg"> <img src="{{ static_asset('assets/registrocomercio/images/hero-bg.png') }} "
                    alt="image"> </div>
            <!-- container start -->
            <div class="container">
                <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
                    <!-- h2 -->
                    <h2><span>FAQ</span> - Preguntas Frecuentes</h2>
                    <!-- p -->
                    <p>Aqui tenemos todas las respuestas a tus preguntas con respecto al uso de <span
                            style="color: black;">La Pieza.<span style="color: #E63108;">DO</span></span>. Aquí podrás
                        encontrar respuestas rápidas para que puedas utilizar la aplicación.</p>
                </div>
                <!-- faq data -->
                <div class="faq_panel">
                    <div class="accordion" id="accordionExample">
                        <div class="card" data-aos="fade-up" data-aos-duration="1500">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link active" data-toggle="collapse"
                                        data-target="#collapseOne">
                                        <i class="icon_faq icofont-plus"></i></i> Cómo puedo subir mi
                                        inventario?</button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
                                        Ipsum has. been the
                                        industrys standard dummy text ever since the when an unknown printer took a
                                        galley of type and
                                        scrambled it to make a type specimen book. It has survived not only five cen
                                        turies but also the
                                        leap into electronic typesetting, remaining essentially unchanged.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card" data-aos="fade-up" data-aos-duration="1500">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#collapseTwo"><i class="icon_faq icofont-plus"></i></i> Cómo
                                        configuro mi cuenta ?</button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
                                        Ipsum has. been the
                                        industrys standard dummy text ever since the when an unknown printer took a
                                        galley of type and
                                        scrambled it to make a type specimen book. It has survived not only five cen
                                        turies but also the
                                        leap into electronic typesetting, remaining essentially unchanged.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card" data-aos="fade-up" data-aos-duration="1500">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#collapseThree"><i class="icon_faq icofont-plus"></i></i>Cuál es
                                        el proceso para solicitar un reembolso
                                        ?</button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
                                        Ipsum has. been the
                                        industrys standard dummy text ever since the when an unknown printer took a
                                        galley of type and
                                        scrambled it to make a type specimen book. It has survived not only five cen
                                        turies but also the
                                        leap into electronic typesetting, remaining essentially unchanged.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card" data-aos="fade-up" data-aos-duration="1500">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#collapseFour"><i class="icon_faq icofont-plus"></i></i>Como
                                        registro mis productos
                                        ?</button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
                                        Ipsum has. been the
                                        industrys standard dummy text ever since the when an unknown printer took a
                                        galley of type and
                                        scrambled it to make a type specimen book. It has survived not only five cen
                                        turies but also the
                                        leap into electronic typesetting, remaining essentially unchanged.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container end -->
        </section>
        <!-- FAQ-Section end -->

        <section class="row_am free_app_section" id="getstarted">
            <!-- container start -->
            <div class="container">
                <div class="free_app_inner" data-aos="fade-in" data-aos-duration="1500" data-aos-delay="100">
                    <!-- row start -->
                    <div class="row">
                        <!-- content -->
                        <div class="col-md-6">
                            <div class="free_text">
                                <div class="section_title">
                                    <h2>Descargala grátis para Android y Apple</h2>
                                    <p>Descarga nuestra app y sácale provecho a tu negocio en línea. Registrate y
                                        descubre lo nuevo que tenemos para ti.</p>
                                </div>
                                <ul class="app_btn">
                                    <li>
                                        <a href="#">
                                            <img src="{{ static_asset('assets/registrocomercio/images/appstore_blue.png') }} "
                                                alt="image">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ static_asset('assets/registrocomercio/images/googleplay_blue.png') }} "
                                                alt="image">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- images -->
                        <div class="col-md-6">
                            <div class="free_img">
                                <img src="{{ static_asset('assets/registrocomercio/images/download-screen01.png') }} "
                                    alt="image">
                                <img class="mobile_mockup"
                                    src="{{ static_asset('assets/registrocomercio/images/download-screen02.png') }} "
                                    alt="image">
                            </div>
                        </div>
                    </div>
                    <!-- row end -->
                </div>
            </div>
            <!-- container end -->
        </section>
        <!-- Download-Free-App-section-end  -->

        <!-- Story-Section-Start -->

        <footer>
            <!-- go top button -->
            <div class="go_top">
                <span><img src="{{ static_asset('assets/registrocomercio/images/go_top.png') }} "
                        alt="image"></span>
            </div>
        </footer>
        <!-- Footer-Section end -->

        <!-- VIDEO MODAL -->


        <div class="purple_backdrop"></div>

    </div>
    <!-- Page-wrapper-End -->

    <!-- Jquery-js-Link -->
    <script src="{{ static_asset('assets/registrocomercio/js/jquery.js') }}"></script>
    <!-- owl-js-Link -->
    <script src="{{ static_asset('assets/registrocomercio/js/owl.carousel.min.js') }}"></script>
    <!-- bootstrap-js-Link -->
    <script src="{{ static_asset('assets/registrocomercio/js/bootstrap.min.js') }}"></script>
    <!-- aos-js-Link -->
    <script src="{{ static_asset('assets/registrocomercio/js/aos.js') }}"></script>
    <!-- main-js-Link -->
    <script src="{{ static_asset('assets/registrocomercio/js/main.js') }}"></script>



</body>

</html>
