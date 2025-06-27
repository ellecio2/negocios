@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    @include('components.delivery.steps', ['step' => 5])

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first();
                    @endphp
                        <!-- Order Confirmation Text-->
                    <div class="text-center py-4 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36"
                             class=" mb-3">
                            <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                                <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18"
                                        transform="translate(978 481)" fill="#85b567"/>
                                <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                    <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3"
                                          rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                    <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18"
                                          rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                                </g>
                            </g>
                        </svg>
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 text-soft-dark">{{  translate('A copy or your order summary has been sent to') }}
                            <strong>{{ json_decode($first_order->shipping_address)->email }}</strong></p>
                    </div>
                    <!-- Orders Summary -->
                    <div class="card shadow-none border rounded-15px">
                        <div class="card-body">
                            <!-- Order Details -->
                            <div>
                                <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">{{ translate('Order Details')}}</h5>
                                <!-- Product Details -->
                                <div class="row">
                                    {{-- Solamente se muestra si el producto tiene la categoria vehiculo --}}
                                    @if($isAvailableToWorkshop)
                                        <div class="col-md-8">
                                            <!-- Contenido del primer div -->
                                            <div>
                                                <table class="table table-responsive-md text-soft-dark fs-14">
                                                    <thead>
                                                    <tr>
                                                        <th class="opacity-60 border-top-0 pl-0">#</th>
                                                        <th class="opacity-60 border-top-0"
                                                            width="30%">{{ translate('Product')}}</th>
                                                        <th class="opacity-60 border-top-0">{{ translate('Variation')}}</th>
                                                        <th class="opacity-60 border-top-0">{{ translate('Quantity')}}</th>
                                                        <th class="opacity-60 border-top-0">{{ translate('Delivery Type')}}</th>
                                                        <th class="text-right opacity-60 border-top-0 pr-0">{{ translate('Price')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($combined_order->orders as $order)
                                                        <!-- Order Code -->
                                                        <tr class="text-center py-1 mb-4">
                                                            <td class="fs-20 col-12"
                                                                colspan="6">{{ translate('Order Code:')}}
                                                                <span
                                                                    class="fw-700 fs-11 text-primary">{{ $order->code }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @foreach ($order->orderDetails as $key => $orderDetail)
                                                            <tr>
                                                                <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                                                <td class="border-top-0 border-bottom">
                                                                    @if ($orderDetail->product != null)
                                                                        <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                                           target="_blank" class="text-reset">
                                                                            {{ $orderDetail->product->getTranslation('name') }}
                                                                        </a>
                                                                    @else
                                                                        <strong>{{  translate('Product Unavailable') }}</strong>
                                                                    @endif
                                                                </td>
                                                                <td class="border-top-0 border-bottom">
                                                                    {{ $orderDetail->variation }}
                                                                </td>
                                                                <td class="border-top-0 border-bottom">
                                                                    {{ $orderDetail->quantity }}
                                                                </td>
                                                                <td class="border-top-0 border-bottom">
                                                                    @if ($order->shipping_type != null && $order->shipping_type == 'PEDIDOS YA')
                                                                        Pedidos Ya
                                                                    @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                                        {{  translate('Carrier') }}
                                                                    @elseif ($order->shipping_type == 'pickup_point')
                                                                        @if ($order->pickup_point != null)
                                                                            {{ $order->pickup_point->getTranslation('name') }}
                                                                            ({{ translate('Pickip Point') }})
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Order Amounts -->
                                            <div class="row">
                                                <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                                    <table class="table ">
                                                        <tbody>
                                                        <!-- Subtotal -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Subtotal')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                <span
                                                                    class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Shipping -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Shipping')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                {{--<span>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>--}}
                                                                <span>{{ single_price($order->orderDetails[0]->shipping_cost) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Tax -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Tax')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                <span>{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Coupon Discount -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Coupon Discount')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                <span>{{ single_price($order->coupon_discount) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Total -->
                                                        <tr>
                                                            <th class="py-2 fs-16"><span
                                                                    class="fw-600">{{ translate('Total')}}</span>
                                                            </th>
                                                            <td class="text-right pr-0 fs-16">
                                                                <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card" id="workshop-request-card" style="width: 18rem;">
                                                <img
                                                    src="https://img.freepik.com/vector-gratis/ilustracion-concepto-mecanico-automatico_114360-16748.jpg?w=740&t=st=1697213925~exp=1697214525~hmac=2698c0414da82035a6cfa025b87ab0738fc414cdb4ca4a9f214329b221552231"
                                                    alt="Taller mecánico" class="img-fluid">
                                                <div class="card-body">
                                                    <h5 class="card-title font-weight-bold text-center">
                                                        ¿Te lo instalamos?
                                                    </h5>
                                                    <p class="card-text">
                                                        Contamos con una amplia red de talleres
                                                        que pueden ofrecerte su servicio para la instalación
                                                        de este producto. Aprovecha esta oportunidad y descubre
                                                        excelentes ofertas.
                                                    </p>
                                                    <button
                                                        class="btn btn-primary d-block mx-auto font-weight-bold fs-16"
                                                        onclick="show_select_modal()" disabled>
                                                        Solicitar ahora
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card" id="workshop-request-confirmed-card"
                                                 style="width: 18rem;">
                                                <img
                                                    src="https://cdni.iconscout.com/illustration/premium/thumb/quality-check-4472445-3858250.png"
                                                    alt="Taller mecánico" class="img-fluid">
                                                <div class="card-body">
                                                    <h5 class="card-title font-weight-bold text-center">Gracias por tu
                                                        preferencia</h5>
                                                    <p class="card-text">
                                                        En estos momentos, te haremos llegar un mensaje via whatsapp al
                                                        numero <span
                                                            class="text-blue-lapieza">{{ auth()->user()->phone }}</span>.
                                                        Te haremos llegar las ofertas de nuestros talleres y podrás
                                                        decidir cuál es la mejor oferta según tus necesidades.
                                                        Al finalizar el proceso podrás encontrar mas detalles en tu
                                                        historial de compras <a
                                                            href="{{ route('purchase_history.index') }}"
                                                            class="text-primary">perfil</a>.
                                                    </p>

                                                    <button type="button" class="btn btn-primary" disabled>
                                                        Podrás realizar una nueva solicitud en 24 horas o bien cuando
                                                        termines el proceso actual.
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @else

                                        <div class="col-md-12">
                                            <div>
                                                <table class="table table-responsive-md text-soft-dark fs-14">
                                                    <thead>
                                                    <tr>
                                                        <th class="opacity-60 border-top-0 pl-0">#</th>
                                                        <th class="opacity-60 border-top-0"
                                                            width="30%">{{ translate('Product')}}</th>
                                                        <th class="opacity-60 border-top-0">{{ translate('Variation')}}</th>
                                                        <th class="opacity-60 border-top-0">{{ translate('Quantity')}}</th>
                                                        <th class="opacity-60 border-top-0">{{ translate('Delivery Type')}}</th>
                                                        <th class="text-right opacity-60 border-top-0 pr-0">{{ translate('Price')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($combined_order->orders as $order)
                                                        <!-- Order Code -->
                                                        <tr class="text-center py-1 mb-4">
                                                            <td class="fs-20 col-12"
                                                                colspan="6">{{ translate('Order Code:')}}
                                                                <span
                                                                    class="fw-700 fs-11 text-primary">{{ $order->code }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @foreach ($order->orderDetails as $key => $orderDetail)
                                                            <tr>
                                                                <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                                                <td class="border-top-0 border-bottom">
                                                                    @if ($orderDetail->product != null)
                                                                        <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                                           target="_blank" class="text-reset">
                                                                            {{ $orderDetail->product->getTranslation('name') }}
                                                                        </a>
                                                                    @else
                                                                        <strong>{{  translate('Product Unavailable') }}</strong>
                                                                    @endif
                                                                </td>
                                                                <td class="border-top-0 border-bottom">
                                                                    {{ $orderDetail->variation }}
                                                                </td>
                                                                <td class="border-top-0 border-bottom">
                                                                    {{ $orderDetail->quantity }}
                                                                </td>
                                                                <td class="border-top-0 border-bottom">
                                                                    @if ($order->shipping_type != null && $order->shipping_type == 'PEDIDOS YA')
                                                                        Pedidos Ya
                                                                    @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                                        {{  translate('Carrier') }}
                                                                    @elseif ($order->shipping_type == 'pickup_point')
                                                                        @if ($order->pickup_point != null)
                                                                            {{ $order->pickup_point->getTranslation('name') }}
                                                                            ({{ translate('Pickip Point') }})
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Order Amounts -->
                                            <div class="row">
                                                <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                                    <table class="table ">
                                                        <tbody>
                                                        <!-- Subtotal -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Subtotal')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                <span
                                                                    class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Shipping -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Shipping')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                {{--<span>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>--}}
                                                                <span>{{ single_price($order->orderDetails[0]->shipping_cost) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Tax -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Tax')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                <span>{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Coupon Discount -->
                                                        <tr>
                                                            <th class="border-top-0 py-2">{{ translate('Coupon Discount')}}</th>
                                                            <td class="text-right border-top-0 pr-0 py-2 fs-16">
                                                                <span>{{ single_price($order->coupon_discount) }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Total -->
                                                        <tr>
                                                            <th class="py-2 fs-16"><span
                                                                    class="fw-600">{{ translate('Total')}}</span>
                                                            </th>
                                                            <td class="text-right pr-0 fs-16">
                                                                <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Select Modal -->
        <div class="modal fade" id="select_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size"
                 role="document">
                <div class="modal-content position-relative">
                    <div class="modal-header">
                        <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">
                            Solicitar servicios de taller
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body px-3 pt-3">
                        <h3 class="fs-12 font-weight-bold">Seleccione los artículos que deseas instalar:</h3>
                        <span class="text-white pl-14px py-2 my-3 w-100 bg-danger d-none" id="alert">Debes seleccionar minimo un producto</span>
                        <div class="gry-bg p-3">
                            @foreach ($products_to_install as $key => $product)
                                <input type="checkbox" name="select_product_install"
                                       id="product-{{ $product['product']->id }}" required
                                       data-order-id="{{ $product['order_id'] }}"
                                       data-product-id="{{ $product['product']->id }}">
                                <label
                                    for="product-{{ $product['product']->id }}">{{ $product['product']->getTranslation('name') }}</label>
                            @endforeach
                        </div>

                        <div class="mt-5" style="margin-top: 5%;">
                            <label for="details" class="fs-12 font-weight-bold">Observaciones adicionales</label>
                            <textarea type="text" class="form-control mb-3 rounded-0" rows="3" name="details"
                                      id="details"
                                      placeholder="Escriba su respuesta"
                                      data-buttons="bold,underline,italic,|,ul,ol,|,paragraph,|,undo,redo"
                                      required style="resize: none"></textarea>
                            <h6 id="characters" class="d-block text-right fs-10 m-0 text-secondary">
                                <span>512</span> carácteres restante(s)
                            </h6>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="submit-button" class="btn btn-primary">Enviar Solicitud</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- Script Modal -->
    <script type="text/javascript">
        function show_select_modal() {
            $('#select_modal').modal('show');
        }
    </script>

    {{-- Workshop Request Script --}}
    <script type="text/javascript">
        window.addEventListener('load', async () => {
            const url = "{{ config('app.url') }}";
            const systemKey = "{{ config('app.system_key') }}";
            const workshopRequestCard = document.querySelector('#workshop-request-card');
            const workshopRequestConfirmedCard = document.querySelector('#workshop-request-confirmed-card');
            const requestWorkshopModal = document.querySelector('#select_modal');
            const submitButton = requestWorkshopModal.querySelector('#submit-button');
            const textArea = requestWorkshopModal.querySelector('textarea');
            const charactersCounter = requestWorkshopModal.querySelector('#characters').children[0];

            updateCharactersCounter();

            textArea.addEventListener('keydown', function (event) {
                // Si se ha llegado al límite y la tecla presionada no es Backspace o Delete,
                // entonces cancelamos el evento de presionar tecla
                if (textArea.value.length >= 512 && event.key !== 'Backspace' && event.key !== 'Delete') {
                    event.preventDefault();
                }
            });
            textArea.addEventListener('change', updateCharactersCounter);
            textArea.addEventListener('input', updateCharactersCounter);

            textArea.addEventListener('paste', function (event) {
                // Obtener texto del portapapeles
                let pasteData = event.clipboardData.getData('text');

                // Si el pegado llevará el texto por encima de 512 caracteres, cancelamos el evento de pegado
                if (textArea.value.length + pasteData.length > 512) {
                    event.preventDefault();
                }
            });


            submitButton.addEventListener('click', sendRequestWorkshopService);

            const response = await checkWorkshopRequestStatus();

            if (response) {
                workshopRequestCard.classList.add('d-none');
                workshopRequestConfirmedCard.classList.remove('d-none');
            } else {
                workshopRequestCard.classList.remove('d-none');
                workshopRequestConfirmedCard.classList.add('d-none');
            }

            async function checkWorkshopRequestStatus() {
                let response = await fetch(`${url}/api/v2/workshops/workshop-request-status`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'System-Key': systemKey
                    }
                }).catch(response => {
                    return response;
                });
                let data = await response.json();

                return data.userHasOpenedProcess;
            }

            async function sendRequestWorkshopService() {

                const inputs = requestWorkshopModal.querySelectorAll('input');
                const observations = requestWorkshopModal.querySelector('textarea')
                const products = Array.from(inputs);
                const alert = requestWorkshopModal.querySelector('#alert');

                const selectedProducts = products.filter(product => product.checked);
                disableButtonTemporary(submitButton)

                if (selectedProducts.length <= 0) {
                    alert.classList.remove('d-none');
                    alert.classList.add('d-block')

                    return;
                }

                if (!alert.classList.contains('d-none')) {
                    alert.classList.add('d-none');
                    alert.classList.remove('d-block');
                }

                const productsData = selectedProducts.map(product => {
                    return {
                        orderId: parseInt(product.dataset.orderId),
                        productId: parseInt(product.dataset.productId)
                    }
                })

                const body = {
                    description: observations.value,
                    productsData
                }

                console.log(body);

                let response = await fetch(`${url}/api/v2/workshops/request-service`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'System-Key': systemKey
                    },
                    body: JSON.stringify(body)
                }).catch(response => {
                    console.log(response)
                    return response;
                });
                let data = await response.json();

                console.log(data);

                if (data.message === "success") {
                    $('#select_modal').modal('hide');
                    workshopRequestCard.classList.add('d-none')
                    workshopRequestConfirmedCard.classList.remove('d-none');
                }
            }

            function disableButtonTemporary(button) {

                button.disabled = true;

                setTimeout(function () {
                    button.disabled = false;
                }, 3000);
            }

            function updateCharactersCounter() {
                if (textArea.value.length > 512) {
                    // limitamos el valor a los primeros 512 caracteres
                    textArea.value = textArea.value.substring(0, 512);
                }
                // actualizamos el contador
                let count = 512 - textArea.value.length;
                charactersCounter.textContent = count;
            }
        });

    </script>
    {{-- Workshop Request Script --}}

@endsection
