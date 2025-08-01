@extends('frontend.layouts.register')
@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/frontend/register.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .tooltip-inner {
            font-size: 14px;
        }
    </style>
@endsection
@section('head-scripts')
    <script
        src='https://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key={{ env('GOOGLE_MAPS_API_KEY') }}'
        defer></script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-80N5BTDM5K"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-80N5BTDM5K');
    </script>
@endsection
@section('register-type', 'Negocio')
@section('register-form')
    {{-- formulario con correo --}}
    <form class="form-flex email-form was-validated" id="registerForm" method="POST"
        action="{{ route('register.business.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="form-row col-md-12">
                <h5 style="padding-bottom: 2%; color: black !important;">Detalles del Representante</h5>
                <div class="border border-black mb-4"></div>

                <div class="row">
                    {{-- Nombre y apellido --}}
                    <div class="col-12 col-lg-6">
                        <div class="form-group mb-4">
                            <label for="name_user" id="nameLabel">Nombre y Apellido</label>
                            <input type="name_user" name="name_user" id="name_user" placeholder="Juan Perez"
                                class="form-input-edit name_user @error('name_user') is-invalid @enderror"
                                value="{{ old('name_user') }}" required>
                            @error('name_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span id="nameError" style="color: #dc3545; font-size: 80%;"></span>
                        </div>
                    </div>

                    {{-- Número Móvil --}}
                    <div class="col-12 col-lg-6">
                        <div class="form-group mb-4" id="phoneField">
                            <label for="phone" id="numeroLabel">Número Móvil</label>
                            <input type="tel" name="phone" id="phone" placeholder="8091234567"
                                class="form-input-edit phone @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" data-user="seller" required>
                            <div id="phoneError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="phoneStatus"></div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Correo electronico --}}
                    <div class="col-12">
                        <div class="form-group mb-4" id="emailField">
                            <label for="email" id="emailLabel">Correo Electrónico</label>
                            <input type="email" name="email" id="email" placeholder="micorreo@micorreo.com"
                                class="form-input-edit email @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" data-user="seller" required>
                            <div id="emailError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="emailStatus"></div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12" style="text-align: left;">
                        {{-- RNC o Cedula Text --}}
                        <div class="form-group">
                            <label class="form-group" for="cedula" id="cedulaLabel">Cédula Representante</label>
                            <!-- <input type="text" class="form-input-edit" id="rnc_input" name="rnc_value" placeholder="Introduce tu Cédula"> -->
                            <input type="text" class="form-input-edit @error('cedula_input') is-invalid @enderror"
                                value="{{ old('cedula_input') }}" id="cedula_input" name="cedula_input"
                                placeholder="Introduce tu número de Cédula" data-user="seller" required>
                            <div id="cedulaError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="cedulaStatus"></div>
                            @error('cedula_input')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    {{-- Registro Mercantil --}}
                    <div class="col-12">
                        <label for="name2" id="nameLabel2" class="fs-5"
                            style="display: block; color: black; font-weight: bold; text-align: start!important; margin-bottom: 10px; margin-top: 2%;">Carga
                            de Documentos</label>
                        <p id="nameLabel" class="fs-14">Sube una Foto de tu Cédula:</p>
                        <div class="row col-12 col-lg-5">
                            <input type="file" name="cedula_photo" id="cedula_photo"
                                class="form-control-lg col-12 col-lg-7" accept="image/*" required>
                            <div class="invalid-feedback d-none" id="invalid-feedback-cedula">La foto de tu cédula es
                                obligatoria
                            </div>
                        </div>
                        <label for="name" id="nameLabel" class="fs-5"
                            style="color: black; text-align: start !important; margin-bottom: 8px; margin-top: 1%;">
                            <span class="colored" id="file-status">(Campo requerido)</span>
                        </label>
                    </div>
                </div>
                <h5 style="padding-bottom: 2%; color: black !important; margin-top: 25px;">
                    Detalles del Negocio
                    <div class="form-check-inline my-2">
                        <input type="checkbox" name="is_physical_person" id="is-physical-person">
                        <label for="is-physical-person" class="font-weight-normal fs-5">¿Eres una persona
                            fisica?</label>
                    </div>
                </h5>

                <div class="border border-black mb-4"></div>

                <div class="row">
                    {{-- Nombre del negocio --}}
                    <div class="col-12 col-lg-6">
                        <div class="form-group mb-4">
                            <label for="name" id="nameLabel">Nombre del Negocio</label>
                            <input type="text" name="name" id="name" placeholder="Tienda Mi Esfuerzo"
                                class="form-input-edit name @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span id="nameNegocioError" style="color: #dc3545; font-size: 90%;"></span>
                        </div>
                    </div>

                    {{-- Tipo de Negocio --}}
                    <div class="col-12 col-lg-6">
                        <div class="form-group mb-4">
                            <label for="categories_id" id="categoryLabel">
                                Que Piezas Vende tu Negocio?
                            </label>
                            <select name="categories_id"
                                class="form-input-edit @error('categories_id') is-invalid @enderror" id="categories_id"
                                required>
                                <option value="" selected>Seleccione el Tipo de Pieza</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                        Piezas de
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span id="categoryError" style="color: #dc3545; font-size: 80%;"></span>
                        </div>
                    </div>
                </div>

                <div class="row mt-2" id="rnc_group">
                    {{-- RNC --}}
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-group" for="rnc" id="rncLabel">RNC:</label>
                            <input type="text" name="rnc_input" id="rnc_input"
                                placeholder="Introduce el RNC de tu Negocio"
                                class="form-input-edit rnc_input @error('rnc_input') is-invalid @enderror" required
                                value="{{ old('rnc_input') }}">
                            <div id="rncError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="rncStatus"></div>
                            @error('rnc_input')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Teléfono de Tienda --}}
                    <div class="col-12 col-lg-6">
                        <div class="form-group mb-4" id="mobileField">
                            <label for="mobile" id="numeroLabel">Teléfono de Tienda</label>
                            <input type="tel" name="telefono_tienda" id="telefono_tienda" placeholder="8091234567"
                                class="form-input-edit mobile " value="{{ old('telefono_tienda') }}">
                            <div id="mobileError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="mobileStatus"></div>
                        </div>
                    </div>

                    {{-- Registro Mercantil --}}
                    <div class="col-lg-12">
                        <div class="form-group mb-2">
                            <label for="name2" id="nameLabel2" class="fs-5"
                                style="display: block; color: black; font-weight: bold; text-align: start!important; margin-bottom: 10px; margin-top: 2%;">Carga
                                de Documentos</label>
                            <p id="nameLabel" class="fs-14">Sube una imagen del Registro Mercantíl del Negocio:</p>
                            <div class="row justify-content-between">
                                <div class="col-12 col-lg-5">
                                    <input type="file" name="registro_mercantil" id="registro_mercantil"
                                        class="form-control-lg" accept="image/*" required>
                                    <label for="name" id="nameLabel" class="fs-5"
                                        style="color: black; text-align: start !important; margin-bottom: 8px; margin-top: 2%;">
                                        <span class="colored" id="file-status-rnc">(Campo requerido)</span>
                                    </label>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <a class="p-2 mb-2" id="tooltip-link" data-bs-toggle="tooltip"
                                        data-bs-title="Nuestra misión es asegurar que tu negocio, cumpla con los requerimientos legales, para garantizar una mejor experiencia de ventas hacia el usuario."
                                        disabled="">¿Porqué Solicitamos Esto?</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    {{-- Contraseña --}}
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" placeholder="Contraseña"
                                class="form-input-edit @error('password') is-invalid @enderror"
                                value="{{ old('password') }}" required>
                            {{-- @error('password')
                            <div class="invalid-feedback">{{ $message }}
                        </div>
                        @enderror --}}
                            <div id="password1Error" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                    {{-- Confirmar Contraseña --}}
                    <div class="col-lg-6 ">
                        <div class="form-group mb-4">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Confirmar Contraseña"
                                class="form-input-edit @error('password_confirmation') is-invalid @enderror" required
                                value="{{ old('password_confirmation') }}">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="passwordError" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-60">
                            <label for="address" id="addressLabel">Buscar dirección o
                                selecciona en el Mapa</label>
                            <input class="form-input-edit" placeholder="Buscar direccion y seleccionar"
                                id="searchTextField" type="text" size="50" required name="address"
                                value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <p style="font-size: 80%;" class="text-danger">{{ $errors->first('address') }}</p>
                            @endif
                            <div class="col-lg-12" style="display: none;">
                                <div class="form-group mb-4">
                                    <input class="form-input-edit" placeholder="Buscar direccion y seleccionar"
                                        id="searchTextField" type="text" size="50" name="address"
                                        value="{{ old('address') }}" readonly required>
                                </div>
                            </div>
                            <span id="addressError" style="color: #dc3545; font-size: 80%;"></span>
                        </div>
                    </div>
                    <div id="map_canvas" class="map_canvas"></div>
                </div>
                <div class="row">
                    <div class="col" style="display: none;">
                        <label for="latitude">Latitude</label>
                        <input name="latitude" class="form-input-edit MapLat" value="{{ old('latitude') }}"
                            type="text" placeholder="Latitude">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="latitudeError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                    <div class="col" style="display: none;">
                        <label for="longitude">Longitude</label>
                        <input name="longitude" class="form-input-edit MapLon" value="{{ old('longitude') }}"
                            type="text" placeholder="Longitude">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="longitudeError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="display: none;">
                        <label for="country">country</label>
                        <input name="country" class="form-input-edit" value="{{ old('country') }}" type="text"
                            placeholder="country">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="countryError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                    <div class="col" style="display: none;">
                        <label for="state">state</label>
                        <input name="state" class="form-input-edit" value="{{ old('state') }}" type="text"
                            placeholder="state">
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="stateError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="display: none;">
                        <label for="city">city</label>
                        <input name="city" class="form-input-edit" value="{{ old('city') }}" type="text"
                            placeholder="city">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="cityError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                    <div class="col" style="display: none;">
                        <label for="postalCode">postalCode</label>
                        <input name="postalCode" class="form-input-edit" value="{{ old('postalCode') }}" type="text"
                            placeholder="postalCode">
                        @error('postalCode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="postalCodeError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                </div>
                <!-- <h5 style="padding-bottom: 2%; padding-top: 7%;">Datos del negocio</h5> -->
                <div class="border border-vertical mb-4"></div>
                <!-- Aquí va el título "Datos de negocio" -->
                <div class="form-group mb-4">
                    <div class="form-check">
                        <input style="width: 18px; height: 18px; margin-right: 10px;"
                            class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms"
                            id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                        <label style="font-size: 1.5rem;" for="terms" style="cursor: pointer;">
                            Estoy de acuerdo con los
                            <a style="font-size: 1.5rem;" href="https://soporte.lapieza.do/?q=terminos" target="_blank">
                                Términos y Condiciones
                            </a>,
                            <a style="font-size: 1.5rem;" href="https://soporte.lapieza.do/?q=devoluciones"
                                target="_blank">
                                Política de Devoluciones
                            </a> &
                            <a style="font-size: 1.5rem;" href="https://soporte.lapieza.do/?q=politicas-privacidad"
                                target="_blank">
                                Política de Privacidad.
                            </a>
                        </label>
                    </div>
                    @if ($errors->has('terms'))
                        <p style="font-size: 80%;" class="text-danger">
                            {{ $errors->first('terms') }}
                        </p>
                    @endif
                </div>
                <div class="form-group mb-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @if ($errors->has('g-recaptcha-response'))
                        <p style="font-size: 80%;" class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                    @endif
                </div>
                <div class="form-group mb-12 d-flex">
                    <button id="submitButton" class="btn btn-primary btn-lg btn-block"><span>Iniciemos</span> <i
                            class="las la-arrow-right"></i></button>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
        @if (session()->has('flash_notification'))
            @foreach (session('flash_notification') as $message)
                <div class="alert alert-{{ $message->level }} alert-dismissible flash-message" role="alert">
                    {{ $message->message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif
    </form>
@endsection
@section('scripts')
    <!-- Agregar SweetAlert2 y FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Tu código del mapa (MANTENERLO IGUAL) -->
    <script>
        $(function() {
            $('#searchTextField').on('input', function() {
                var input = $(this).val().trim();
                var errorSpan = $('#addressError');
                // Validar si el campo está vacío
                if (input.length === 0) {
                    errorSpan.text('El campo dirección es obligatorio.');
                    $(this).css('border', '1px solid #dc3545');
                    $(this).css('box-shadow', '0 0 5px #dc3545');
                } else {
                    errorSpan.text('');
                    $(this).css('border', '1px solid #198754');
                    $(this).css('box-shadow', '0 0 5px #198754');
                }
            });
            var lat = 18.4934562,
                lng = -69.8954769,
                latlng = new google.maps.LatLng(lat, lng),
                image = 'https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
            var mapOptions = {
                center: new google.maps.LatLng(lat, lng),
                zoom: 12,
                disableDefaultUI: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: true,
                panControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.DEFAULT,
                    position: google.maps.ControlPosition.TOP_left
                }
            };
            var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
            var marker = new google.maps.Marker({
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
            google.maps.event.addListener(autocomplete, 'place_changed', function(event) {
                infowindow.close();
                var place = autocomplete.getPlace();
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    "address": place.formatted_address
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var country = getAddressComponent(results[0], 'country');
                        if (country === 'Dominican Republic') {
                            moveMarker(place.name, place.geometry.location);
                            $('.MapLat').val(place.geometry.location.lat());
                            $('.MapLon').val(place.geometry.location.lng());
                            // Mostrar dirección, estado, país, ciudad y código postal
                            $("input[name='address']").val(place.formatted_address);
                            $("input[name='country']").val(getAddressComponent(place, 'country'));
                            $("input[name='state']").val(getAddressComponent(place,
                                'administrative_area_level_1'));
                            $("input[name='city']").val(getAddressComponent(place, 'locality'));
                            $("input[name='postalCode']").val(getAddressComponent(place,
                                'postal_code'));
                        } else {
                            // No es República Dominicana, no se permite la selección
                            Swal({
                                type: 'error',
                                title: 'Opps!',
                                text: 'Solo se permiten direcciones dentro de la República Dominicana.',
                                timer: 6000
                            })
                            input.value = '';
                        }
                    }
                });
            });
            google.maps.event.addListener(map, 'click', function(event) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    "latLng": event.latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var country = getAddressComponent(results[0], 'country');
                        if (country === 'Dominican Republic') {
                            $('.MapLat').val(event.latLng.lat());
                            $('.MapLon').val(event.latLng.lng());
                            infowindow.close();
                            var placeName = results[0].address_components[0].long_name,
                                latlng = new google.maps.LatLng(event.latLng.lat(), event.latLng
                                    .lng());
                            moveMarker(placeName, latlng);
                            $("#searchTextField").val(results[0].formatted_address);
                            $("input[name='address']").val(results[0].formatted_address);
                            $("input[name='latitude']").val(latlng.lat());
                            $("input[name='longitude']").val(latlng.lng());
                            // Mostrar dirección, estado, país, ciudad y código postal
                            $("input[name='country']").val(getAddressComponent(results[0],
                                'country'));
                            $("input[name='state']").val(getAddressComponent(results[0],
                                'administrative_area_level_1'));
                            $("input[name='city']").val(getAddressComponent(results[0],
                            'locality'));
                            $("input[name='postalCode']").val(getAddressComponent(results[0],
                                'postal_code'));
                        } else {
                            // No es República Dominicana, no se permite la selección
                            Swal({
                                type: 'error',
                                title: 'Opps!',
                                text: 'Solo se permiten direcciones dentro de la República Dominicana.',
                                timer: 6000
                            })
                        }
                    }
                });
            });

            // Función para obtener el valor de un componente de dirección específico
            function getAddressComponent(place, component) {
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (addressType === component) {
                        return place.address_components[i].long_name;
                    }
                }
                return '';
            }

            function moveMarker(placeName, latlng) {
                marker.setIcon(image);
                marker.setPosition(latlng);
                infowindow.setContent(placeName);
            }
        });
    </script>

    <!-- NUEVO: Código AJAX para el formulario -->
    <script>
        $(document).ready(function() {
            // Variable para evitar envíos múltiples
            let isSubmitting = false;

            // Interceptar el envío del formulario
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) {
                    return false;
                }

                if (!validateFormWithDebug()) {
                    return false;
                }

                // Validar reCAPTCHA
                if (typeof grecaptcha !== 'undefined') {
                    try {
                        var recaptchaResponse = grecaptcha.getResponse();
                        if (!recaptchaResponse) {
                            showErrorMessage('Por favor complete el reCAPTCHA');
                            return false;
                        }
                    } catch (e) {
                        console.log('Error con reCAPTCHA:', e);
                    }
                }

                isSubmitting = true;
                showLoading();

                var formData = new FormData(this);

                // Debug: mostrar todos los datos del formulario
              
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
          

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('=== RESPUESTA DEL SERVIDOR ===');
                        console.log('Estado:', response.state);
                        console.log('Mensaje:', response.message);
                        
                        // Si hay información de OCR, mostrarla
                        if (response.debug_info) {
                            
                            
                            // Mostrar modal con información OCR
                            Swal.fire({
                                title: 'Resultados OCR',
                                html: `
                                    <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                                        <pre style="white-space: pre-wrap;">${JSON.stringify(response, null, 2)}</pre>
                                    </div>
                                `,
                                width: '800px',
                                confirmButtonText: 'Continuar',
                                showCancelButton: true,
                                cancelButtonText: 'Revisar de nuevo'
                            }).then((result) => {
                                hideLoading();
                                isSubmitting = false;
                                
                                if (result.isConfirmed) {
                                    if (response.state === true) {
                                        showSuccessMessage('¡Registro exitoso! Redirigiendo...');
                                        setTimeout(function() {
                                            if (response.redirect) {
                                                window.location.href = response.redirect;
                                            }
                                        }, 2000);
                                    } else {
                                        showErrorMessage(response.message || 'Error en el registro');
                                        if (typeof grecaptcha !== 'undefined') {
                                            grecaptcha.reset();
                                        }
                                    }
                                }
                            });
                        } else {
                            // Comportamiento normal sin debug info
                            hideLoading();
                            isSubmitting = false;
                            
                            if (response.state === true) {
                                showSuccessMessage('¡Registro exitoso! Redirigiendo...');
                                setTimeout(function() {
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    }
                                }, 2000);
                            } else {
                                showErrorMessage(response.message || 'Error en el registro');
                                if (typeof grecaptcha !== 'undefined') {
                                    grecaptcha.reset();
                                }
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', xhr.status, error);
                        console.error('Respuesta completa del servidor:', xhr.responseJSON);
                        let errorMessage = 'Ocurrió un error en el servidor';
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', ');
                            console.error('Errores de validación:', xhr.responseJSON.errors);
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorMessage(errorMessage);
                        hideLoading();
                        isSubmitting = false;
                    }
                });
            });

            function validateFormWithDebug() {
                let isValid = true;

                // Limpiar errores previos
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');

                // Campos requeridos básicos
                const requiredFields = ['name_user', 'phone', 'email', 'cedula_input', 'name', 'categories_id',
                    'password', 'password_confirmation', 'address'
                ];

                requiredFields.forEach(function(fieldName) {
                    const field = $('[name="' + fieldName + '"]');
                    const value = field.val();


                    if (field.length === 0) {
                        isValid = false;
                    } else if (!value || value.trim() === '') {
                        showFieldError(fieldName, 'Este campo es requerido');
                        isValid = false;
                    } else {
                        console.log(`✓ Campo ${fieldName} OK`);
                    }
                });

                // Validar contraseñas
                const password = $('#password').val();
                const passwordConfirm = $('#password_confirmation').val();


                if (password && password.length < 8) {
                    showFieldError('password', 'La contraseña debe tener al menos 8 caracteres');
                    isValid = false;
                }

                if (password !== passwordConfirm) {
                    showFieldError('password_confirmation', 'Las contraseñas no coinciden');
                    isValid = false;
                }

                // Validar foto de cédula
                const cedulaPhoto = $('#cedula_photo')[0];
                if (cedulaPhoto && cedulaPhoto.files.length > 0) {
                    console.log('✓ Foto de cédula OK:', cedulaPhoto.files[0].name);
                } else {
                    showFieldError('cedula_photo', 'La foto de cédula es requerida');
                    $('#invalid-feedback-cedula').removeClass('d-none').show();
                    isValid = false;
                }

                // Validar si es persona física
                const isPhysicalPerson = $('#is-physical-person').is(':checked');
                console.log('Es persona física:', isPhysicalPerson ? 'SI' : 'NO');

                if (!isPhysicalPerson) {
                    // Validar RNC
                    const rncValue = $('#rnc_input').val();
                    console.log('RNC:', rncValue || 'VACÍO');
                    if (!rncValue || rncValue.trim() === '') {
                        showFieldError('rnc_input', 'El RNC es requerido para empresas');
                        isValid = false;
                    }

                    // Validar teléfono tienda
                    const telTienda = $('#telefono_tienda').val();
                    console.log('Teléfono tienda:', telTienda || 'VACÍO');
                    if (!telTienda || telTienda.trim() === '') {
                        showFieldError('telefono_tienda', 'El teléfono de tienda es requerido para empresas');
                        isValid = false;
                    }

                    // Validar registro mercantil
                    const regMercantil = $('#registro_mercantil')[0];
                    if (regMercantil && regMercantil.files.length > 0) {
                        console.log('✓ Registro mercantil OK:', regMercantil.files[0].name);
                    } else {
                        showFieldError('registro_mercantil', 'El registro mercantil es requerido para empresas');
                        isValid = false;
                    }
                }

                // Validar términos
                const termsChecked = $('#terms').is(':checked');
                console.log('Términos aceptados:', termsChecked ? 'SI' : 'NO');
                if (!termsChecked) {
                    showFieldError('terms', 'Debe aceptar los términos y condiciones');
                    isValid = false;
                }

                // Validar coordenadas
                const lat = $('.MapLat').val();
                const lon = $('.MapLon').val();
                console.log('Coordenadas:', lat, lon);
                if (!lat || !lon) {
                    showFieldError('address', 'Debe seleccionar una ubicación válida en el mapa');
                    isValid = false;
                }

                return isValid;
            }

            function showFieldError(fieldName, message) {
                const field = $('[name="' + fieldName + '"]');
                field.addClass('is-invalid');

                const errorContainer = $('#' + fieldName + 'Error');
                if (errorContainer.length) {
                    errorContainer.text(message).show();
                } else {
                    const existingFeedback = field.siblings('.invalid-feedback');
                    if (existingFeedback.length) {
                        existingFeedback.text(message).show();
                    } else {
                        field.after('<div class="invalid-feedback" style="display: block;">' + message + '</div>');
                    }
                }

                // Hacer scroll al primer error
                if ($('.is-invalid:first').length) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 300);
                }
            }

            function showLoading() {
                $('#submitButton').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Procesando...');

                if ($('#loadingOverlay').length === 0) {
                    $('body').append(`
                <div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 30px; border-radius: 10px; text-align: center;">
                        <i class="fas fa-spinner fa-spin fa-3x" style="color: #007bff;"></i>
                        <h4>Procesando Registro</h4>
                        <p>Por favor espere...</p>
                    </div>
                </div>
            `);
                } else {
                    $('#loadingOverlay').show();
                }
            }

            function hideLoading() {
                $('#submitButton').prop('disabled', false).html(
                    '<span>Iniciemos</span> <i class="las la-arrow-right"></i>');
                $('#loadingOverlay').fadeOut(300);
            }

            function showSuccessMessage(message) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });
            }

            function showErrorMessage(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonText: 'Entendido'
                });
            }

            // Limpiar errores cuando el usuario empiece a escribir
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').hide();
                const fieldName = $(this).attr('name');
                if (fieldName) {
                    $('#' + fieldName + 'Error').text('').hide();
                }
            });

            // Manejar cambio en checkbox de persona física
            $('#is-physical-person').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#rnc_group').find('input').prop('required', false);
                    $('#rnc_group').slideUp();
                } else {
                    $('#rnc_group').find('input').prop('required', true);
                    $('#rnc_group').slideDown();
                }
            });
        });
    </script>

    <script type="text/javascript" src="{{ asset('/public/assets/js/utilities/register/validations.js') }}"></script>
    <script src="{{ asset('public/assets/js/utilities/register/SellerRegister.js') }}" type="text/javascript"></script>
@endsection
