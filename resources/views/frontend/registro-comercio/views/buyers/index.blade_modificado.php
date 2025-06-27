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
            href="{{ static_asset('assets/registrocomercio/registro-form/assets/css/theme-2.css') }}">

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

        <style>
            /*social icon colored*/
                    ul.social a {
                        display: inline-block;
                        width: 36px;
                        height: 36px;
                        border-radius: 50em;
                        line-height: 43px;
                        text-align: center;
                        font-size: 18px;
                        background-color: #d5d5df;
                    }
                    ul.social-md a {
                        display: inline-block;
                        width: 20px;
                        height: 20px;
                        border-radius: 50em;
                        line-height: 22px;
                        text-align: center;
                        font-size: 13px;
                        background-color: #d5d5df;
                    }
                    ul.social a:hover {
                        -webkit-transform: translateY(-3px);
                        transform: translateY(-3px);
                    }
                    ul.social i {
                        color: #171727;
                        font-size: 24px;
                        font-weight: 700;
                    }
                    ul.social a:hover i{
                        color: #fff;
                    }

                    ul.social.colored i {
                        color: #fff;
                    }
                    ul.social [class*="facebook"]:hover,
                    ul.social.colored [class*="facebook"] {
                        background-color: #3b5998;
                    }
                    ul.social [class*="twitter"]:hover,
                    ul.social.colored [class*="twitter"] {
                        background-color: #1da1f2;
                    }
                    ul.social [class*="google"]:hover,
                    ul.social.colored [class*="google"] {
                        background-color: #e62833;
                    }
                    ul.social.colored [class*="apple"] {
                        background-color: #141414;
                    }
                    ul.social [class*="youtube"]:hover,
                    ul.social.colored [class*="youtube"] {
                        background-color: #ff0000;
                    }
                    ul.social [class*="instagram"]:hover,
                    ul.social.colored [class*="instagram"] {
                        background-color: #bd32a2;
                    }
                    ul.social [class*="tripadvisor"]:hover,
                    ul.social.colored [class*="tripadvisor"] {
                        background-color: #32da9d;
                    }
                    ul.social [class*="linkedin"]:hover,
                    ul.social.colored [class*="linkedin"] {
                        background-color: #0070ac;
                    }

                    ul.colored-light [class*="facebook"] {
                        background-color: #c2d9fd;
                    }
                    ul.colored-light [class*="twitter"] {
                        background-color: #ace5f3;
                    }
                    ul.colored-light [class*="google"] {
                        background-color: #ffcbcc;
                    }
                    ul.colored-light [class*="youtube"] {
                        background-color: #ffcbcc;
                    }
                    ul.colored-light [class*="instagram"] {
                        background-color: #f7e2f3;
                    }

                    ul.colored-light [class*="facebook"]:hover {
                        background-color: #3b5998;
                    }
                    ul.colored-light [class*="twitter"]:hover {
                        background-color: #1da1f2;
                    }
                    ul.colored-light [class*="google"]:hover {
                        background-color: #ff0000;
                    }
                    ul.colored-light [class*="youtube"]:hover {
                        background-color: #ff0000;
                    }
                    ul.colored-light [class*="instagram"]:hover {
                        background-color: #bd32a2;
                    }

                    ul.colored-light [class*="facebook"] i {
                        color: #3b5998;
                    }
                    ul.colored-light [class*="twitter"] i {
                        color: #1da1f2;
                    }
                    ul.colored-light [class*="google"] i {
                        color: #ff0000;
                    }
                    ul.colored-light [class*="youtube"] i {
                        color: #ff0000;
                    }
                    ul.colored-light [class*="instagram"] i {
                        color: #bd32a2;
                    }
                    ul.colored-light a:hover i{
                        color: #ffffff;
                    }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src='https://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key={{ env("GOOGLE_MAPS_API_KEY") }}'></script>
    

    </head>

    <body>

        <div class="ugf-wrapper theme-bg">
            <div class="ugf-content-block">
                <div class="logo">
                </div>
                <div class="container-md">
                    <div class="row">
                        <div class="col-lg-7 offset-lg-5 p-sm-0">
                            <div class="ugf-content pt150">
                                <a href="{{ route('shop.view.account.type') }}" class="prev-page margin-pd"> <i class="las la-arrow-left" ></i> Volver Atrás</a>
                                <h3 class="margin-bc">Bienvenido!<span style="font-size: 2.3rem;">Registra tu Cuenta</span></h3>
                                
                                <p>Puedes registrarte llegando los siguientes pasos o a traves de una red social </p>

                                <div class="form-group" style="">
                                    <ul class="list-inline social colored mb-4">
                                  
                                        <li class="list-inline-item">
                                            <a href="{{-- route('social.login', ['provider' => 'facebook']) --}}" class="facebook">
                                                <i class="lab la-facebook-f" style="position: relative;top: 5px;"></i>
                                            </a>
                                        </li>
                                   
                                        <li class="list-inline-item">
                                            <a href="{{-- route('social.login', ['provider' => 'google']) --}}" class="google">
                                                <i class="lab la-google" style="position: relative;top: 5px;"></i>
                                            </a>
                                        </li>
                                  
                                        <li class="list-inline-item">
                                            <a href="{{-- route('social.login', ['provider' => 'twitter']) --}}" class="twitter">
                                                <i class="lab la-twitter" style="position: relative;top: 5px;"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>



                                {{-- formulario con correo --}}

                                <form class="form-flex email-form was-validated" id="emailGroup" method="POST" action="{{ route('register.buyer.store') }}">
                                    @csrf
                                    
                                    <div class="container">
                                        <div class="form-row col-md-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                            <div class="form-group mb-4">
                                                <label for="name_user" id="nameLabel">Nombre y Apellido</label>
                                                <input type="name_user" name="name_user" id="name_user" placeholder="Nombre y Apellio" class="form-control name_user @error('name_user') is-invalid @enderror" value="{{ old('name_user') }}" required>
                                               
                                                @error('name_user')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            @if (addon_is_activated('otp_system'))
                                            <div class="form-group phone-form-group mb-1">
                                                <label for="phone" class="fs-12 fw-700 text-soft-dark">{{  translate('Phone') }}</label>
                                                <input type="tel" id="phone-code" class="form-control rounded-lapieza-8 {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="Ej: 809 123 4567" name="phone" autocomplete="off" required>
                                            </div>

                                            <input type="hidden" name="country_code" value="">

                                            <div class="form-group email-form-group mb-1 d-none">
                                                <label for="email" class="fs-12 fw-700 text-soft-dark">{{  translate('Email') }}</label>
                                                <input type="email" class="form-control rounded-lapieza-8 {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email"  autocomplete="off" required>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group text-right" style="cursor: pointer;">
                                                <a class="mb-3" style="text-decoration: underline;" onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></a>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="email" class="fs-12 fw-700 text-soft-dark">{{  translate('Email') }}</label> 
                                                <input type="email" class="form-control rounded-lapieza-8 {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" required>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                      </div>
                                    </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                            <div class="form-group mb-4">
                                                <label for="password">Contraseña</label>
                                                <input type="password" name="password" id="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" id="inputPassword" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-4">
                                                <label for="password_confirmation">Repetir Contraseña</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation"  placeholder="{{ __('Confirm Password') }}" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                            <div class="form-group mb-60">                                                
                                                <label for="address" id="addressLabel">Buscar dirección o selecciona en el Mapa</label>
                                                <input class="form-control" placeholder="Introduce la Ubicación" id="searchTextField" type="text" size="50" name="address" value="{{ old('address') }}" required>
                                                @if ($errors->has('address'))
                                                    <p style="font-size: 80%;" class="text-danger">{{ $errors->first('address') }}</p>
                                                @endif
                                                <div class="row">
                                                    <div class="col" style="display: none;">
                                                        <label for="latitude">Latitude</label>
                                                        <input name="latitude" class="form-control MapLat" value="{{ old('latitude') }}" type="text" placeholder="Latitude">
                                                    </div>
                                                    <div class="col" style="display: none;">
                                                        <label for="longitude">Longitude</label>
                                                        <input name="longitude" class="form-control MapLon" value="{{ old('longitude') }}" type="text" placeholder="Longitude">
                                                    </div>
                                                </div>
                                                <!-- seccion mapa -->
                                                <div id="map_canvas" class="map_canvas"> </div>
                                                <style>
                                                    #map_canvas{
                                                        height: 350px;
                                                        width: 686px;
                                                        margin-top: 2%;
                                                        margin-left: 4px;
                                                        }
                                                    @media all and (max-width: 991px) {

                                                    #map_canvas{
                                                        height: 354px !important;
                                                        width: 278px !important;
                                                        margin-top: 0.8em !important;
                                                        position: relative !important;
                                                        overflow: hidden !important;
                                                        }
                                                    }
                                                </style>           
                                                    </div>
                                                    <br>
                                                        </div>
                                                    </div>
                                            <div class="form-group mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                                                    <label for="terms" style="cursor: pointer;">
                                                        Estoy de acuerdo con la <a href="{{ route('terms') }}" target="_blank">Términos y Condiciones</a>, <a href="{{ route('returnpolicy') }}" target="_blank">Política de Devoluciones</a> & <a href="{{ route('privacypolicy') }}" target="_blank">Política de Privacidad</a>
                                                    </label>
                                                </div>
                                                @if ($errors->has('terms'))
                                                    <p style="font-size: 80%;" class="text-danger">{{ $errors->first('terms') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group mb-4">
                                                
                                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                                @if ($errors->has('g-recaptcha-response'))
                                                <p style="font-size: 80%;" class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                                                @endif
                                            </div>

                                    
                                            <div class="form-group mb-12 d-flex">
                                                <button id="" class="btn btn-primary btn-lg btn-block"><span>Iniciemos</span> <i class="las la-arrow-right"></i></button>
                                            </div> 
                                        </div>
                                    </div>
                                    
                                </form>

                                {{-- formulario con numero de telefono --}}
                                 
                                <form class="form-flex email-form" id="phoneGroup">
                                    
                                    <div class="form-row col-md-9" >

                                        <div class="form-group mb-4">
                                            <label for="name_user" id="nameLabel">Nombre y apellido</label>
                                            <input type="name_user" name="name_user" id="name_user" placeholder="nombre y apellio" class="form-control" required>     
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="inputMail" id="phoneLabel">Teléfono</label>
                                            <input type="number" placeholder="Teléfono" class="form-control phone" required>
                                            <a id="emailLink" style="text-decoration: underline;" href="#">Utilice el correo electrónico en su lugar</a>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="inputPassword" id="password2">Contraseña</label>
                                            <input type="password" placeholder="Contraseña"
                                                class="form-control" id="inputPassword" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="inputConfirmPassword" id="repitpassword2">Repetir Contraseña</label>
                                            <input type="password" placeholder="Repetir Contraseña"
                                                class="form-control" id="inputConfirmPassword" required>
                                        </div>

                                        <div class="form-group mb-12 d-flex">
                                            <button id="" class="btn btn-primary btn-lg btn-block"><span>Iniciemos</span> <i class="las la-arrow-right"></i></button>
                                        </div> 

                                    </div>
                                    
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
            <div class="ugf-sidebar flex-bottom ugf-sidebar-bg-lapieza sidebar-steps">
                <div class="logo">
                    <a href="">
                        <img class="loguito" src="/public/assets/img/logo_blanco.png" alt="">
                       <img class="dark-logo" src="/public/assets/img/logo_black.png" alt="">
                    </a>
                </div>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
 
    $(document).ready(function() {
        // Ocultar el formulario de teléfono por defecto
        $("#phoneGroup").hide();

        // Mostrar el formulario de correo electrónico al cargar la página
        $("#emailGroup").show();

        // Manejar el evento de clic en el enlace "phoneLink"
        $("#phoneLink").click(function() {
            $("#phoneGroup").show();
            $("#emailGroup").hide();
        });

        // Manejar el evento de clic en el enlace "emailLink"
        $("#emailLink").click(function() {
            $("#phoneGroup").hide();
            $("#emailGroup").show();
        });
    });



</script>
<script>
    $(function () {
        var lat = 18.7009047,
            lng = -70.1654584,
            latlng = new google.maps.LatLng(lat, lng),
            image = 'https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';

        //zoomControl: true,
        //zoomControlOptions: google.maps.ZoomControlStyle.LARGE,

        var mapOptions = {
            center: new google.maps.LatLng(lat, lng),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl: true,
            panControlOptions: {
                position: google.maps.ControlPosition.TOP_RIGHT
            },
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.TOP_left
            }
        },
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions),
            marker = new google.maps.Marker({
                position: latlng,
                map: map,
                icon: image
            });

        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input, {
            types: ["geocode"]
        });

        autocomplete.bindTo('bounds', map);
        var infowindow = new google.maps.InfoWindow();

        google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
            infowindow.close();
            var place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            moveMarker(place.name, place.geometry.location);
            $('.MapLat').val(place.geometry.location.lat());
            $('.MapLon').val(place.geometry.location.lng());
        });
        google.maps.event.addListener(map, 'click', function (event) {
            $('.MapLat').val(event.latLng.lat());
            $('.MapLon').val(event.latLng.lng());
            infowindow.close();
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        "latLng":event.latLng
                    }, function (results, status) {
                        console.log(results, status);
                        if (status == google.maps.GeocoderStatus.OK) {
                            console.log(results);
                            var lat = results[0].geometry.location.lat(),
                                lng = results[0].geometry.location.lng(),
                                placeName = results[0].address_components[0].long_name,
                                latlng = new google.maps.LatLng(lat, lng);

                            moveMarker(placeName, latlng);
                            $("#searchTextField").val(results[0].formatted_address);
                            $("input[name='address']").val(results[0].formatted_address);
                            $("input[name='latitude']").val(latlng.lat());
                            $("input[name='longitude']").val(latlng.lng());   
                        }
                    });
        });
       
        function moveMarker(placeName, latlng) {
            marker.setIcon(image);
            marker.setPosition(latlng);
            infowindow.setContent(placeName);
            //infowindow.open(map, marker);
        }
    });
</script>
<script>
    var isPhoneShown = true,
    countryData = window.intlTelInputGlobals.getCountryData(),
    input = document.querySelector("#phone-code");
    
    for (var i = 0; i < countryData.length; i++) {
    var country = countryData[i];
    if(country.iso2 == 'bd'){
        country.dialCode = '88';
    }
    }
    
    var iti = intlTelInput(input, {
    separateDialCode: true,
    utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
    onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
        if(selectedCountryData.iso2 == 'bd'){
            return "01xxxxxxxxx";
        }
        return selectedCountryPlaceholder;
    }
    });
    
    var country = iti.getSelectedCountryData();
    $('input[name=country_code]').val(country.dialCode);
    
    input.addEventListener("countrychange", function(e) {
    // var currentMask = e.currentTarget.placeholder;
    
    var country = iti.getSelectedCountryData();
    $('input[name=country_code]').val(country.dialCode);
    
    });
    
    function toggleEmailPhone(el){
    if(isPhoneShown){
        $('.phone-form-group').addClass('d-none');
        $('.email-form-group').removeClass('d-none');
        isPhoneShown = false;
        $(el).html('<i>*{{ translate('Use Phone Number Instead') }}</i>');
    }
    else{
        $('.phone-form-group').removeClass('d-none');
        $('.email-form-group').addClass('d-none');
        isPhoneShown = true;
        $(el).html('*{{ translate('Use Email Instead') }}');
    }
    }
    </script>



