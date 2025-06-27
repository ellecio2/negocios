<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Pieza.DO | Registro de Comprador</title>
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
    <link rel="shortcut icon" href="{{ static_asset('assets/registrocomercio/images/favicon.png') }}"
          type="image/x-icon">
</head>

<body class="bg-movil">
<!-- Page-wrapper-Start -->
<div class="page_wrapper">
    <!-- Preloader -->
    <div id="preloader">
        <div id="loader"></div>
    </div>

    <!-- Banner-Section-Start -->
    <section class="banner_section">
        <!-- hero bg -->
        <div class="hero_bg"><img src="{{ static_asset('assets/registrocomercio/images/hero-bg.png') }}"
                                  alt="image"></div>
        <!-- container start -->
        <div class="container">
            <!-- row start -->
            <div class="row">
                <div class="col-lg-6 col-md-12" data-aos="fade-right" data-aos-duration="1500">
                    <!-- banner text -->
                    <div class="banner_text">
                        <!-- h1 -->
                        <h1> Hola! <br>
                            <span style="color: #E63118;">Comprador</span>
                        </h1>
                        <!-- p -->
                        <p style="color: black;"><span style="color: #003b73;">Compra y recibe todas tus
                                    piezas en la puerta de tu casa.<br><b>Nosotros nos encargamos del resto!.</b>
                                </span>
                        </p>
                    </div>
                    <div class="trial_box">
                        <!-- form -->
                        <form action="" data-aos="fade-in" data-aos-duration="1500"
                              data-aos-delay="100">
                            <!-- <div class="form-group">
          <input type="email" class="form-control" placeholder="Enter your email">
      </div> -->
                            <div class="form-group">
                                <a href="{{ route('register.buyer.index') }}" class="btn">COMIENZA
                                    AQUI!
                                </a>
                            </div>
                        </form>
                    </div>
                    <!-- list -->
                    <div class="trial_box_list">
                        <ul>
                            <li><i class="icofont-arrow-left"></i>
                                <a href="{{ route('user.registration') }}"
                                   class="prev-page">
                                    Atrás
                                </a>
                            </li>
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
        <div class="feature_section_bg"><img
                src="{{ static_asset('assets/registrocomercio/images/hero-red-bg.png') }} " alt="image">
        </div>
        <!-- container start -->
        <div class="container">
            <div class="features_inner">
                <!-- feature image -->
                <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
                    <!-- h2 -->
                    <h2 style="color:#003b73">
                        <span style="color: black;">Lo</span>
                        que
                        hace
                        de
                        <span style="color: black;">La Pieza.
                                <span style="color: #E63108;">DO</span>
                            </span>
                        <br>
                        una opción única!
                    </h2>
                    <!-- p -->
                    <p>Nuestro sistema en la nube, garantiza una funcionalidad segura y en tiempo real.<br>
                        Compra en linea de manera segura y efectiva.</p>
                </div>
                <!-- story -->
                <div class="features_block">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="feature_box" data-aos="fade-up" data-aos-duration="1500">
                                <div class="image alcentromovil">
                                    <img src="{{ static_asset('assets/registrocomercio/images/azul.png') }} "
                                         alt="image">
                                </div>
                                <div class="text">
                                    <h4>Pago Seguro</h4>
                                    <p>Utilizando la plataforma de pagos
                                        <b>AZUL</b> del Banco Popular.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feature_box" data-aos="fade-up" data-aos-duration="1700">
                                <div class="image alcentromovil">
                                    <img src="{{ static_asset('assets/registrocomercio/images/uber.png') }} "
                                         alt="image">
                                </div>
                                <div class="text">
                                    <h4>Tus Piezas directo a ti!</h4>
                                    <p>Con los servicios de <b>Uber</b> y <b><span
                                                style="color: #003b73;">Delivery La
                                                    Pieza.<span style="color: #E63108;">DO</span></span>
                                        </b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feature_box" data-aos="fade-up" data-aos-duration="1900">
                                <div class="image alcentromovil">
                                    <img src="{{ static_asset('assets/registrocomercio/images/cloud.png') }} "
                                         alt="image">
                                </div>
                                <div class="text">
                                    <h4>Sistema en la Nube</h4>
                                    <p>Tus datos estan <b>100% seguros</b> con nuentro sistema en la nube.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feature_box" data-aos="fade-up" data-aos-duration="1900">
                                <div class="image alcentromovil">
                                    <img src="{{ static_asset('assets/registrocomercio/images/ofertas.png') }} "
                                         alt="image">
                                </div>
                                <div class="text">
                                    <h4>Cientos de Ofertas</h4>
                                    <p>Disfruta de cientos de <b>ofertas </b>, que tenemos para tí!.</p>
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
                <div class="col-lg-6"> <!-- about text --></div>
            </div>
            <!-- row end -->
        </div>
        <!-- container end -->
    </section>
    <!-- About-App-Section-end -->
    
    <!-- ModernUI-Section-Start -->
    <section class="row_am modern_ui_section">
        <!-- section bg -->
        <div class="modernui_section_bg"><img
                src="{{ static_asset('assets/registrocomercio/images/hero-bg.png') }} " alt="image">
        </div>
        <!-- container start -->
        <div class="container">
            <!-- row start -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- UI content -->
                    <div class="ui_text">
                        <div class="section_title" data-aos="fade-up" data-aos-duration="1500"
                             data-aos-delay="100">
                            <h2>Diseño moderno<br>
                                <span style="color: #003b73">y pensado para ti!</span>
                            </h2>
                            <p>
                                Nuestra misión es que tengas la mejor experiencia y puedas realizar tus compras
                                de
                                forma rápida, segura y súper fácil.
                            </p>
                        </div>
                        <ul class="design_block">
                            <li data-aos="fade-up" data-aos-duration="1500">
                                <h4>Fácil Acceso</h4>
                                <p>Registrate con tu <b>email</b> o <b>teléfono</b>, también puedes utilizar
                                    <b>Facebook</b>, <b>Instagram</b> o
                                    <b>Google</b> y ¡listo!.
                                </p>
                            </li>
                            <li data-aos="fade-up" data-aos-duration="1500">
                                <h4>Maneja tus compras, envíos y subastas.</h4>
                                <p>Utilizando nuestras aplicaciones, puedes tener el control total de tus
                                    <b>compras</b>, <b>envíos</b> y <b>subastas</b> desde
                                    cualquier dispositivo o computadora.
                                </p>
                            </li>
                            <li data-aos="fade-up" data-aos-duration="1500">
                                <h4>Enviamos las piezas a tu puerta.</h4>
                                <p>Descubre la eficiente logística y servicios de envíos que tenemos
                                    para ti. <b>¡Facilitamos todo el proceso! </b>Gracias a las plataformas
                                    <b>Uber Moto</b>, <b>MailBoxes</b> y <b><span
                                            style="color: #003b73;">Delivery La
                                                Pieza.<span style="color: #E63108;">DO</span>.</span>
                                    </b><br>
                                    <b>¡Tu comodidad es nuestra prioridad!</b>
                                </p>
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
        <div class="how_section_bg"><img
                src="{{ static_asset('assets/registrocomercio/images/hero-red-bg.png') }} " alt="image">
        </div>
        <!-- container start -->
        <div class="container">
            <div class="how_it_inner">
                <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
                    <!-- h2 -->
                    <h2>
                            <span style="color: #003b73">
                                Cómo funciona
                                <span style="color: black;">La Pieza.
                                    <span style="color: #E63108;">DO</span>
                                </span>
                                en 3 sencillos pasos
                            </span>
                    </h2>
                    <!-- p -->
                    <p>Usar
                        <b>
                                <span style="color: black;">La Pieza.
                                    <span style="color: #E63108;">DO</span>
                                </span>
                        </b>
                        es súper fácil, solo sigue estos pasos.
                    </p>
                </div>
                <div class="step_block">
                    <!-- UL -->
                    <ul>
                        <!-- step -->
                        <li>
                            <div class="step_text" data-aos="fade-right" data-aos-duration="1500">
                                <h4>Descarga la app</h4>
                                <div class="app_icon">
                                    <a href="https://play.google.com/store/apps/details?id=com.gqlabssrl_lapieza.do_clientes"><i
                                            class="icofont-brand-android-robot"></i></a>
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
        <!-- container start -->
        <div class="container">
            <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
                <!-- h2 -->
                <h2>
                    <span>FAQ</span>
                    - Tienes Preguntas?
                </h2>
                <!-- p -->
                <p>Visita nuestro centro de soporte y enterate de todo lo que <b><span style="color: black;">La
                                Pieza.<span style="color: #E63108;">DO</span></span>
                    </b> tiene para ofrecerte.
                </p>
                <div class="trial_box trial_boxcenter">
                    <!-- form -->
                    <form action="" data-aos="fade-in" data-aos-duration="1500" data-aos-delay="100">
                        <!-- <div class="form-group">
              <input type="email" class="form-control" placeholder="Enter your email">
          </div> -->
                        <div class="form-group">
                            <a href="http://soporte.lapieza.do" class="btn">IR AL CENTRO DE
                                SERVICIOS
                            </a>
                        </div>
                    </form>
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
                                <h2>Disponible para Android y Apple</h2>
                                <p>Descarga nuestra app y sácale provecho a tus compras en línea. Registrate y
                                    descubre lo nuevo que tenemos para ti.</p>
                            </div>
                            <ul class="app_btn">
                                <li>
                                    <a href="#">
                                        <img
                                            src="{{ static_asset('assets/registrocomercio/images/appstore_blue.png') }} "
                                            alt="image">
                                    </a>
                                </li>
                                <li class="li_google">
                                    <a href="https://play.google.com/store/apps/details?id=com.gqlabssrl_lapieza.do_clientes">
                                        <img
                                            src="{{ static_asset('assets/registrocomercio/images/googleplay_blue.png') }} "
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
    
    <!-- Footer -->
    <footer>
        <!-- go top button -->
        <div class="go_top">
                <span><img src="{{ static_asset('assets/registrocomercio/images/go_top.png') }} "
                           alt="image"></span>
        </div>
    </footer>
    <!-- Footer-Section end -->
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
<script src="{{ static_asset('assets/registrocomercio/js/main.js') }}" defer></script>

</body>
</html>