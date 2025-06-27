@aware(['product'])

@if($deliveryName == 'Transporte Blanco')
    <div class="col-auto" id="transporte-blanco-product-id-{{ $product->id }}">
        <div class="card delivery-card" style="width: 15rem;">
            <img src="{{ $icon  }}" alt="Image" class="w-50px img-fit rounded-circle ml-4 mt-4 border-2 border-primary">
            <div class="card-body pt-1 text-blue-lapieza">
                <input type="radio" name="delivery_{{ $product->id }}" value="{{ $deliveryCheckoutInfo }}" class="position-absolute" style="top: 20px; right: 20px;" checked>
                <label for="radio" class="fw-bold h6 d-block">
                    {{ $deliveryName }}
                </label>
                <span class="d-block fs-6 fw-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                    </svg>
                    <span class="font-weight-bold"> {{ $data['dropOffPoint']->city }}</span>
                    <span class="d-block w-100 fw-bold">Estimado de ${{ $data['delivery']['starter_price'] }} a ${{ $data['delivery']['ending_price'] }}</span>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                    <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                </svg>
                <span class="font-weight-bold">Recíbelo</span>
                <h6 class="fs-12">{{ $estimatedTime }}</h6>
            </div>
        </div>
    </div>
@else

    <div class="col-auto" id="pedidos-ya-product-id-{{ $product->id }}">
        <div class="card delivery-card" style="width: 15rem;">

            <img src="{{ $icon  }}" alt="Image" class="w-50px img-fit rounded-circle ml-4 mt-4 border-2 border-primary">
{{--            <label for="radio" class="fw-bold h6 d-block">--}}
            <div class="card-body pt-1 text-blue-lapieza">
                <input type="radio" name="delivery_{{ $product->id }}" value="{{ $deliveryCheckoutInfo }}" class="position-absolute" style="top: 20px; right: 20px;" @if(!$data['storeAvailability']['delivery_now_available']) disabled @endif>
                <label for="radio" class="fw-bold h6 d-block">
                    {{ $deliveryName }}
                </label>
                <span class="d-block fs-6 fw-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                </svg>
                <span class="font-weight-bold"> {{ $data['dropOffPoint']->city }} </span>
                <span class="d-block w-100">Estimado de ${{ $data['delivery']['starter_price'] }} a ${{ $data['delivery']['ending_price'] }}</span>
            </span>
                @if($data['storeAvailability']['delivery_now_available'])
                    <span class="d-block fs-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                    </svg>
                    <span class="font-weight-bold">Recíbelo</span>
                    <h6 class="fs-12">{{ $estimatedTime }}</h6>
                </span>
                @else
                    <span class="d-block fs-8 text-danger">
                    <span>{{ $data['storeAvailability']['delivery_now_not_available_message'] }}</span>
                    <span> {{ $deliveryTime->diffForHumans() }}</span>
                </span>
                @endif
            </div>
           </label>
        </div>
    </div>
@endif

