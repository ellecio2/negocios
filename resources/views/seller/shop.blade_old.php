@extends('seller.layouts.app')





@section('panel_content')





    <div class="aiz-titlebar mt-2 mb-4">


        <div class="row align-items-center">


            <div class="col-md-6">


                <h1 class="h3">{{ translate('Shop Settings') }}


                    <span class="ml-3 fs-13">(<a href="{{ route('shop.visit', $shop->slug) }}" class="btn btn-link btn-sm px-0"


                            target="_blank">{{ translate('Visit Shop') }} <i class="las la-arrow-right"></i></a>)</span>


                </h1>


            </div>


        </div>


    </div>





    <!-- Basic Info -->


    <div class="card">


        <div class="card-header">


            <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>


        </div>


        <div class="card-body">


            <form class="" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">


                <input type="hidden" name="shop_id" value="{{ $shop->id }}">


                @csrf


                <div class="row">


                    <label class="col-md-2 col-form-label">{{ translate('Shop Name') }}<span


                            class="text-danger text-danger">*</span></label>


                    <div class="col-md-10">


                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Shop Name') }}"


                            name="name" value="{{ $shop->name }}" required>


                    </div>


                </div>


                <div class="row mb-3">


                    <label class="col-md-2 col-form-label">{{ translate('Shop Logo') }}</label>


                    <div class="col-md-10">


                        <div class="input-group" data-toggle="aizuploader" data-type="image">


                            <div class="input-group-prepend">


                                <div class="input-group-text bg-soft-secondary font-weight-medium">


                                    {{ translate('Browse') }}</div>


                            </div>


                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>


                            <input type="hidden" name="logo" value="{{ $shop->logo }}" class="selected-files">


                        </div>


                        <div class="file-preview box sm">


                        </div>


                    </div>


                </div>


                <div class="row">


                    <div class="col-md-2">


                        <label>{{ translate('Shop Phone') }} <span class="text-danger">*</span></label>


                    </div>


                    <div class="col-md-10">


                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Phone') }}"


                            name="phone" value="{{ $shop->phone }}" required>


                    </div>


                </div>


                <div class="row">


                    <label class="col-md-2 col-form-label">{{ translate('Shop Address') }} <span


                            class="text-danger text-danger">*</span></label>


                    <div class="col-md-10">


                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Address') }}"


                            name="address" value="{{ $shop->address }}" required>


                    </div>


                </div>


                @if (get_setting('shipping_type') == 'seller_wise_shipping')


                    <div class="row">


                        <div class="col-md-2">


                            <label>{{ translate('Shipping Cost') }} <span class="text-danger">*</span></label>


                        </div>


                        <div class="col-md-10">


                            <input type="number" lang="en" min="0" class="form-control mb-3"


                                placeholder="{{ translate('Shipping Cost') }}" name="shipping_cost"


                                value="{{ $shop->shipping_cost }}" required>


                        </div>


                    </div>


                @endif


                <div class="row">


                    <label class="col-md-2 col-form-label">{{ translate('Meta Title') }}<span


                            class="text-danger text-danger">*</span></label>


                    <div class="col-md-10">


                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Meta Title') }}"


                            name="meta_title" value="{{ $shop->meta_title }}" required>


                    </div>


                </div>


                <div class="row">


                    <label class="col-md-2 col-form-label">{{ translate('Meta Description') }}<span


                            class="text-danger text-danger">*</span></label>


                    <div class="col-md-10">


                        <textarea name="meta_description" rows="3" class="form-control mb-3" required>{{ $shop->meta_description }}</textarea>


                    </div>


                </div>


                <div class="form-group mb-0 text-right">


                    <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>


                </div>


            </form>


        </div>


    </div>





    <!-- Delivery Boy Pickup Point -->


    @if (addon_is_activated('delivery_boy'))


        <div class="card">


            <div class="card-header">


                <h5 class="mb-0 h6">{{ translate('Delivery Boy Pickup Point') }}</h5>


            </div>


            <div class="card-body">


                <form class="" action="{{ route('seller.shop.update') }}" method="POST"


                    enctype="multipart/form-data">


                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">


                    @csrf





                    @if (get_setting('google_map') == 1)


                        <div class="row mb-3">


                            <input id="searchInput" class="controls" type="text"


                                placeholder="{{ translate('Enter a location') }}">


                            <div id="map"></div>


                            <ul id="geoData">


                                <li style="display: none;">{{ translate('Full Address') }}: <span id="location"></span>


                                </li>


                                <li style="display: none;">{{ translate('Postal Code') }}: <span id="postal_code"></span>


                                </li>


                                <li style="display: none;">{{ translate('Country') }}: <span id="country"></span></li>


                                <li style="display: none;">{{ translate('Latitude') }}: <span id="lat"></span></li>


                                <li style="display: none;">{{ translate('Longitude') }}: <span id="lon"></span>


                                </li>


                            </ul>


                        </div>


                        <div class="row">


                            <div class="col-md-2" id="">


                                <label for="exampleInputuname">{{ translate('Longitude') }}</label>


                            </div>


                            <div class="col-md-10" id="">


                                <input type="text" class="form-control mb-3" id="longitude"


                                    name="delivery_pickup_longitude" readonly=""


                                    value="{{ $shop->delivery_pickup_longitude }}">


                            </div>


                        </div>


                        <div class="row">


                            <div class="col-md-2" id="">


                                <label for="exampleInputuname">{{ translate('Latitude') }}</label>


                            </div>


                            <div class="col-md-10" id="">


                                <input type="text" class="form-control mb-3" id="latitude"


                                    name="delivery_pickup_latitude" readonly=""


                                    value="{{ $shop->delivery_pickup_latitude }}">


                            </div>


                        </div>


                    @else


                        <div class="row">


                            <div class="col-md-2" id="">


                                <label for="exampleInputuname">{{ translate('Longitude') }}</label>


                            </div>


                            <div class="col-md-10" id="">


                                <input type="text" class="form-control mb-3" id="longitude"


                                    name="delivery_pickup_longitude" value="{{ $shop->delivery_pickup_longitude }}">


                            </div>


                        </div>


                        <div class="row">


                            <div class="col-md-2" id="">


                                <label for="exampleInputuname">{{ translate('Latitude') }}</label>


                            </div>


                            <div class="col-md-10" id="">


                                <input type="text" class="form-control mb-3" id="latitude"


                                    name="delivery_pickup_latitude" value="{{ $shop->delivery_pickup_latitude }}">


                            </div>


                        </div>


                    @endif





                    <div class="form-group mb-0 text-right">


                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>


                    </div>


                </form>


            </div>


        </div>


    @endif





    {{-- HORARIO SETTINGS --}}





    <div class="row">


        <div class="col-md-6">


            <div class="card">


                <div class="card-header">


                    <h5 class="mb-0 h6">Horario de la Tienda</h5>


                    {{-- <button onclick="toggleForm()" class="btn btn-sm btn-primary">Mostrar/Ocultar</button> --}}


                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal">Crear</button>


                </div>


        


                <div class="card-body">


        


                     


                  


                    <div class="table-responsive-sm">


                      


                        <table id="horario_tienda" class="table">


                            <thead class="thead-dark">


                                <tr>


                                    <th >Dia</th>


                                    <th >Apertura</th>


                                    <th >Cierre</th>


                                    <th class="text-center" >Actividad</th>


                                    <th class="text-center" >Acciones</th>


                                </tr>


                            </thead>


                            <tbody>


                                @foreach ($businessWorkingHours as $workingHours)


                                <tr>


                                    <th scope="row">{{ $diasSemanaspanis[array_search($workingHours->dia_semana, $diasSemana)] }}</th>


                                    <td>{{ date("h:i A", strtotime($workingHours->hora_inicio)) }}</td>


                                    <td>{{ date("h:i A", strtotime($workingHours->hora_fin)) }}</td>


                                    <td class="text-center">


                                        @if ($workingHours->laborable)


                                            <div class="alert alert-success" role="alert">


                                                Activo


                                            </div>


                                        @else


                                            <div class="alert alert-danger" role="alert">


                                                Desactivado


                                            </div>


                                        @endif


                                    </td>





                                    <td class="text-center">


                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editModal_{{ $workingHours->id }}">Editar</button>


                                       


                                    </td>


                                </tr>





                                  {{-- MODAL DE EDITAR --}}


        


                                <div class="modal fade" id="editModal_{{ $workingHours->id }}" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">


                                    <div class="modal-dialog" role="document">


                                        <div class="modal-content">


                                            <div class="modal-header">


                                                <h5 class="modal-title" id="createModalLabel">Editar Horario Laboral del Negocio</h5>


                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">


                                                    <span aria-hidden="true">&times;</span>


                                                </button>


                                            </div>


                                            <form action="{{ route('seller.business_working_hours.update', $workingHours->id) }}" method="POST">


                                                @csrf


                                                @method('PUT')


                                                <div class="modal-body">


                                                    


                                                    <div class="form-group">


                                                        <label for="hora_inicio">Hora de apertura:</label>


                                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="{{ $workingHours->hora_inicio }}">


                                                    </div>


                                                    <div class="form-group">


                                                        <label for="hora_fin">Hora de cierre:</label>


                                                        <input type="time" class="form-control" id="hora_fin" name="hora_fin" value="{{ $workingHours->hora_fin }}">


                                                    </div>


                                                    <div class="form-group">


                                                        <label for="laborable">Actividad del día</label>


                                                        <select class="form-control" id="laborable" name="laborable">


                                                            <option value="1" @if ($workingHours->laborable == 1) selected @endif>Activo</option>


                                                            <option value="0" @if ($workingHours->laborable == 0) selected @endif>Desactivo</option>


                                                        </select>


                                                    </div>


                                                </div>


                                                <div class="modal-footer">


                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>


                                                    <button type="submit" class="btn btn-primary">Guardar</button>


                                                </div>


                                            </form>


                                        </div>


                                    </div>


                                </div>


                                


                                @endforeach


                            </tbody>


                        </table>


                    </div>


                    


                    {{-- MODAL DE CREAR --}}


        


                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">


                        <div class="modal-dialog" role="document">


                            <div class="modal-content">


                                <div class="modal-header">


                                    <h5 class="modal-title" id="createModalLabel">Crear Horario Laboral del Negocio</h5>


                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">


                                        <span aria-hidden="true">&times;</span>


                                    </button>


                                </div>


                                <form action="{{ route('seller.business_working_hours.store') }}" method="POST">


                                    @csrf


                                    <div class="modal-body">


                                        <div class="form-group">


                                            <label for="dia_semana">Día de la semana:</label>


                                            {{-- <select class="form-control" id="dia_semana" name="dia_semana">


                                                @foreach ($diasSemana as $dia)


                                                    @if (!$businessWorkingHours->contains('dia_semana', $dia))


                                                        <option value="{{ $dia }}">{{ $dia }}</option>


                                                    @endif


                                                @endforeach


                                            </select> --}}


                                            {{-- <select class="form-control" id="dia_semana" name="dia_semana">


                                                @foreach ($diasSemanaTraducidos as $index => $dia)


                                                    @if (!$businessWorkingHours->contains('dia_semana', $dia))


                                                        <option value="{{ $dia }}">{{ $diasSemanaTraducidos[$index] }}</option>


                                                    @endif


                                                @endforeach


                                            </select> --}}





                                            <select class="form-control" id="dia_semana" name="dia_semana">


                                                @foreach ($diasSemanaspanis as $key => $dia)


                                                    @if (!$businessWorkingHours->contains('dia_semana', $diasSemana[$key]))


                                                        <option value="{{ $diasSemana[$key] }}">{{ $dia }}</option>


                                                    @endif


                                                @endforeach


                                            </select>


                                            @error('dia_semana')


                                                <div class="text-danger">{{ $message }}</div>


                                            @enderror


                                        </div>


                                        <div class="form-group">


                                            <label for="hora_inicio">Hora de apertura:</label>


                                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio">


                                            @error('hora_inicio')


                                                <div class="text-danger">{{ $message }}</div>


                                            @enderror


                                        </div>


                                        <div class="form-group">


                                            <label for="hora_fin">Hora de cierre:</label>


                                            <input type="time" class="form-control" id="hora_fin" name="hora_fin">


                                            @error('hora_fin')


                                                <div class="text-danger">{{ $message }}</div>


                                            @enderror


                                        </div>


                                        <div class="form-group">


                                            <label for="laborable">Actividad del día</label>


                                            <select class="form-control" id="laborable" name="laborable">


                                                <option value="1">Activo</option>


                                                <option value="0">Desactivo</option>


                                            </select>


                                            @error('laborable')


                                                <div class="text-danger">{{ $message }}</div>


                                            @enderror


                                        </div>


                                    </div>


                                    <div class="modal-footer">


                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>


                                        <button type="submit" class="btn btn-primary">Guardar</button>


                                    </div>


                                </form>


                                


                            </div>


                        </div>


                    </div>


                      


                     


        


                </div>


            </div>


        </div>


        <div class="col-md-6">


            <div class="card">


                <div class="card-header">


                    <h5 class="mb-0 h6">Fechas no laborable</h5>


                    {{-- <button onclick="toggleForm()" class="btn btn-sm btn-primary">Mostrar/Ocultar</button> --}}


                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModalnolaborable">Crear</button>


                </div>


        


                <div class="card-body">


        


                     


                  


                    <div class="table-responsive-sm">


                      


                        <table id="fecha_no_laborableeeeee" class="table">


                            <thead class="thead-dark">


                                <tr>


                                    <th>Fecha</th>


                                    <th>Nota</th>


                                    <th class="text-center">Acciones</th>


                                </tr>


                            </thead>


                            <tbody>


                                @foreach ($businessDateNonWorkings as $nonWorkingDate)


                                <tr>


                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($nonWorkingDate->fecha_no_laborable)->formatLocalized('%B %e, %Y') }}</td>


                                    <td>{{ $nonWorkingDate->nota }}</td>


                                    <td class="text-center">


                                        <div class="btn-group-vertical">


                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editModalnolaborable_{{ $nonWorkingDate->id }}">Editar</button>


                                           


                                          


                                            <form action="{{ route('seller.business_dates_non_workings.destroy', $nonWorkingDate->id) }}" method="POST">@method('delete')


                                            @csrf





                                            <button type="submit" class="btn btn-danger btn-sm" id="delete">Eliminar</button>





                                            </form>


                                        </div>         


                                    </td>


                                </tr>





                                {{-- modal de editar --}}


                                <div class="modal fade" id="editModalnolaborable_{{ $nonWorkingDate->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">


                                    <div class="modal-dialog" role="document">


                                        <div class="modal-content">


                                            <div class="modal-header">


                                                <h5 class="modal-title" id="editModalLabel">Editar fechas no laborable</h5>


                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">


                                                    <span aria-hidden="true">&times;</span>


                                                </button>


                                            </div>


                                            <form action="{{ route('seller.business_dates_non_workings.update', $nonWorkingDate->id) }}" method="POST">


                                                @csrf


                                                @method('PUT')


                                                <div class="modal-body">


                                                    <div class="form-group">


                                                        <label for="fecha_no_laborable">Fecha no laborable</label>


                                                        <input type="date" class="form-control" id="fecha_no_laborable" name="fecha_no_laborable" value="{{ $nonWorkingDate->fecha_no_laborable }}" required>


                                                        @error('fecha_no_laborable')


                                                        <div class="text-danger">{{ $message }}</div>


                                                        @enderror


                                                    </div>


                                                    <div class="form-group">


                                                        <label for="nota">Nota</label>


                                                        <input type="text" class="form-control" id="nota" name="nota" value="{{ $nonWorkingDate->nota }}">


                                                        @error('nota')


                                                        <div class="text-danger">{{ $message }}</div>


                                                        @enderror


                                                    </div>


                                                </div>


                                                <div class="modal-footer">


                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>


                                                    <button type="submit" class="btn btn-primary">Guardar</button>


                                                </div>


                                            </form>


                                        </div>


                                    </div>


                                </div>


                                @endforeach


                            </tbody>


                        </table>





                    </div>


                    


        


                    {{-- modal de crear --}}


                    <div class="modal fade" id="createModalnolaborable" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">


                        <div class="modal-dialog" role="document">


                            <div class="modal-content">


                                <div class="modal-header">


                                    <h5 class="modal-title" id="createModalLabel">Crear fechas no laborable</h5>


                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">


                                        <span aria-hidden="true">&times;</span>


                                    </button>


                                </div>


                                <form action="{{ route('seller.business_dates_non_workings.store') }}" method="POST">


                                    @csrf


                                    <div class="modal-body">


                                    


                                            <div class="form-group">


                                                <label for="fecha_no_laborable">Fecha no laborable</label>


                                                <input type="date" class="form-control" id="fecha_no_laborable" name="fecha_no_laborable" required>


                                                @error('fecha_no_laborable')


                                                <div class="text-danger">{{ $message }}</div>


                                                @enderror


                                            </div>


                                            <div class="form-group">


                                                <label for="nota">Nota</label>


                                                <input type="text" class="form-control" id="nota" name="nota">


                                                @error('nota')


                                                <div class="text-danger">{{ $message }}</div>


                                                @enderror


                                            </div>


                                        


                                        


                                    </div>


                                    <div class="modal-footer">


                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>


                                        <button type="submit" class="btn btn-primary">Guardar</button>


                                    </div>


                                </form>


                            </div>


                        </div>


                    </div>








                </div>


            </div>


        </div>


    </div>





    


    {{-- HORARIO SETTINGS --}}














    <!-- Banner Settings -->


    <div class="card">


        <div class="card-header">


            <h5 class="mb-0 h6">{{ translate('Banner Settings') }}</h5>


        </div>


        <div class="card-body">


            <form class="" action="{{ route('seller.shop.update') }}" method="POST"


                enctype="multipart/form-data">


                <input type="hidden" name="shop_id" value="{{ $shop->id }}">


                @csrf


                <!-- Top Banner -->


                <div class="row mb-3">


                    <label class="col-md-2 col-form-label">{{ translate('Top Banner') }} (1920x360)</label>


                    <div class="col-md-10">


                        <div class="input-group" data-toggle="aizuploader" data-type="image">


                            <div class="input-group-prepend">


                                <div class="input-group-text bg-soft-secondary font-weight-medium">


                                    {{ translate('Browse') }}</div>


                            </div>


                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>


                            <input type="hidden" name="top_banner" value="{{ $shop->top_banner }}"


                                class="selected-files">


                        </div>


                        <div class="file-preview box sm">


                        </div>


                        <small


                            class="text-muted">{{ translate('We had to limit height to maintian consistancy. In some device both side of the banner might be cropped for height limitation.') }}</small>


                    </div>


                </div>


                <!-- Slider Banners -->


                <div class="row mb-3">


                    <label class="col-md-2 col-form-label">{{ translate('Slider Banners') }} (1500x450)</label>


                    <div class="col-md-10">


                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">


                            <div class="input-group-prepend">


                                <div class="input-group-text bg-soft-secondary font-weight-medium">


                                    {{ translate('Browse') }}</div>


                            </div>


                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>


                            <input type="hidden" name="sliders" value="{{ $shop->sliders }}" class="selected-files">


                        </div>


                        <div class="file-preview box sm">


                        </div>


                        <small


                            class="text-muted">{{ translate('We had to limit height to maintian consistancy. In some device both side of the banner might be cropped for height limitation.') }}</small>


                    </div>


                </div>


                <!-- Banner Full width 1 -->


                <div class="row mb-3">


                    <label class="col-md-2 col-form-label">{{ translate('Banner Full width 1') }}</label>


                    <div class="col-md-10">


                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">


                            <div class="input-group-prepend">


                                <div class="input-group-text bg-soft-secondary font-weight-medium">


                                    {{ translate('Browse') }}</div>


                            </div>


                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>


                            <input type="hidden" name="banner_full_width_1" value="{{ $shop->banner_full_width_1 }}"


                                class="selected-files">


                        </div>


                        <div class="file-preview box sm">


                        </div>


                    </div>


                </div>


                <!-- Banners half width -->


                <div class="row mb-3">


                    <label class="col-md-2 col-form-label">{{ translate('Banners half width') }}


                        ({{ translate('2 Equal Banners') }})</label>


                    <div class="col-md-10">


                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">


                            <div class="input-group-prepend">


                                <div class="input-group-text bg-soft-secondary font-weight-medium">


                                    {{ translate('Browse') }}</div>


                            </div>


                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>


                            <input type="hidden" name="banners_half_width" value="{{ $shop->banners_half_width }}"


                                class="selected-files">


                        </div>


                        <div class="file-preview box sm">


                        </div>


                    </div>


                </div>


                <!-- Banner Full width 2 -->


                <div class="row mb-3">


                    <label class="col-md-2 col-form-label">{{ translate('Banner Full width 2') }}</label>


                    <div class="col-md-10">


                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">


                            <div class="input-group-prepend">


                                <div class="input-group-text bg-soft-secondary font-weight-medium">


                                    {{ translate('Browse') }}</div>


                            </div>


                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>


                            <input type="hidden" name="banner_full_width_2" value="{{ $shop->banner_full_width_2 }}"


                                class="selected-files">


                        </div>


                        <div class="file-preview box sm">


                        </div>


                    </div>


                </div>


                <!-- Save Button -->


                <div class="form-group mb-0 text-right">


                    <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>


                </div>


            </form>


        </div>


    </div>





    <!-- Social Media Link -->


    <div class="card">


        <div class="card-header">


            <h5 class="mb-0 h6">{{ translate('Social Media Link') }}</h5>


        </div>


        <div class="card-body">


            <form class="" action="{{ route('seller.shop.update') }}" method="POST"


                enctype="multipart/form-data">


                <input type="hidden" name="shop_id" value="{{ $shop->id }}">


                @csrf


                <div class="form-box-content p-3">


                    <div class="row mb-3">


                        <label class="col-md-2 col-form-label">{{ translate('Facebook') }}</label>


                        <div class="col-md-10">


                            <input type="text" class="form-control" placeholder="{{ translate('Facebook') }}"


                                name="facebook" value="{{ $shop->facebook }}">


                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>


                        </div>


                    </div>


                    <div class="row mb-3">


                        <label class="col-md-2 col-form-label">{{ translate('Instagram') }}</label>


                        <div class="col-md-10">


                            <input type="text" class="form-control" placeholder="{{ translate('Instagram') }}"


                                name="instagram" value="{{ $shop->instagram }}">


                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>


                        </div>


                    </div>


                    <div class="row mb-3">


                        <label class="col-md-2 col-form-label">{{ translate('Twitter') }}</label>


                        <div class="col-md-10">


                            <input type="text" class="form-control" placeholder="{{ translate('Twitter') }}"


                                name="twitter" value="{{ $shop->twitter }}">


                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>


                        </div>


                    </div>


                    <div class="row mb-3">


                        <label class="col-md-2 col-form-label">{{ translate('Google') }}</label>


                        <div class="col-md-10">


                            <input type="text" class="form-control" placeholder="{{ translate('Google') }}"


                                name="google" value="{{ $shop->google }}">


                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>


                        </div>


                    </div>


                    <div class="row mb-3">


                        <label class="col-md-2 col-form-label">{{ translate('Youtube') }}</label>


                        <div class="col-md-10">


                            <input type="text" class="form-control" placeholder="{{ translate('Youtube') }}"


                                name="youtube" value="{{ $shop->youtube }}">


                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>


                        </div>


                    </div>


                </div>


                <div class="form-group mb-0 text-right">


                    <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>


                </div>


            </form>


        </div>


    </div>





@endsection





    <link rel="stylesheet" type="text/css"


        href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.css" />

















@section('script')


<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.js"></script>





<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>


    $(function() {


        $(document).on('click', '#delete', function(e) {


            e.preventDefault();


            var link = $(this).attr("action");


            var form = $(this).closest("form");


            Swal.fire({


                title: '¿Estás seguro?',


                text: "¡No podrás revertir esto!",


                icon: 'warning',


                showCancelButton: true,


                confirmButtonColor: '#3085d6',


                cancelButtonColor: '#d33',


                confirmButtonText: '¡Sí, bórralo!',


                cancelButtonText: 'Cancelar'


            }).then((result) => {


                if (result.value && result.value === true) {


                    form.submit();


                } else if (result.dismiss === Swal.DismissReason.cancel) {


                    Swal.fire({


                        title: "Acción Cancelada",


                        text: "La acción fue cancelada",


                        icon: "info"


                    });


                }


            });


        });


    });


</script>





<script>


    $(document).ready(function() {


        $('#horario_tienda').DataTable({


            responsive: true,


            "autoWidth": false,


            language: {


                searchPlaceholder: "Buscar...",


                search: "",


                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",


            },


            'order': [],


            'columnDefs': [{


                    orderable: false,


                    targets: 0


                }, // Disable ordering on column 0 (checkbox)


            ],





        


        });


    });





</script>





<script>


    $(document).ready(function() {


        $('#fecha_no_laborableeeeee').DataTable({


            responsive: true,


            "autoWidth": false,


            language: {


                searchPlaceholder: "Buscar...",


                search: "",


                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",


            },


            'order': [],


            'columnDefs': [{


                    orderable: false,


                    targets: 0


                }, // Disable ordering on column 0 (checkbox)


            ],





        


        });


    });


</script>














    @if (addon_is_activated('delivery_boy') && get_setting('google_map') == 1)


        <script>


            function initialize(id_format = '') {


                let default_longtitude = '';


                let default_latitude = '';


                @if (get_setting('google_map_longtitude') != '' && get_setting('google_map_longtitude') != '')


                    default_longtitude = {{ get_setting('google_map_longtitude') }};


                    default_latitude = {{ get_setting('google_map_latitude') }};


                @endif





                var lat = -33.8688;


                var long = 151.2195;





                if (document.getElementById('latitude').value != '' &&


                    document.getElementById('longitude').value != '') {


                    lat = parseFloat(document.getElementById('latitude').value);


                    long = parseFloat(document.getElementById('longitude').value);


                } else if (default_longtitude != '' &&


                    default_latitude != '') {


                    lat = default_latitude;


                    long = default_longtitude;


                }








                var map = new google.maps.Map(document.getElementById('map'), {


                    center: {


                        lat: lat,


                        lng: long


                    },


                    zoom: 13


                });





                var myLatlng = new google.maps.LatLng(lat, long);





                var input = document.getElementById(id_format + 'searchInput');


                // console.log(input);


                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);





                var autocomplete = new google.maps.places.Autocomplete(input);





                autocomplete.bindTo('bounds', map);





                var infowindow = new google.maps.InfoWindow();


                var marker = new google.maps.Marker({


                    map: map,


                    position: myLatlng,


                    anchorPoint: new google.maps.Point(0, -29),


                    draggable: true,


                });





                map.addListener('click', function(event) {


                    marker.setPosition(event.latLng);


                    document.getElementById(id_format + 'latitude').value = event.latLng.lat();


                    document.getElementById(id_format + 'longitude').value = event.latLng.lng();


                    infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());


                    infowindow.open(map, marker);


                });





                google.maps.event.addListener(marker, 'dragend', function(event) {


                    document.getElementById(id_format + 'latitude').value = event.latLng.lat();


                    document.getElementById(id_format + 'longitude').value = event.latLng.lng();


                    infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());


                    infowindow.open(map, marker);


                });





                autocomplete.addListener('place_changed', function() {


                    infowindow.close();


                    marker.setVisible(false);


                    var place = autocomplete.getPlace();





                    if (!place.geometry) {


                        window.alert("Autocomplete's returned place contains no geometry");


                        return;


                    }





                    // If the place has a geometry, then present it on a map.


                    if (place.geometry.viewport) {


                        map.fitBounds(place.geometry.viewport);


                    } else {


                        map.setCenter(place.geometry.location);


                        map.setZoom(17);


                    }


                    /*


                    marker.setIcon(({


                        url: place.icon,


                        size: new google.maps.Size(71, 71),


                        origin: new google.maps.Point(0, 0),


                        anchor: new google.maps.Point(17, 34),


                        scaledSize: new google.maps.Size(35, 35)


                    }));


                    */


                    marker.setPosition(place.geometry.location);


                    marker.setVisible(true);





                    var address = '';


                    if (place.address_components) {


                        address = [


                            (place.address_components[0] && place.address_components[0].short_name || ''),


                            (place.address_components[1] && place.address_components[1].short_name || ''),


                            (place.address_components[2] && place.address_components[2].short_name || '')


                        ].join(' ');


                    }





                    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);


                    infowindow.open(map, marker);





                    //Location details


                    for (var i = 0; i < place.address_components.length; i++) {


                        if (place.address_components[i].types[0] == 'postal_code') {


                            document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;


                        }


                        if (place.address_components[i].types[0] == 'country') {


                            document.getElementById('country').innerHTML = place.address_components[i].long_name;


                        }


                    }


                    document.getElementById('location').innerHTML = place.formatted_address;


                    document.getElementById(id_format + 'latitude').value = place.geometry.location.lat();


                    document.getElementById(id_format + 'longitude').value = place.geometry.location.lng();


                });





            }


        </script>





        <script


            src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&libraries=places&language=en&callback=initialize"


            async defer></script>


    @endif


@endsection


<script>


    function toggleForm() {


        var form = document.getElementById('formId'); // Replace 'formId' with your form's ID





        // Toggle the display and add a fade effect


        if (form.style.display === 'none' || form.style.display === '') {


            form.style.opacity = '0'; // Start with opacity 0


            form.style.display = 'block';


            fadeIn(form);


        } else {


            form.style.opacity = '1'; // Start with opacity 1


            fadeOut(form);


        }


    }





    function fadeIn(element) {


        var opacity = 0;


        var interval = setInterval(function() {


            if (opacity < 1) {


                opacity += 0.1;


                element.style.opacity = opacity;


            } else {


                clearInterval(interval);


            }


        }, 50);


    }





    function fadeOut(element) {


        var opacity = 1;


        var interval = setInterval(function() {


            if (opacity > 0) {


                opacity -= 0.1;


                element.style.opacity = opacity;


            } else {


                element.style.display = 'none';


                clearInterval(interval);


            }


        }, 25);


    }


</script>


