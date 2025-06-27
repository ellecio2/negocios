@extends('seller.layouts.app')



@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">

        <div class="row align-items-center">

            <div class="col-md-6">

                <h1 class="h3">{{ translate('Manage Profile') }}</h1>

            </div>

        </div>

    </div>

    <form action="{{ route('seller.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">

        <input name="_method" type="hidden" value="POST">

        @csrf

        <!-- Basic Info-->

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0 h6">{{ translate('Basic Info')}}</h5>

            </div>

            <div class="card-body">

               

                <div class="form-group row">

                    <label class="col-md-2 col-form-label" for="businnes_name">{{ translate('Nombre del negocio') }}</label>

                    <div class="col-md-10">

                        <input type="text" name="businnes_name" id="businnes_name" value="{{ $user->shop->name }}"
                               class="form-control" placeholder="{{ translate('Nombre del negocio') }}" required>

                        @error('businnes_name')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror


                        <span id="businnes_nameError" style="color: #dc3545; font-size: 80%;"></span>

                    </div>

                </div>

                <div class="form-group row">

                    <label class="col-md-2 col-form-label" for="name">{{ translate('Numero RNC') }}</label>

                    <div class="col-md-10">

                        <input type="text" name="rnc" id="rnc" value="{{ $user->shop->rnc }}"
                               class="form-control" placeholder="{{ translate('Numero RNC') }}" required>

                        @error('rnc')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror


                        <span id="rncError" style="color: #dc3545; font-size: 80%;"></span>

                    </div>

                </div>

                <div class="form-group row">

                        <label class="col-md-2 col-form-label" for="name">Nombre Representante</label>

                        <div class="col-md-10">

                            <input type="text" name="name" value="{{ $user->name }}" id="name"
                                class="form-control" placeholder="{{ translate('Your Name') }}" required>

                            @error('name')

                            <small class="form-text text-danger">{{ $message }}</small>

                            @enderror


                            <span id="nameError" style="color: #dc3545; font-size: 80%;"></span>

                        </div>

                        </div>

                <div class="form-group row">

                    <label class="col-md-2 col-form-label" for="name">Cédula Representante</label>

                    <div class="col-md-10">

                        <input type="text" name="cedula" id="cedula" value="{{ $user->cedula }}"
                               class="form-control" placeholder="{{ translate('CC Representante') }}" required>

                        @error('cedula')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror


                        <span id="cedulaError" style="color: #dc3545; font-size: 80%;"></span>

                    </div>

                </div>


                <div class="form-group row">

                    <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>

                    <div class="col-md-10">

                        <div class="input-group" data-toggle="aizuploader" data-type="image">

                            <div class="input-group-prepend">

                                <div
                                    class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                            </div>

                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                            <input type="hidden" name="photo" value="{{ $user->avatar_original }}"
                                   class="selected-files">

                        </div>

                        <div class="file-preview box sm">

                        </div>

                    </div>

                </div>

                <div class="form-group row">

                    <label class="col-md-2 col-form-label" for="password">{{ translate('Your Password') }}</label>

                    <div class="col-md-10">

                        <input type="password" name="new_password" id="password" class="form-control"
                               placeholder="{{ translate('New Password') }}">

                        @error('new_password')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror


                        <div id="password1Error" style="color: #dc3545; font-size: 80%;"></div>

                    </div>

                </div>

                <div class="form-group row">

                    <label class="col-md-2 col-form-label"
                           for="confirm_password">{{ translate('Confirm Password') }}</label>

                    <div class="col-md-10">

                        <input type="password" name="confirm_password" id="password_confirmation" class="form-control"
                               placeholder="{{ translate('Confirm Password') }}">

                        @error('confirm_password')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror


                        <div id="passwordError" style="color: #dc3545; font-size: 80%;"></div>

                    </div>

                </div>


            </div>

        </div>


        <!-- Payment System -->

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0 h6">{{ translate('Payment Setting')}}</h5>

            </div>

            <div class="card-body">

                <!-- <div class="row">

                    <label class="col-md-3 col-form-label">{{ translate('Cash Payment') }}</label>

                    <div class="col-md-9">

                        <label class="aiz-switch aiz-switch-success mb-3">

                            <input value="1" name="cash_on_delivery_status" type="checkbox"
                                   @if ($user->shop->cash_on_delivery_status == 1) checked @endif>

                            <span class="slider round"></span>

                        </label>

                    </div>

                </div> -->

                <div class="row">

                    <label class="col-md-3 col-form-label">{{ translate('Bank Payment') }}</label>

                    <div class="col-md-9">

                        <label class="aiz-switch aiz-switch-success mb-3">

                            <input value="1" name="bank_payment_status" type="checkbox"
                                   @if ($user->shop->bank_payment_status == 1) checked @endif>

                            <span class="slider round"></span>

                        </label>

                    </div>

                </div>

                <div class="row">

                    <label class="col-md-3 col-form-label" for="bank_name">Nombre del Banco</label>

                    <div class="col-md-9">

                        <input type="text" name="bank_name" value="{{ $user->shop->bank_name }}" id="bank_name"
                               class="form-control mb-3" placeholder="{{ translate('Bank Name')}}">

                        @error('phone')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror

                    </div>

                </div>

                <div class="row">

                    <label class="col-md-3 col-form-label"
                           for="bank_acc_name">Nombre Cuenta de Banco</label>

                    <div class="col-md-9">

                        <input type="text" name="bank_acc_name" value="{{ $user->shop->bank_acc_name }}"
                               id="bank_acc_name" class="form-control mb-3"
                               placeholder="{{ translate('Bank Account Name')}}">

                        @error('bank_acc_name')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror

                    </div>

                </div>

                <div class="row">

                    <label class="col-md-3 col-form-label"
                           for="bank_acc_no">Número de Cuenta</label>

                    <div class="col-md-9">

                        <input type="text" name="bank_acc_no" value="{{ $user->shop->bank_acc_no }}" id="bank_acc_no"
                               class="form-control mb-3" placeholder="{{ translate('Bank Account Number')}}">

                        @error('bank_acc_no')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror

                    </div>

                </div>

                <div class="row">

                    <label class="col-md-3 col-form-label"
                           for="bank_routing_no">Número de Ruta Bancaria</label>

                    <div class="col-md-9">

                        <input type="number" name="bank_routing_no" value="{{ $user->shop->bank_routing_no }}"
                               id="bank_routing_no" lang="en" class="form-control mb-3"
                               placeholder="{{ translate('Bank Routing Number')}}">

                        @error('bank_routing_no')

                        <small class="form-text text-danger">{{ $message }}</small>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        <div class="form-group mb-0 text-right">

            <button type="submit" class="btn btn-primary">{{translate('Update Profile')}}</button>

        </div>

    </form>



    <br>



    <!-- Address -->

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0 h6">{{ translate('Address')}}</h5>

        </div>

        <div class="card-body">

            <div class="row gutters-10">

                @foreach ($addresses as $key => $address)

                    <div class="col-lg-4">

                        <div class="border p-3 pr-5 rounded mb-3 position-relative">

                            <div>

                                <span class="w-50 fw-600">{{ translate('Address') }}:</span>

                                <span class="ml-2">{{ $address->address }}</span>

                            </div>

                            <div>

                                <span class="w-50 fw-600">{{ translate('Postal Code') }}:</span>

                                <span class="ml-2">{{ $address->postalCode }}</span>

                            </div>

                            <div>

                                <span class="w-50 fw-600">{{ translate('City') }}:</span>

                                <span class="ml-2">{{ $address->city }}</span>

                            </div>

                            <div>

                                <span class="w-50 fw-600">{{ translate('State') }}:</span>

                                <span class="ml-2">{{ $address->state }}</span>

                            </div>

                            <div>

                                <span class="w-50 fw-600">{{ translate('Country') }}:</span>

                                <span class="ml-2">{{ $address->country }}</span>

                            </div>

                            <div>

                                <span class="w-50 fw-600">{{ translate('Phone') }}:</span>

                                <span class="ml-2">{{ $address->phone }}</span>

                            </div>

                            @if ($address->set_default)

                                <div class="position-absolute right-0 bottom-0 pr-2 pb-3">

                                    <span class="badge badge-inline badge-primary">{{ translate('Default') }}</span>

                                </div>

                            @endif

                            <div class="dropdown position-absolute right-0 top-0">

                                <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">

                                    <i class="la la-ellipsis-v"></i>

                                </button>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                    <a class="dropdown-item" href="#"
                                       onclick="edit_address({{ $address->id }}, '{{ $address->longitude }}', '{{ $address->latitude }}')">

                                        {{ translate('Edit') }}

                                    </a>


                                    @if (!$address->set_default)

                                        <a class="dropdown-item"
                                           href="{{ route('seller.addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>

                                    @endif

                                    <a class="dropdown-item"
                                       href="{{ route('seller.addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Edit Address Modal -->

                    <div class="modal fade" id="edit-address-modal_{{ $address->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Edit Address') }}</h5>

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                    </button>

                                </div>

                                <form class="form-default" role="form" id="edit-address-form"
                                      action="{{ route('addresses.update', $address->id ?? '') }}" method="POST">

                                    @csrf

                                    @method('PUT')


                                    <div class="modal-body c-scrollbar-light">

                                        <div class="p-3">

                                            <!-- Address -->

                                            <div class="row">

                                                <div class="col-md-2">

                                                    <label>{{ translate('Address')}}</label>

                                                </div>


                                                <div class="col-md-10">

                                                    <input class="form-control mb-3 rounded-0"

                                                           placeholder="Introduce la Ubicación"
                                                           id="edit_searchTextField_{{ $address->id ?? '1' }}"

                                                           type="text" name="address"

                                                           value="{{ old('address', $address->address ?? '') }}"
                                                           required readonly>


                                                    @if ($errors->has('address'))

                                                        <p style="font-size: 80%;" class="text-danger">

                                                            {{ $errors->first('address') }}</p>

                                                    @endif


                                                    <div id="edit-addressError"
                                                         style="color: #dc3545; font-size: 80%;"></div>


                                                    <label for="address" id="edit-addressLabel"

                                                           class="fs-14 text-dark fw-500">Solo puedes editar dirección
                                                        seleccionando en el mapa.</label>


                                                    <div id="edit-map_canvas_{{ $address->id ?? '1' }}"
                                                         class="map_canvas edit-map_canvas"></div>

                                                    <style>

                                                        .edit-map_canvas {

                                                            height: 500px;

                                                            margin-top: 20px;

                                                            margin-bottom: 20px;

                                                        }


                                                        @media all and (max-width: 991px) {

                                                            .edit-map_canvas {

                                                                height: 354px !important;

                                                                width: 261px !important;

                                                                margin-top: 0.8em !important;

                                                                position: relative !important;

                                                                overflow: hidden !important;

                                                                margin-left: 0px !important;

                                                            }

                                                        }


                                                        .edit-searchTextField {

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

                                                    <input name="latitude" class="form-control MapLat"

                                                           value="{{ old('latitude', $address->latitude ?? '') }}"
                                                           type="text"

                                                           placeholder="Latitude">

                                                </div>


                                                <div class="col" style="display: none;">

                                                    <label for="longitude">Longitude</label>

                                                    <input name="longitude" class="form-control MapLon"

                                                           value="{{ old('longitude', $address->longitude ?? '') }}"
                                                           type="text"

                                                           placeholder="Longitude">

                                                </div>

                                            </div>


                                            <div class="row">

                                                <div class="col" style="display: none;">

                                                    <label for="country">country</label>

                                                    <input name="country" class="form-control"

                                                           value="{{ old('country', $address->country ?? '') }}"
                                                           type="text"

                                                           placeholder="country">


                                                    @error('country')

                                                    <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror


                                                    <div id="countryError" style="color: #dc3545; font-size: 80%;">

                                                    </div>

                                                </div>

                                                <div class="col" style="display: none;">

                                                    <label for="state">state</label>

                                                    <input name="state" class="form-control"

                                                           value="{{ old('state', $address->state ?? '') }}" type="text"

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


                                                    <input name="city" class="form-control"

                                                           value="{{ old('city', $address->city ?? '') }}" type="text"

                                                           placeholder="city">


                                                    @error('city')

                                                    <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror


                                                    <div id="cityError" style="color: #dc3545; font-size: 80%;">

                                                    </div>

                                                </div>

                                                <div class="col" style="display: none;">

                                                    <label for="postalCode">postalCode</label>

                                                    <input name="postalCode" class="form-control"

                                                           value="{{ old('postalCode', $address->postalCode ?? '') }}"
                                                           type="text"

                                                           placeholder="postalCode">


                                                    @error('postalCode')

                                                    <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror


                                                    <div id="postalCodeError" style="color: #dc3545; font-size: 80%;">

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="row">

                                                <div class="col-md-2">

                                                    <label>{{ translate('Phone')}} 111</label>

                                                </div>

                                                <div class="col-md-10">

                                                    <input type="tel" name="phone"
                                                           id="phonedos_{{ $address->id ?? '1' }}"
                                                           placeholder="Ejemplo: +10123456789"
                                                           class="form-control phone-input mb-3 rounded-0 phone @error('phone') is-invalid @enderror"
                                                           value="{{ old('phone', $address->phone ?? '') }}">

                                                    <div id="phoneErrordos"
                                                         style="color: #dc3545; font-size: 80%;"></div>

                                                    <div id="phoneStatus"></div>

                                                    @error('phone')

                                                    <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror

                                                </div>

                                            </div>


                                            <div class="form-group text-right">

                                                <button type="button" id="submit-edit-address-{{ $address->id }}"
                                                        class="btn btn-primary rounded-0 w-150px">{{translate('Save')}} </button>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach


                <div class="col-lg-4 mx-auto">

                    <div class="border p-3 rounded mb-3 c-pointer text-center bg-light" onclick="add_new_address()">

                        <i class="la la-plus la-2x"></i>

                        <div class="alpha-7">{{ translate('Add New Address') }}</div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- Change Email -->

    <form action="{{ route('user.change.email') }}" method="POST">

        @csrf

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0 h6">{{ translate('Change your email')}}</h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-2">

                        <label>{{ translate('Your Email') }}</label>

                    </div>

                    <div class="col-md-10">

                        <div class="input-group mb-3">

                            <input type="email" class="form-control" placeholder="{{ translate('Your Email')}}"
                                   name="email" value="{{ $user->email }}"/>

                            <div class="input-group-append">

                                <button type="button" class="btn btn-outline-secondary new-email-verification">

                               <span class="d-none loading">

                                   <span class="spinner-border spinner-border-sm" role="status"
                                         aria-hidden="true"></span>{{ translate('Sending Email...') }}

                               </span>

                                    <span class="default">{{ translate('Verify') }}</span>

                                </button>

                            </div>

                        </div>

                        <div class="form-group mb-0 text-right">

                            <button type="submit" class="btn btn-primary">{{translate('Update Email')}}</button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

@endsection



@section('modal')

    {{-- New Address Modal --}}

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

                <form class="form-default" id="form-address" role="form" action="{{ route('addresses.store') }}"
                      method="POST">

                    @csrf

                    <div class="modal-body c-scrollbar-light">

                        <div class="p-3">

                            <!-- Address -->

                            <div class="row">

                                <div class="col-md-2">

                                    <label>{{ translate('Address')}}</label>

                                </div>


                                <div class="col-md-10">

                                    <input class="form-control mb-3 rounded-0"

                                           placeholder="Buscar direccion y seleccionar" id="new-searchTextField"

                                           type="text" name="address_2"

                                           value="{{ old('address_2') }}" required>


                                    {{-- <input class="form-control mb-3 rounded-0"

                                        placeholder="Buscar direccion y seleccionar" id="new-searchTextField"

                                        type="text" value="{{ old('address') }}" required> --}}


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


                                    <div id="new-addressError"

                                         style="color: #dc3545; font-size: 80%;"></div>


                                    <label for="address" id="new-addressLabel"

                                           class="fs-14 text-dark fw-500">También, puedes buscar tu dirección en el
                                        Mapa</label>


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

                                                width: 261px !important;

                                                margin-top: 0.8em !important;

                                                position: relative !important;

                                                overflow: hidden !important;

                                                margin-left: 0px !important;

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

                                    <input name="latitude" class="form-control MapLat"

                                           value="{{ old('latitude') }}" type="text"

                                           placeholder="Latitude">


                                    @error('latitude')

                                    <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror


                                    <div id="latitudeError" style="color: #dc3545; font-size: 80%;">

                                    </div>

                                </div>

                                <div class="col" style="display: none;">

                                    <label for="longitude">Longitude</label>

                                    <input name="longitude" class="form-control MapLon"

                                           value="{{ old('longitude') }}" type="text"

                                           placeholder="Longitude">


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

                                    <input name="country" class="form-control"

                                           value="{{ old('country') }}" type="text"

                                           placeholder="country">


                                    @error('country')

                                    <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror


                                    <div id="countryError" style="color: #dc3545; font-size: 80%;">

                                    </div>

                                </div>

                                <div class="col" style="display: none;">

                                    <label for="state">state</label>

                                    <input name="state" class="form-control"

                                           value="{{ old('state') }}" type="text"

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


                                    <input name="city" class="form-control"

                                           value="{{ old('city') }}" type="text"

                                           placeholder="city">


                                    @error('city')

                                    <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror


                                    <div id="cityError" style="color: #dc3545; font-size: 80%;">

                                    </div>

                                </div>

                                <div class="col" style="display: none;">

                                    <label for="postalCode">postalCode</label>

                                    <input name="postalCode" class="form-control"

                                           value="{{ old('postalCode') }}" type="text"

                                           placeholder="postalCode">


                                    @error('postalCode')

                                    <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror


                                    <div id="postalCodeError" style="color: #dc3545; font-size: 80%;">

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">

                                    <label>{{ translate('Phone')}}</label>

                                </div>

                                <div class="col-md-10">

                                    <input type="tel" name="phone" id="phoneuno" placeholder="Ejemplo: +10123456789"
                                           class="form-control mb-3 rounded-0 phone @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}">


                                    <div id="phoneError" style="color: #dc3545; font-size: 80%;"></div>


                                    <div id="phoneStatus"></div>


                                    @error('phone')

                                    <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror

                                </div>

                            </div>


                            <div class="form-group text-right">

                                <button id="submit-new-address"
                                        class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>

                            </div>

                        </div>

                    </div>


                </form>

            </div>

        </div>

    </div>

@endsection







@section('script')

    <script

        src='https://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key={{ env('GOOGLE_MAPS_API_KEY') }}'>

    </script>





    <script type="text/javascript">

        function add_new_address() {

            $('#new-address-modal').modal('show');

            showMapnew("new-map_canvas");

        }

        $('#submit-new-address').on('click', function (event) {
            event.preventDefault();
            var url = "{{ route('addresses.store') }}";
            var formData = $('#form-address').serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.state === 'success') {
                        $('#new-address-modal').modal('hide');
                        Swal.fire({
                            type: 'success',
                            title: 'Bien',
                            text: response.msg,
                            timer: 6000
                        }).then(() => {
                            location.reload();
                        })
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: response.msg,
                            timer: 6000
                        })
                    }

                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });


        function edit_address(id, longitude, latitude) {

            $('#edit-address-modal_' + id).modal('show');

            showMapedit('edit-map_canvas_' + id, longitude, latitude);
            $('#submit-edit-address-' + id).attr('data-id', id);
        }

        $('[id^=submit-edit-address-]').on('click', function (event) {
            event.preventDefault();
            let id = $(this).data('id');
            let url = "{{ route('addresses.update', '__id__') }}".replace('__id__', id);
            let formData = $('#edit-address-form').serialize();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "PUT",
                url: url,
                data: formData,
                success: function (data, textStatus, jqXHR) {
                    if (data.state === 'success') {
                        Swal({
                            type: 'success',
                            title: 'Bien',
                            text: data.msg,
                            timer: 6000
                        }).then(() => {
                            location.reload();
                        })
                    } else {
                        Swal({
                            type: 'error',
                            title: 'Oops...',
                            text: data.msg,
                            timer: 6000
                        })
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            })
        });


    </script>



    <script>

        function showMapnew(mapId) {

            var latitude = 18.7009047;

            var longitude = -70.1654584;


            var latlng = new google.maps.LatLng(latitude, longitude);

            var image = 'https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';


            var mapOptions = {

                center: latlng,

                zoom: 10,

                mapTypeId: google.maps.MapTypeId.ROADMAP,

                panControl: true,

                panControlOptions: {

                    position: google.maps.ControlPosition.TOP_RIGHT

                },

                zoomControl: true,

                zoomControlOptions: {

                    style: google.maps.ZoomControlStyle.LARGE,

                    position: google.maps.ControlPosition.TOP_LEFT

                }

            };


            var map = new google.maps.Map(document.getElementById(mapId), mapOptions);

            var marker = new google.maps.Marker({

                position: latlng,

                map: map,

                icon: image,

                draggable: true

            });


            google.maps.event.addListener(marker, 'dragend', function (event) {

                $("input[name='latitude']").val(event.latLng.lat());

                $("input[name='longitude']").val(event.latLng.lng());

            });


            var input = document.getElementById(mapId.replace("map_canvas", "searchTextField"));

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


                // Mostrar dirección, estado, país, ciudad y código postal

                $("input[name='address_2']").val(place.formatted_address);

                $("input[name='address']").val(place.formatted_address);

                $("input[name='country']").val(getAddressComponent(place, 'country'));

                $("input[name='state']").val(getAddressComponent(place, 'administrative_area_level_1'));

                $("input[name='city']").val(getAddressComponent(place, 'locality'));

                $("input[name='postalCode']").val(getAddressComponent(place, 'postal_code'));

            });


            google.maps.event.addListener(map, 'click', function (event) {

                $('.MapLat').val(event.latLng.lat());

                $('.MapLon').val(event.latLng.lng());

                infowindow.close();

                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({

                    "latLng": event.latLng

                }, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {

                        var lat = results[0].geometry.location.lat(),

                            lng = results[0].geometry.location.lng(),

                            placeName = results[0].address_components[0].long_name,

                            latlng = new google.maps.LatLng(lat, lng);


                        moveMarker(placeName, latlng);

                        $("#searchTextField").val(results[0].formatted_address);

                        $("input[name='address_2']").val(results[0].formatted_address);

                        $("input[name='address']").val(results[0].formatted_address);

                        $("input[name='latitude']").val(latlng.lat());

                        $("input[name='longitude']").val(latlng.lng());


                        // Mostrar dirección, estado, país, ciudad y código postal

                        $("input[name='country']").val(getAddressComponent(results[0], 'country'));

                        $("input[name='state']").val(getAddressComponent(results[0], 'administrative_area_level_1'));

                        $("input[name='city']").val(getAddressComponent(results[0], 'locality'));

                        $("input[name='postalCode']").val(getAddressComponent(results[0], 'postal_code'));

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

        }


        function showMapedit(mapId, longitude, latitude) {


            var latlng = new google.maps.LatLng(latitude, longitude);

            var image = 'https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';


            var mapOptions = {

                center: latlng,

                zoom: 15,

                mapTypeId: google.maps.MapTypeId.ROADMAP,

                panControl: true,

                panControlOptions: {

                    position: google.maps.ControlPosition.TOP_RIGHT

                },

                zoomControl: true,

                zoomControlOptions: {

                    style: google.maps.ZoomControlStyle.LARGE,

                    position: google.maps.ControlPosition.TOP_LEFT

                }

            };


            var map = new google.maps.Map(document.getElementById(mapId), mapOptions);

            var marker = new google.maps.Marker({

                position: latlng,

                map: map,

                icon: image,

                draggable: true

            });


            google.maps.event.addListener(marker, 'dragend', function (event) {

                $("input[name='latitude']").val(event.latLng.lat());

                $("input[name='longitude']").val(event.latLng.lng());

            });


            var input = document.getElementById(mapId.replace("map_canvas", "edit_searchTextField_" + mapId));

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


                // Mostrar dirección, estado, país, ciudad y código postal

                $("input[name='address']").val(place.formatted_address);

                $("input[name='country']").val(getAddressComponent(place, 'country'));

                $("input[name='state']").val(getAddressComponent(place, 'administrative_area_level_1'));

                $("input[name='city']").val(getAddressComponent(place, 'locality'));

                $("input[name='postalCode']").val(getAddressComponent(place, 'postal_code'));

            });


            google.maps.event.addListener(map, 'click', function (event) {

                $('.MapLat').val(event.latLng.lat());

                $('.MapLon').val(event.latLng.lng());

                infowindow.close();

                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({

                    "latLng": event.latLng

                }, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {

                        var lat = results[0].geometry.location.lat(),

                            lng = results[0].geometry.location.lng(),

                            placeName = results[0].address_components[0].long_name,

                            latlng = new google.maps.LatLng(lat, lng);


                        moveMarker(placeName, latlng);

                        $("#edit_searchTextField").val(results[0].formatted_address);

                        $("input[name='address']").val(results[0].formatted_address);

                        $("input[name='latitude']").val(latlng.lat());

                        $("input[name='longitude']").val(latlng.lng());


                        // Mostrar dirección, estado, país, ciudad y código postal

                        $("input[name='country']").val(getAddressComponent(results[0], 'country'));

                        $("input[name='state']").val(getAddressComponent(results[0], 'administrative_area_level_1'));

                        $("input[name='city']").val(getAddressComponent(results[0], 'locality'));

                        $("input[name='postalCode']").val(getAddressComponent(results[0], 'postal_code'));

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

        }


    </script>





    <script>

        //agregar el +1

        document.addEventListener('DOMContentLoaded', function () {

            var phoneInput = document.getElementById('phoneuno');


            phoneInput.addEventListener('input', function () {

                var inputValue = phoneInput.value.trim();


                if (inputValue.length === 1) {

                    phoneInput.value = '+1' + inputValue;

                }

            });

        });

    </script>



    <script>

        //esto es para el formulario principal de usuario

        //agregar el +1

        document.addEventListener('DOMContentLoaded', function () {

            var phoneInput = document.getElementById('phoneedit');


            phoneInput.addEventListener('input', function () {

                var inputValue = phoneInput.value.trim();


                if (inputValue.length === 1) {

                    phoneInput.value = '+1' + inputValue;

                }

            });

        });

    </script>





    <script>

        // Agregar el +1 a los campos de teléfono

        function addPlusOneToPhoneInputs() {

            var phoneInputs = document.getElementsByClassName('phone-input');


            for (var i = 0; i < phoneInputs.length; i++) {

                var phoneInput = phoneInputs[i];


                phoneInput.addEventListener('input', function () {

                    var inputValue = this.value.trim();


                    if (inputValue.length === 1) {

                        this.value = '+1' + inputValue;

                    }

                });

            }

        }


        document.addEventListener('DOMContentLoaded', function () {

            addPlusOneToPhoneInputs();

        });

    </script>



    <script>


        $(document).ready(function () {


            $('#phoneuno').on('input', function () {

                var input = $(this).val().trim().replace(/\s/g, ''); // Eliminar espacios en blanco

                var errorSpan = $('#phoneError');

                var phoneInput = $(this); // Almacenar una referencia al elemento


                // Validar si el campo está vacío

                if (input.length === 0) {

                    errorSpan.text('El campo número móvil es obligatorio.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo contiene solo números y el símbolo "+"

                else if (!/^[\d+]+$/.test(input)) {

                    errorSpan.text('Solo se permiten números y el símbolo "+".');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo tiene el formato correcto para el código de área de la República Dominicana y los siguientes 8 números

                else if (!/^\+1\d{10}$/.test(input)) { // Eliminar el espacio después de "+1"

                    errorSpan.text('El número móvil debe tener el formato +1 seguido de 10 números.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                } else {

                    errorSpan.text('');

                    $(this).css('border', '1px solid #198754');

                    $(this).css('box-shadow', '0 0 5px #198754');


                }

            });


            //editar campo numero

            $('[id^="phonedos_"]').on('input', function () {

                var input = $(this).val().trim().replace(/\s/g, ''); // Eliminar espacios en blanco

                var errorSpan = $(this).siblings('#phoneErrordos');

                var phoneInput = $(this); // Almacenar una referencia al elemento


                // Validar si el campo está vacío

                if (input.length === 0) {

                    errorSpan.text('El campo número móvil es obligatorio.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo contiene solo números y el símbolo "+"

                else if (!/^[\d+]+$/.test(input)) {

                    errorSpan.text('Solo se permiten números y el símbolo "+".');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo tiene el formato correcto para el código de área de la República Dominicana y los siguientes 8 números

                else if (!/^\+1\d{10}$/.test(input)) { // Eliminar el espacio después de "+1"

                    errorSpan.text('El número móvil debe tener el formato +1 seguido de 10 números.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                } else {

                    errorSpan.text('');

                    $(this).css('border', '1px solid #198754');

                    $(this).css('box-shadow', '0 0 5px #198754');

                }

            });


            $('#new-searchTextField').on('input', function () {

                var input = $(this).val().trim();

                var errorSpan = $('#new-addressError');


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


            $('#edit-searchTextField').on('input', function () {

                var input = $(this).val().trim();

                var errorSpan = $('#edit-addressError');


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


        });

    </script>



    <script type="text/javascript">


        $('.new-email-verification').on('click', function () {

            $(this).find('.loading').removeClass('d-none');

            $(this).find('.default').addClass('d-none');

            var email = $("input[name=email]").val();


            $.post('{{ route('user.new.verify') }}', {_token: '{{ csrf_token() }}', email: email}, function (data) {

                data = JSON.parse(data);

                $('.default').removeClass('d-none');

                $('.loading').addClass('d-none');

                if (data.status == 2)

                    AIZ.plugins.notify('warning', data.message);

                else if (data.status == 1)

                    AIZ.plugins.notify('success', data.message);

                else

                    AIZ.plugins.notify('danger', data.message);

            });

        });


    </script>



    <script>

        $(document).ready(function () {

            $('#name_user').on('input', function () {

                var input = $(this).val().trim();

                var errorSpan = $('#nameError');


                // Validar si el campo está vacío

                if (input.length === 0) {

                    errorSpan.text('El campo nombre y apellido es obligatorio.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo contiene solo letras, espacios y la letra "ñ"

                else if (!/^[a-zA-ZñÑ\s]+$/.test(input)) {

                    errorSpan.text('Solo se permiten letras y espacios');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                } else {

                    errorSpan.text('');

                    $(this).css('border', '1px solid rgba(0, 0, 0, 0.12)');

                    $(this).css('box-shadow', '0 0 5px rgba(0, 0, 0, 0.12)');


                    // Validar si el campo tiene más de 3 letras

                    if (input.length < 3) {

                        errorSpan.text('Debe tener al menos 3 letras');

                        $(this).css('border', '1px solid #dc3545');

                        $(this).css('box-shadow', '0 0 5px #dc3545');

                    } else {

                        errorSpan.text('');

                        $(this).css('border', '1px solid #198754');

                        $(this).css('box-shadow', '0 0 5px #198754');

                    }

                }

            });


            $('#phoneedit').on('input', function () {

                var input = $(this).val().trim().replace(/\s/g, ''); // Eliminar espacios en blanco

                var errorSpan = $('#phoneError');

                var phoneInput = $(this); // Almacenar una referencia al elemento


                // Validar si el campo está vacío

                if (input.length === 0) {

                    errorSpan.text('El campo número móvil es obligatorio.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo contiene solo números y el símbolo "+"

                else if (!/^[\d+]+$/.test(input)) {

                    errorSpan.text('Solo se permiten números y el símbolo "+".');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si el campo tiene el formato correcto para el código de área de la República Dominicana y los siguientes 8 números

                else if (!/^\+1\d{10}$/.test(input)) { // Eliminar el espacio después de "+1"

                    errorSpan.text('El número móvil debe tener el formato +1 seguido de 10 números.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                } else {

                    errorSpan.text('');

                    $(this).css('border', '1px solid #198754');

                    $(this).css('box-shadow', '0 0 5px #198754');


                }

            });


            $('#password').on('input', function () {

                var password = $(this).val().trim();

                var errorSpan = $('#password1Error');


                // Validar si el campo está vacío

                if (password.length === 0) {

                    errorSpan.text('El campo de contraseña es obligatorio.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si la contraseña tiene al menos 6 caracteres

                else if (password.length < 6) {

                    errorSpan.text('La contraseña debe tener al menos 6 caracteres.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                } else {

                    errorSpan.text('');

                    $(this).css('border', '1px solid #198754');

                    $(this).css('box-shadow', '0 0 5px #198754');

                }

            });


            $('#password_confirmation').on('input', function () {

                var password = $('#password').val().trim();

                var confirmPassword = $(this).val().trim();

                var errorSpan = $('#passwordError');


                // Validar si el campo está vacío

                if (confirmPassword.length === 0) {

                    errorSpan.text('El campo de confirmación de contraseña es obligatorio.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                }

                // Validar si la contraseña y la confirmación coinciden

                else if (password !== confirmPassword) {

                    errorSpan.text('Las contraseñas no coinciden.');

                    $(this).css('border', '1px solid #dc3545');

                    $(this).css('box-shadow', '0 0 5px #dc3545');

                } else {

                    errorSpan.text('');

                    $(this).css('border', '1px solid rgba(0, 0, 0, 0.12)');

                    $(this).css('box-shadow', '0 0 5px rgba(0, 0, 0, 0.12)');


                    // Validar si la contraseña tiene al menos 6 caracteres

                    if (password.length < 6) {

                        errorSpan.text('La contraseña debe tener al menos 6 caracteres.');

                        $(this).css('border', '1px solid #dc3545');

                        $(this).css('box-shadow', '0 0 5px #dc3545');

                    } else {

                        errorSpan.text('');

                        $(this).css('border', '1px solid #198754');

                        $(this).css('box-shadow', '0 0 5px #198754');

                    }

                }

            });


        });

    </script>







    {{-- @if (get_setting('google_map') == 1)



        @include('frontend.partials.google_map')



    @endif --}}

@endsection

