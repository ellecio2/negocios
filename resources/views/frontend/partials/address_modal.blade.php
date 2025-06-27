<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-default" role="form" id="form-address">
                @csrf
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <!-- Address -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Address') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input class="form-control mb-3 rounded-15px" placeholder="Buscar direccion y seleccionar" type="text" name="address_2" value="{{ old('address_2') }}"
                                    required>
                                    <div class="col-lg-12" style="display: none;">
                                        <div class="form-group mb-4">
                                            <input class="form-input-edit"
                                                placeholder="Buscar direccion y seleccionar" id="new-searchTextField"
                                                type="text" size="50" name="address"
                                                value="{{ old('address') }}" readonly required>
                                        </div>
                                    </div>
                                @if ($errors->has('address'))
                                    <p style="font-size: 80%;" class="text-danger">
                                        {{ $errors->first('address') }}</p>
                                @endif
                                <div id="new-addressError" style="color: #dc3545; font-size: 80%;"></div>
                                <label for="address" id="new-addressLabel" class="fs-14 text-dark fw-500">También,
                                    puedes buscar tu dirección en el Mapa</label>
                                <div id="new-map_canvas" class="map_canvas"></div>
                                <style>
                                    #new-map_canvas {
                                        height: 500px;
                                        margin-top: 20px;
                                        margin-bottom: 20px;
                                    }
                                    @media all and (max-width: 991px) {
                                        #new-map_canvas {
                                            height: 354px !important;
                                            width: 291px !important;
                                            margin-top: 0.8em !important;
                                            position: relative !important;
                                            overflow: hidden !important;
                                            margin-left: 0px !important;
                                            border-radius: 15px !important;
                                        }
                                    }
                                    #new-searchTextField {
                                        width: 100%;
                                        height: 9%;
                                    }
                                </style>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col" style="display: none;">
                                <label for="latitude">Latitude</label>
                                <input name="latitude" class="form-control MapLat" value="{{ old('latitude') }}"
                                    type="text" placeholder="Latitude">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="latitudeError" style="color: #dc3545; font-size: 80%;">
                                </div>
                            </div>
                            <div class="col" style="display: none;">
                                <label for="longitude">Longitude</label>
                                <input name="longitude" class="form-control MapLon" value="{{ old('longitude') }}"
                                    type="text" placeholder="Longitude">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="longitudeError" style="color: #dc3545; font-size: 80%;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="display: none;">
                                <label for="country">country</label>
                                <input name="country" class="form-control" value="{{ old('country') }}" type="text"
                                    placeholder="country">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="countryError" style="color: #dc3545; font-size: 80%;">
                                </div>
                            </div>
                            <div class="col" style="display: none;">
                                <label for="state">state</label>
                                <input name="state" class="form-control" value="{{ old('state') }}" type="text"
                                    placeholder="state">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="stateError" style="color: #dc3545; font-size: 80%;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="display: none;">
                                <label for="city">city</label>
                                <input name="city" class="form-control" value="{{ old('city') }}" type="text"
                                    placeholder="city">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="cityError" style="color: #dc3545; font-size: 80%;">
                                </div>
                            </div>
                            <div class="col" style="display: none;">
                                <label for="postalCode">postalCode</label>
                                <input name="postalCode" class="form-control" value="{{ old('postalCode') }}"
                                    type="text" placeholder="postalCode">
                                @error('postalCode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="postalCodeError" style="color: #dc3545; font-size: 80%;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Phone') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="tel" name="phone" id="phoneuno"
                                    placeholder="Ejemplo: +18091234567"
                                    class="form-control mb-3 rounded-15px phone @error('phone') is-invalid @enderror"
                                    value="{{ $phone_number ?? '' }}">
                                <div id="phoneError" style="color: #dc3545; font-size: 80%;"></div>
                                <div id="phoneStatus"></div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" id="submit-new-address"
                                class="btn btn-primary rounded-25px w-150px">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
