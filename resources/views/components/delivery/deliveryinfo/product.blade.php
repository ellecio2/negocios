<li class="list-group-item product">
    <div class="row align-items-center justify-content-between mb-3">
        {{-- Informacion del producto --}}
        <div class="col-12 col-lg-auto my-2">
            <div class="d-flex align-items-center">
                <span class="mr-2 mr-md-3">
                    <img src="{{ $product->thumbnail != null ? my_asset($product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                        class="img-fit size-60px" alt="{{ $product->getTranslation('name') }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </span>
                <span class="fs-14 fw-400 text-dark">
                    {{ $product->getTranslation('name') }}
                </span>
            </div>
        </div>
        <div class="col-12 col-lg-auto my-2">
            @if(!$deliveryOptions->pedidosYa['available'] || !$deliveryOptions->transporteBlanco['available'])
                <h6 class="bg-bann-lapieza text-white fs-12 text-center rounded-3 font-weight-bold px-3 py-2"> <span class="d-block">¡Este producto es demasiado grande!</span>Unicamente puedes pasarlo a recoger con el proveedor</h6>
            @endif
        </div>
    </div>
    {{-- Informacion de deliveries --}}
    <div class="row">
        @if ($physical)
            <div class="col-12 margib">
                <div class="row w-100 glutters-5 align-items-center justify-content-end">
                    @if($deliveryOptions->pedidosYa['available'] || $deliveryOptions->transporteBlanco['available'])
                        <!-- Home Delivery | Opcion de radio para seleccionar Delivery -->
                        <div class="col-3 col-333">
                            <label class="aiz-megabox d-block bg-white mb-0">
                                <input
                                    type="radio"
                                    class="delivery-radio"
                                    name="shipping-type-product-{{ $product->id }}"
                                    onchange="show_pickup_point(this, 'home_delivery')"
                                    data-product-id="{{ $product->id }}"
                                    data-target=".pickup-point-product-id-{{ $product->id }}"
                                    value="home_delivery"
                                    checked
                                />
                                <span class="d-flex p-3 aiz-megabox-elem rounded-15px" style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                <span class="flex-grow-1 pl-3 fw-600">{{ translate('Home Delivery') }}</span>
                            </span>
                            </label>
                        </div>
                    @endif
                    <!-- Local Pickup | Revisa si hay puntos fisicos disponibles y muestra la opcion en caso de haber -->
                    @if($pickupPoints)
                        <div class="col-3 col-333">
                            <label class="aiz-megabox d-block bg-white mb-0">
                                <input
                                    type="radio"
                                    class="pickup-radio"
                                    name="shipping-type-product-{{ $product->id }}"
                                    data-product-id="{{ $product->id }}"
                                    value="pickup-point"
                                    onchange="show_pickup_point(this, 'pickup_point')"
                                    data-target=".pickup-point-product-id-{{ $product->id }}"
                                />
                                <span class="d-flex p-3 aiz-megabox-elem rounded-15px" style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                <span class="flex-grow-1 pl-3 fw-600">{{ translate('Local Pickup') }}</span>
                            </span>
                            </label>
                        </div>
                    @endif
                </div>
                <div class="row justify-content-end align-items-center">
                    @if ($pickupPoints)
                        <div class="mt-4 d-none pickup-point-product-id-{{ $product->id }} d-none col-5">
                            <select class="form-control aiz-selectpicker rounded-0" name="pickup-point-product-id-{{ $product->id }}" data-live-search="true">
                                <option selected disabled>{{ translate('Select your nearest pickup point') }}</option>
                                @foreach ($pickupPoints as $pick_up_point)
                                    <option value="{{ $pick_up_point->id }}" data-pickup-point="{{ $pick_up_point }}" data-content="<span class='d-block'>
                                    <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                    <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                    <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                </span>"></option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if($deliveryOptions->pedidosYa['available'] || $deliveryOptions->transporteBlanco['available'])
        <div class="row align-items-center justify-content-between mt-4 mb-3" id="delivery-options-product-{{ $product->id }}">
            <div class="col-3 col-333 text-center delivery-message">
                <h6 class="fs-14 fw-700">{{ translate('Choose Delivery Type') }}</h6>
            </div>

            {{-- Pedidos Ya --}}
            @if($deliveryOptions->pedidosYa['available'])
                <x-delivery.deliveryinfo.product.delivery-option
                    icon="https://www.ccs.cl/wp-content/uploads/elementor/thumbs/pedidosYa-plmjvruvp3jg7e0259bm25wctawl3c6bc3jlbunbi8.jpg"
                    delivery-name="PedidosYa"
                    :data="$deliveryOptions->pedidosYa"
                />
            @endif

            @if($deliveryOptions->transporteBlanco['available'])
                <x-delivery.deliveryinfo.product.delivery-option
                    icon="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS7KQAdv4xhTMGg5RtyCShGYnYvgs0PYDduuA&usqp=CAU"
                    delivery-name="Transporte Blanco"
                    :data="$deliveryOptions->transporteBlanco"
                />
            @endif
        </div>
    @endif
</li>
