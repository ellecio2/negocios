<div class="aiz-topbar-item ml-2">
    <div class="align-items-stretch d-flex dropdown">
        <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button"
           aria-haspopup="false" aria-expanded="false">
            <span class="btn btn-icon p-0 d-flex justify-content-center align-items-center">
                <span class="d-flex align-items-center position-relative">
                    <i class="las la-bell fs-24"></i>
                    @if($notificationsCount > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="padding: 6px">{{$notificationsCount}}</span>
                    @endif
                </span>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">
            <div class="notifications" style="max-height: 300px; overflow-y: scroll;">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" data-type="order" href="#orders-notifications"
                           role="tab" id="orders-tab">{{ translate('Orders') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="orders-notifications" role="tabpanel">
                        {{--@forelse($this->notifications as $notification)--}}
                        @forelse($this->notifications->filter(function ($notification) {
                            return auth()->user()->user_type == 'seller' &&
                                   isset($notification->data['status']) &&
                                   $notification->data['status'] == 'confirmed';
                        }) as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="media text-inherit">
                                    <div class="media-body">
                                        <p class="mb-1 text-truncate-2">
                                            @php $user_type = auth()->user()->user_type; @endphp
                                            @if ($notification->type == 'App\Notifications\OrderNotification')
                                                @php
                                                    if ($user_type == 'admin'){
                                                        $route = route('all_orders.show', encrypt($notification->data['order_id']));
                                                    }
                                                    if ($user_type == 'seller'){
                                                        $route = route('seller.orders.show', encrypt($notification->data['order_id']));
                                                    }
                                                @endphp
                                                @if($notification->data['status'] != 'on_the_way')
                                                    <a href="{{ $route }}">{{ translate('Order code: ') }}
                                                        : {{ $notification->data['order_code'] }}
                                                        - {{ translate(' has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}</a>
                                                @else
                                                    <a href="{{ $route }}">{{ translate('Order code: ') }}
                                                        : {{ $notification->data['order_code'] }}
                                                        - Se encuentra en camino</a>
                                                @endif
                                            @elseif ($notification->type == 'App\Notifications\ShopVerificationNotification')
                                                @if ($user_type == 'admin')
                                                    @if ($is_linkable)
                                                        <a href="{{ route('sellers.show_verification_request', $notification->data['id']) }}">
                                                            @endif
                                                            {{ $notification->data['name'] }}:
                                                            @if ($is_linkable)
                                                        </a>
                                                    @endif
                                                @else
                                                    {{ translate('Your ') }}
                                                @endif
                                                {{ translate('verification request has been '.$notification->data['status']) }}
                                            @elseif ($notification->type == 'App\Notifications\ShopProductNotification')
                                                @php
                                                    $product_id     = $notification->data['id'];
                                                    $product_type   = $notification->data['type'];
                                                    $product_name   = $notification->data['name'];
                                                    $lang           = env('DEFAULT_LANGUAGE');
                                                    $route = $user_type == 'admin'
                                                            ? ( $product_type == 'physical'
                                                                ? route('products.seller.edit', ['id'=>$product_id, 'lang'=>$lang])
                                                                : route('digitalproducts.edit', ['id'=>$product_id, 'lang'=>$lang] ))
                                                            : ( $product_type == 'physical'
                                                                ? route('seller.products.edit', ['id'=>$product_id, 'lang'=>$lang])
                                                                : route('seller.digitalproducts.edit',  ['id'=>$product_id, 'lang'=>$lang] ));
                                                @endphp
                                                {{ translate('Product : ') }}
                                                @if ($is_linkable)
                                                    <a href="{{ $route }}">{{ $product_name }}</a>
                                                @else
                                                    {{ $product_name }}
                                                @endif
                                                {{ translate(' is').' '.$notification->data['status'] }}
                                            @elseif ($notification->type == 'App\Notifications\PayoutNotification')
                                                @php
                                                    $route = $user_type == 'admin'
                                                            ? ( $notification->data['status'] == 'pending' ? route('withdraw_requests_all') : route('sellers.payment_histories'))
                                                            : ( $notification->data['status'] == 'pending' ? route('seller.money_withdraw_requests.index') : route('seller.payments.index'));
                                                @endphp
                                                {{ $user_type == 'admin' ? $notification->data['name'].': ' : translate('Your') }}
                                                @if ($is_linkable )
                                                    <a href="{{ $route }}">{{ translate('payment') }}</a>
                                                @else
                                                    {{ translate('payment') }}
                                                @endif
                                                {{ single_price($notification->data['payment_amount']).' '.translate('is').' '.translate($notification->data['status']) }}
                                            @endif
                                        </p>
                                        <small class="text-muted">
                                            {{ date('F j Y, g:i a', strtotime($notification->created_at)) }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="py-4 text-center fs-16">
                                    {{ translate('No notification found') }}
                                </div>
                            </li>
                        @endforelse

                    </div>
                </div>
            </div>
            <div class="text-center border-top d-flex flex-column">
                <a href="{{ route('seller.all-notification') }}" class="text-reset d-block py-2">
                    {{translate('View All Notifications')}}
                </a>
                <a wire:click.prevent="markAllAsRead()" class="text-reset d-block py-2 c-pointer">
                    Marcar todas como leidas
                </a>
            </div>
        </div>
    </div>
    <script>
        const route = "{{ route('seller.orders.index') }}";
        console.log(route)
        document.addEventListener('livewire:load', function () {
            Livewire.on('newNotification', (count, filteredData) => {
                console.log(count, filteredData)
                if (filteredData[0].data.status === 'confirmed') {
                    Swal.fire({
                        title: "¡Nueva Notificación!",
                        //text: "Tienes " + count + " Orden(es) sin verificar",
                        html: `Tienes <span style="color: #003b73; font-weight: bold;">${count}</span> Orden(es) sin verificar`,
                        type: "info",
                        showConfirmButton: true,
                        confirmButtonText: "Ver"
                    }).then((result) => {
                        console.log(1);
                        window.location.replace(route);
                        if (result.isConfirmed) {
                            console.log(2)
                            // Usamos replace para asegurar la redirección
                            window.location.replace(route);
                        }
                    });
                }
            });
        });
    </script>
</div>

{{--<div class="aiz-topbar-item ml-2">
    <div class="align-items-stretch d-flex dropdown">
        <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button"
           aria-haspopup="false" aria-expanded="false">
            <span class="btn btn-icon p-0 d-flex justify-content-center align-items-center">
                <span class="d-flex align-items-center position-relative">
                    <i class="las la-bell fs-24"></i>
                        @if($this->notifications->filter(function ($notification) {
                            return auth()->user()->user_type == 'seller' &&
                                   isset($notification->data['status']) &&
                                   $notification->data['status'] == 'confirmed'; })->isNotEmpty())
                        <span class="badge badge-sm badge-primary position-absolute absolute-top-right">
                            <i class="las la-bell"></i>
                        </span>
                    @endif
                </span>
            </span>
        </a>

        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">
            <div class="notifications" style="max-height: 300px; overflow-y: scroll;">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" data-type="order" href="#orders-notifications"
                           role="tab" id="orders-tab">{{ translate('Orders') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="orders-notifications" role="tabpanel">
                        @forelse($this->notifications->filter(function ($notification) {
                                return auth()->user()->user_type == 'seller' &&
                                       isset($notification->data['status']) &&
                                       $notification->data['status'] == 'confirmed';
                            }) as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="media text-inherit">
                                    <div class="media-body">
                                        <p class="mb-1 text-truncate-2">
                                            @if ($notification->type == 'App\Notifications\OrderNotification')
                                                @php
                                                    $route = route('seller.orders.show', encrypt($notification->data['order_id']));
                                                @endphp
                                                <a href="{{ $route }}">
                                                    {{ translate('Order code: ') }}
                                                    : {{ $notification->data['order_code'] }}
                                                    - {{ translate(' has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                </a>
                                            @endif
                                        </p>
                                        <small class="text-muted">
                                            {{ date('F j Y, g:i a', strtotime($notification->created_at)) }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="py-4 text-center fs-16">
                                    {{ translate('No notification found') }}
                                </div>
                            </li>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="text-center border-top d-flex flex-column">
                <a href="{{ route('seller.all-notification') }}" class="text-reset d-block py-2">
                    {{translate('View All Notifications')}}
                </a>
                <a wire:click.prevent="markAllAsRead()" class="text-reset d-block py-2 c-pointer">
                    Marcar todas como leidas
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('newNotification', (code) => {
                if(code.status === 'pending'){
                    Swal.fire({
                        title: "¡Nueva Notificación!",
                        text: "Tienes una nueva Orden Confirmada con el código: " + code,
                        type: "info",
                        showConfirmButton: true,
                        confirmButtonText: "Confirmar"
                    });
                }
            });
        });

    </script>
</div>--}}

