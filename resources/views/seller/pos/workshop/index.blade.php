@extends('seller.layouts.app')
@section('panel_content')
<style>
    #preloader-delivery {
        margin-top: 50px;
        display: flex;
        justify-content: center;
        height: 200px;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.9);
        z-index: 1;
    }

    #loader-delivery {
        display: block;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        margin-left: 3%;
    }

    #loader-delivery:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #E63108;
        -webkit-animation: spin 3s linear infinite;
        animation: spin 3s linear infinite;
    }

    #loader-delivery:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #000;
        -webkit-animation: spin 1.5s linear infinite;
        animation: spin 1.5s linear infinite;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.5s;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .btn-tooltip {
        font-size: 0.7rem;
        color: white;
        margin: 0;
        padding: 1px;
        width: 25px;
        height: 25px;
        border-style: none;
        border-radius: 50px;
        font-weight: bold;
        background-color: #a71d2a;
    }

    .box-title.custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-transform: uppercase;
        width: 100%;
        font-weight: bold;
        color: white;
        align-content: center;
    }

    .rowe {
        align-items: center;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    .card-info {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        cursor: pointer;
        row-gap: 15px;
        transition: opacity 0.3s ease-in-out;
    }

    .card-info svg {
        fill: white;
        width: 25px;
        height: 25px;
    }

    .card-info p {
        color: white;
        font-weight: bold;
        font-size: 1rem;
        text-transform: uppercase;
    }

    .product:hover .card-info {
        opacity: 1;
    }

    .rounded-15px {
        border-radius: 15px !important;
    }
</style>
<section class="gry-bg py-4 profile h-100">
    <div class="container-fluid h-100" id="app"></div>
</section>
@endsection
@section('modal')
<!-- Address Modal -->
<div id="new-customer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header bord-btm">
                <h4 class="modal-title h6">Agregar Nuevo Cliente</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="shipping_form">
                <div class="modal-body" id="shipping_address">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" for="name">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Nombre" id="name" name="name" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="email">Correo electrónico</label>
                            <div class="col-sm-10">
                                <input type="email" placeholder="Correo electrónico" id="email" name="email" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="address">Dirección</label>
                            <div class="col-sm-10">
                                <textarea placeholder="Dirección" id="address" name="address" class="form-control" required=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label">País</label>
                            <div class="col-sm-10">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="Selecciona tu pais" name="country_id" required="">
                                    <option value="">Selecciona tu pais</option>
                                    <option value="61">Haiti</option>
                                    <option value="61">República Dominicana </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2 control-label">
                        <label>Estado</label>
                        </div>
                        <div class=" col-sm-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required="">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2">
                                <label>Ciudad</label>
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required="">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="postal_code">Código Postal</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" placeholder="Código Postal" id="postal_code" name="postal_code" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="phone">Teléfono</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" placeholder="Teléfono" id="phone" name="phone" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-styled btn-soft-dark rounded-15px" data-dismiss="modal" id="close-button">Cerrar</button>
                <button type="button" class="btn btn-primary btn-styled btn-base-1 rounded-15px" id="confirm-address" data-dismiss="modal">{{ translate('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- AGREGAR SERVICIOS MODAL -->
<div id="add_servicios" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header bord-btm" style="background-color: lightgrey; border-radius: 15px 15px 0 0;">
                <h4 class="modal-title h6">Agregar Servicios de Taller</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding: 50px 20px !important;">
                <div class="form-group">
                    <div class=" row">
                        <label class="col-sm-4 control-label">Tipo de Servicio</label>
                        <div class="col-sm-8">
                            <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="Selecciona el Servicio" name="country_id" required="">
                                <option value="">Selecciona el Servicio</option>
                                <option value="Chequeo">Chequeo </option>
                                <option value="Mantenimiento">Mantenimiento </option>
                                <option value="Instalación ">Instalación </option>
                                <option value="Reparación">Reparación </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class=" row">
                        <label class="col-sm-4 control-label" for="offline_payment_amount">Costo del Servicio</label>
                        <div class="col-sm-8">
                            <input placeholder="RD$" id="offline_payment_amount" name="offline_payment_amount" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-4 control-label" for="trx_id">Comentarios</label>
                    <div class="col-md-8">
                        <textarea placeholder="Comentarios" id="comentarios" name="comentarios" class="form-control" required=""></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-base-3 rounded-15px" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="#" class="btn btn-styled btn-base-1 btn-success rounded-15px" data-dismiss="modal">{{ translate('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- ORDEN CONFIRMADA MODAL -->
<div id="order-confirm-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content" id="variants">
            <div class="modal-header bord-btm" style="flex-direction: column-reverse !important; color: #0abb75;">
                <h4 class="modal-title h4">Pedido Confirmado!</h4>
            </div>
            <div class="modal-body" id="order-confirmation">
                <div class="p-4 text-center">
                    <i class="lar la-check-circle" style="font-size: 4.5rem; color: #0abb75;"></i>
                    <p style="font-size: 16px; font-weight: bold;">El pedido se confirmó correctamente:</p>
                    <p style="text-align: justify;">Recuerde, si su cliente es nuevo, debe indicarle que revise el WhatsApp y el Correo Electrónico agregado, donde confirme el registro a <span style="color: #003B73; font-weight: bold;">La Pieza.<span style="color: #E63108;">DO</span></span>, siguiendo los pasos indicados en los mensajes, para poder acceder a los medios de pago disponibles en la plataforma y pueda recibir los pagos de los sevicios y piezas cotizados anteriormente.</p><br>
                    <p style="text-align: justify;">En caso de que el cliente ya este registrado, indicarle que vaya a su perfil en <span style="color: #003B73; font-weight: bold;">La Pieza.<span style="color: #E63108;">DO</span></span>, para completar la orden generada.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-base-1 btn-info rounded-15px">Finalizar</button>
            </div>
        </div>
    </div>
</div>
<!-- ORDEN CONFIRMADA MODAL -->

<!-- REGISTRO CONFIRMADO MODAL -->
<div id="register-confirm-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content" id="variants">
            <div class="modal-header bord-btm" style="flex-direction: column-reverse !important; color: #0abb75;">
                <h4 class="modal-title h4">Cliente Registrado Correctamente!</h4>
            </div>
            <div class="modal-body" id="order-confirmation">
                <div class="p-4 text-center">
                    <i class="lar la-check-circle" style="font-size: 4.5rem; color: #0abb75;"></i>
                    <p style="font-size: 16px; font-weight: bold;">El Registro se ha completado correctamente!</p>
                    <p style="text-align: justify;">Favor indicar al cliente que revise el WhatsApp y el Correo Electrónico agregado, donde confirme el registro a <span style="color: #003B73; font-weight: bold;">La Pieza.<span style="color: #E63108;">DO</span></span>, siguiendo los pasos indicados en los mensajes, para poder acceder a los medios de pago disponibles en la plataforma y pueda recibir los pagos de los sevicios y piezas cotizados anteriormente.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-base-1 btn-info rounded-15px">Finalizar</button>
            </div>
        </div>
    </div>
</div>
<!-- REGISTRO CONFIRMADO MODAL -->


<!-- new address modal -->
<div id="new-address-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header bord-btm">
                <h4 class="modal-title h6">{{ translate('Shipping Address') }}</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="form-horizontal" action="{{ route('addresses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="rounded-15p" name="customer_id" id="set_customer_id" value="">
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="address">{{ translate('Address') }}</label>
                            <div class="col-sm-10">
                                <textarea placeholder="{{ translate('Address') }}" id="address" name="address" class="form-control rounded-15p" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label">{{ translate('Country') }}</label>
                            <div class="col-sm-10">
                                <select class="form-control aiz-selectpicker rounded-15p" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                    <option value="">{{ translate('Select your country') }}</option>
                                    @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2 control-label ">
                                <label>{{ translate('State') }}</label>
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-15p" data-live-search="true" name="state_id" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2">
                                <label>{{ translate('City') }}</label>
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-15p" data-live-search="true" name="city_id" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="postal_code">{{ translate('Postal code') }}</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" placeholder="{{ translate('Postal code') }}" id="postal_code" name="postal_code" class="form-control rounded-15p" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-2 control-label" for="phone">{{ translate('Phone') }}</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" placeholder="{{ translate('Phone') }}" id="phone" name="phone" class="form-control rounded-15p" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3 rounded-25px" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-styled btn-base-1 rounded-25px">{{ translate('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="order-confirm" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-xl">
        <div class="modal-content" id="variants">
            <div class="modal-header bord-btm">
                <h4 class="modal-title h6">{{ translate('Order Summary') }}</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="order-confirmation">
                <div class="p-4 text-center">
                    <i class="las la-spinner la-spin la-3x"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-base-3" data-dismiss="modal">{{ translate('Close') }}</button>
                <button type="button" onclick="oflinePayment()" class="btn btn-base-1 btn-warning">{{ translate('Offline Payment') }}</button>
                <button type="button" onclick="submitOrder('cash_on_delivery')" class="btn btn-base-1 btn-info">{{ translate('Confirm with COD') }}</button>
                <button type="button" onclick="submitOrder('cash')" class="btn btn-base-1 btn-success">{{ translate('Confirm with Cash') }}</button>
            </div>
        </div>
    </div>
</div>
{{-- Offline Payment Modal --}}
<div id="offlin_payment" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header bord-btm">
                <h4 class="modal-title h6">{{ translate('Offline Payment Info') }}</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class=" row">
                        <label class="col-sm-3 control-label" for="offline_payment_method">{{ translate('Payment Method') }}</label>
                        <div class="col-sm-9">
                            <input placeholder="{{ translate('Name') }}" id="offline_payment_method" name="offline_payment_method" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class=" row">
                        <label class="col-sm-3 control-label" for="offline_payment_amount">{{ translate('Amount') }}</label>
                        <div class="col-sm-9">
                            <input placeholder="{{ translate('Amount') }}" id="offline_payment_amount" name="offline_payment_amount" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 control-label" for="trx_id">{{ translate('Transaction ID') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control mb-3" id="trx_id" name="trx_id" placeholder="{{ translate('Transaction ID') }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ translate('Payment Proof') }}</label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    {{ translate('Browse') }}
                                </div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose image') }}</div>
                            <input type="hidden" name="payment_proof" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-base-3 rounded-25px" data-dismiss="modal">{{ translate('Close') }}</button>
                <button type="button" onclick="submitOrder('offline_payment')" class="btn btn-styled btn-base-1 btn-success rounded-25px">{{ translate('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    const url = "{{ config('app.url') }}";
</script>
<script src="{{ asset('public/assets/js/seller/workshop/pos.prod.js') }}" type="module"></script>
@endsection
