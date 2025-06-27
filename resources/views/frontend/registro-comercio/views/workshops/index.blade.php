@extends('frontend.layouts.register')
@section('register-type', 'Taller')
@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/css/frontend/register.css') }}">
@endsection

@section('register-form')
    {{-- formulario con correo --}}
    <form class="form-flex email-form was-validated" id="emailGroup" method="POST" action="{{ route('register.workshop.store') }}">
        @csrf
        <div class="container">
            <div class="form-row col-md-12">
                <h5 style="padding-bottom: 2%; color: black !important;">
                    Detalles del Taller
                </h5>
                <div class="border border-vertical mb-4"></div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="name_user" id="nameLabel">Nombre y Apellido</label>
                            <input type="name_user" name="name_user" id="name_user"
                                   placeholder="Nombre y Apellido"
                                   class="form-input-edit name_user @error('name_user') is-invalid @enderror"
                                   value="{{ old('name_user') }}" required>
                            @error('name_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span id="nameError" style="color: #dc3545; font-size: 80%;"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="categories_id" id="categoryLabel">Tipo de Taller</label>
                            <select name="categories_id"
                                    class="form-input-edit @error('categories_id') is-invalid @enderror"
                                    id="categories_id" required>
                                <option value="" selected>Seleccione Tipo de Taller</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label for="name" id="nameLabel">Nombre del Taller</label>
                            <input type="text" name="name" id="name"
                                   placeholder="Nombre"
                                   class="form-input-edit name @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span id="nameNegocioError" style="color: #dc3545; font-size: 80%;"></span>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-4" id="phoneField">
                            <label for="phone" id="numeroLabel">Número móvil</label>
                            <input type="tel" name="phone" id="phone" placeholder="Ejemplo: +10123456789" data-user="seller" class="form-input-edit phone @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            <div id="phoneError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="phoneStatus"></div> <!-- Agrega este elemento para mostrar el mensaje -->
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group mb-4" id="emailField">
                            <label for="email" id="emailLabel">Correo Electrónico</label>
                            <input type="email" name="email" id="email" placeholder="Correo Electrónico" class="form-input-edit email @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            <div id="emailError" style="color: #dc3545; font-size: 80%;"></div>
                            <div id="emailStatus"></div> <!-- Agrega este elemento para mostrar el mensaje -->
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 ">
                        <div class="form-group mb-4">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password"
                                   placeholder="{{ __('Password') }}"
                                   class="form-input-edit @error('password') is-invalid @enderror"
                                   id="inputPassword" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="password1Error" style="color: #dc3545; font-size: 80%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="form-group mb-4">
                            <label for="password_confirmation">Repetir Contraseña</label>
                            <input type="password" name="password_confirmation"
                                   id="password_confirmation"
                                   placeholder="{{ __('Confirmar Password') }}"
                                   class="form-input-edit @error('password_confirmation') is-invalid @enderror"
                                   required>
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
                            <label for="address" id="addressLabel">
                                Buscar dirección o selecciona en el Mapa
                            </label>
                            <input class="form-input-edit"
                                   placeholder="Buscar direccion y seleccionar direccion de google mapas" id="searchTextField"
                                   type="text" size="50"
                                   required>
                            @if ($errors->has('address'))
                                <p style="font-size: 80%;" class="text-danger">
                                    {{ $errors->first('address') }}
                                </p>
                            @endif

                            <div class="col-lg-12" style="display: none;">
                                <div class="form-group mb-4">
                                    <input class="form-input-edit"
                                           placeholder="Ubicación del mapa o mediante buscador" id="searchTextField"
                                           type="text" size="50" name="address"
                                           value="{{ old('address') }}" readonly required>
                                </div>
                            </div>

                            <span id="addressError" style="color: #dc3545; font-size: 80%;"></span>
                            <!-- seccion mapa -->
                            <div id="map_canvas" class="map_canvas"> </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col" style="display: none;">
                        <label for="latitude">Latitude</label>
                        <input name="latitude" class="form-control MapLat"
                               value="{{ old('latitude') }}" type="text"
                               placeholder="Latitude">
                    </div>
                    <div class="col" style="display: none;">
                        <label for="longitude">Longitude</label>
                        <input name="longitude" class="form-control MapLon"
                               value="{{ old('longitude') }}" type="text"
                               placeholder="Longitude">
                    </div>
                </div>

                <div class="row">
                    <div class="col" style="display: none;">
                        <label for="country">country</label>
                        <input name="country" class="form-input-edit"
                               value="{{ old('country') }}" type="text"
                               placeholder="country">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="countryError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                    <div class="col" style="display: none;">
                        <label for="state">state</label>
                        <input name="state" class="form-input-edit"
                               value="{{ old('state') }}" type="text"
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
                        <input name="city" class="form-input-edit"
                               value="{{ old('city') }}" type="text"
                               placeholder="city">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="cityError" style="color: #dc3545; font-size: 80%;"></div>
                    </div>
                    <div class="col" style="display: none;">
                        <label for="postalCode">postalCode</label>
                        <input name="postalCode" class="form-input-edit"
                               value="{{ old('postalCode') }}" type="text"
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
                        <input class="form-check-input @error('terms') is-invalid @enderror"
                               type="checkbox" name="terms" id="terms" value="1"
                               {{ old('terms') ? 'checked' : '' }} required>
                        <label for="terms" style="cursor: pointer;">
                            Estoy de acuerdo con la <a href="{{ route('terms') }}"
                                                       target="_blank">Términos y Condiciones</a>, <a
                                href="{{ route('returnpolicy') }}" target="_blank">Política
                                de Devoluciones</a> & <a href="{{ route('privacypolicy') }}"
                                                         target="_blank">Política de Privacidad</a>
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
                        <p style="font-size: 80%;" class="text-danger">
                            {{ $errors->first('g-recaptcha-response') }}
                        </p>
                    @endif
                </div>

                <div class="form-group mb-12 d-flex">
                    <button class="btn btn-primary btn-lg btn-block">
                        <span>Iniciemos</span>
                        <i class="las la-arrow-right"></i>
                    </button>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#searchTextField').on('input', function () {
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
            var lat = 18.7009047,
                lng = -70.1654584,
                latlng = new google.maps.LatLng(lat, lng),
                image = 'https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
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
            google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
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
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var country = getAddressComponent(results[0], 'country');
                        if (country === 'Dominican Republic') {
                            moveMarker(place.name, place.geometry.location);
                            $('.MapLat').val(place.geometry.location.lat());
                            $('.MapLon').val(place.geometry.location.lng());
                            // Mostrar dirección, estado, país, ciudad y código postal
                            $("input[name='address']").val(place.formatted_address);
                            $("input[name='country']").val(getAddressComponent(place, 'country'));
                            $("input[name='state']").val(getAddressComponent(place, 'administrative_area_level_1'));
                            $("input[name='city']").val(getAddressComponent(place, 'locality'));
                            $("input[name='postalCode']").val(getAddressComponent(place, 'postal_code'));
                        } else {
                            // No es República Dominicana, no se permite la selección
                            alert('Solo se permiten direcciones dentro de la República Dominicana.');
                            input.value = '';
                        }
                    }
                });
            });
            google.maps.event.addListener(map, 'click', function (event) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    "latLng": event.latLng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var country = getAddressComponent(results[0], 'country');
                        if (country === 'Dominican Republic') {
                            $('.MapLat').val(event.latLng.lat());
                            $('.MapLon').val(event.latLng.lng());
                            infowindow.close();
                            var placeName = results[0].address_components[0].long_name,
                                latlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                            moveMarker(placeName, latlng);
                            $("#searchTextField").val(results[0].formatted_address);
                            $("input[name='address']").val(results[0].formatted_address);
                            $("input[name='latitude']").val(latlng.lat());
                            $("input[name='longitude']").val(latlng.lng());
                            // Mostrar dirección, estado, país, ciudad y código postal
                            $("input[name='country']").val(getAddressComponent(results[0], 'country'));
                            $("input[name='state']").val(getAddressComponent(results[0], 'administrative_area_level_1'));
                            $("input[name='city']").val(getAddressComponent(results[0], 'locality'));
                            $("input[name='postalCode']").val(getAddressComponent(results[0], 'postal_code'));
                        } else {
                            // No es República Dominicana, no se permite la selección
                            alert('Solo se permiten direcciones dentro de la República Dominicana.');
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
    <script src="{{ asset('/') }}"></script>
@endsection
