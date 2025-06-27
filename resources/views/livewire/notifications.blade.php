<div>
    @php use Carbon\Carbon; @endphp
    <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
        <li class="list-inline-item ml-3 mr-3 pr-3 pl-0 dropdown">
            <a class="dropdown-toggle no-arrow text-secondary fs-12"
               data-toggle="dropdown"
               href="javascript:void(0);"
               role="button"
               aria-haspopup="false"
               aria-expanded="false">
            <span class="">
                <span
                    class="position-relative d-inline-block @if(auth()->user()->unreadNotifications->count() > 0) text-primary @else text-secondary @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                         viewBox="0 0 14.668 16">
                        <path id="_26._Notification" data-name="26. Notification"
                              d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                              transform="translate(-0.999)" fill="currentColor"/>
                    </svg>
                    @if( count( auth()->user()->unreadNotifications ) > 0 )
                        <span
                            class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count( auth()->user()->unreadNotifications ) }}</span>
                    @endif
                </span>
            </span>
            </a>
            @auth
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xl py-0 border-bottom-2l">
                    <div class="p-3 bg-light border-bottom">
                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                    </div>
                    <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                        <ul class="list-group list-group-flush">
                            @forelse($this->notifications as $notification)
                                @php
                                    $date = Carbon::parse($notification->created_at)->locale('es');
                                @endphp
                                <li @class([
                                'list-group-item',
                                'd-flex',
                                'justify-content-between',
                                'lh-condensed',
                                'my-1',
                                'bg-black-10' => $notification->read_at == null,
                                ]) wire:click="readNotification( '{{ $notification->id }}' )">
                                    @if ($notification->type == 'App\Notifications\OrderNotification')
                                        @if (auth()->user()->user_type == 'customer')
                                            <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                               class="w-100 text-secondary d-flex align-items-center justify-content-between">
                                            <span
                                                class="border p-2 rounded-circle bg-primary d-flex align-items-center justify-content-center w-50px h-50px">
                                                <i class="las la-people-carry text-white fs-24"></i>
                                            </span>
                                                <div class="w-75">
                                                    <h6 class="my-0 text-dark fs-4 font-weight-bold">{{ translate($notification->data['status']) }}</h6>
                                                    <small class="text-muted d-block w-100 mb-0 mt-1">Estamos procesando
                                                        tu orden No. </small>
                                                    <small
                                                        class="text-muted font-weight-bold">{{ $notification->data['order_code'] }}</small>
                                                    <small
                                                        class="text-muted text-right d-block w-100">{{ $date->diffForHumans()  }}</small>
                                                </div>
                                            </a>
                                        @elseif (Auth::user()->user_type == 'seller')
                                            <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                               class="w-100 text-secondary d-flex align-items-center justify-content-between">
                                            <span
                                                class="border p-2 rounded-circle bg-primary d-flex align-items-center justify-content-center w-50px h-50px">
                                                <i class="las la-money-bill-wave text-white fs-24"></i>
                                            </span>
                                                <div class="w-75">
                                                    <h6 class="my-0 text-dark fs-4 font-weight-bold">Pedido
                                                        Confirmado</h6>
                                                    <small class="text-muted d-block w-100 mb-0 mt-1">Has recibido una
                                                        nueva orden No. </small>
                                                    <small
                                                        class="text-muted font-weight-bold">{{ $notification->data['order_code'] }}</small>
                                                    <small
                                                        class="text-muted text-right d-block w-100">{{ $date->diffForHumans()  }}</small>
                                                </div>
                                            </a>
                                        @endif
                                    @endif
                                </li>
                            @empty
                                <li class="list-group-item">
                                    <div class="py-4 text-center fs-16">
                                        No hay notificaciones
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="text-center border-top d-flex px-2 justify-content-between align-items-center mt-2">
                        <a href="{{ route('all-notifications') }}"
                           class="fs-13 px-3 py-1 d-inline-block fw-700 text-negro header_menu_links hov-bg-black-10 hover-article">
                            {{ translate('View All') }}
                        </a>
                        <button wire:click.prevent="markAllAsRead()"
                                class="text-secondary fs-12 fw-700 text-negro d-block py-1 px-3 btn p-0 hover-article">
                            Marcar todas como leídas
                        </button>
                    </div>
                </div>
            @endauth
        </li>
    </ul>
</div>

