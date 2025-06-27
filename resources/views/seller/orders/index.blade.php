@php use App\Models\Order;use App\Models\OrderDetail; @endphp
@extends('seller.layouts.app')



@section('panel_content')
    <style>
        #btn-print-dia svg:hover,
        #btn-print svg:hover,
        #btn-print-mes svg:hover {
            cursor: pointer;
            transform: scale(1.1);
            transition: transform 0.2s ease-in-out, stroke 0.2s ease-in-out;
            stroke: #ffffff;
        }

    </style>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary py-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Ventas del dia') }}</span>
                            </p>
                            <h4 class="mb-0 text-white fs-20">
                                @php
                                    $total = 0;
                                    /*foreach ($today_orders as $detail) {
                                        $order = Order::find($detail->id);
                                        $orderDetails = $order->orderDetails;

                                       foreach ($orderDetails as $orderDetail) {
                                           $orden = $orderDetail->id;
                                           if ($orderDetail->order != null && $orderDetail->order->payment_status == 'paid') {
                                               $total += $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                                           }
                                       }
                                    }*/

                                $processedOrders = collect();
                                $total = 0;
                                foreach ($today_orders as $detail) {
                                    $order = Order::find($detail->id);
                                    $total += $order->grand_total;
                                    /*foreach ($orderDetails as $orderDetail) {
                                        if ($orderDetail->order != null && $orderDetail->order->payment_status == 'paid') {
                                            $total += $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                                        }
                                    }*/
                                }

                                @endphp
                                {{ single_price($total) }}
                            </h4>
                        </div>
                        <div class="col-auto text-right">
                            <a href="#" id="btn-print-dia">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40.001" fill="none"

                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="#0ABB75" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary py-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Ventas del mes') }}</span>
                            </p>
                            <h4 class="mb-0 text-white fs-20">
                                @php
                                    $total = 0;
                                    foreach ($current_month_orders as $detail) {
                                        $order = Order::find($detail->id);
                                        $total += $order->grand_total;
                                    }
                                @endphp
                                {{ single_price($total) }}
                            </h4>
                        </div>
                        <div class="col-auto text-right">
                            <a href="#" id="btn-print-mes">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40.001" fill="none"

                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="#0ABB75" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary py-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Ordenes del dia') }}</span>
                            </p>
                            <h4 class="mb-0 text-white fs-20">
                                {{ $today_orders->count() }}
                            </h4>
                        </div>
                        <div class="col-auto text-right">
                            {{--<a href="#" id="btn-print">--}}
                            {{--<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40.001" fill="none"

                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="#d9d9d9" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                            </svg>--}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 64 64">
                                <g id="Group_25" data-name="Group 25" transform="translate(-1561.844 1020.618)">
                                    <path id="Path_58" data-name="Path 58" d="M229.23,106.382h-12a6,6,0,0,0,0,12h12a6,6,0,0,0,0-12m0,10h-12a4,4,0,0,1,0-8h12a4,4,0,0,1,0,8" transform="translate(1370.615 -1127)" fill="#FFFFFF"></path>
                                    <path id="Path_59" data-name="Path 59" d="M213.73,117.882h24a1,1,0,0,1,0,2h-24a1,1,0,0,1,0-2" transform="translate(1372.115 -1115.5)" fill="#FFFFFF"></path>
                                    <path id="Path_60" data-name="Path 60" d="M210.23,117.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2" transform="translate(1367.615 -1116)" fill="#FFFFFF"></path>
                                    <line id="Line_1" data-name="Line 1" transform="translate(1578.047 -1014.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <line id="Line_2" data-name="Line 2" transform="translate(1609.643 -1014.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <path id="Path_61" data-name="Path 61" d="M213.73,123.882h24a1,1,0,0,1,0,2h-24a1,1,0,0,1,0-2" transform="translate(1372.115 -1109.5)" fill="#FFFFFF"></path>
                                    <path id="Path_62" data-name="Path 62" d="M210.23,123.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2" transform="translate(1367.615 -1110)" fill="#FFFFFF"></path>
                                    <path id="Path_63" data-name="Path 63" d="M213.73,129.882h24a1,1,0,0,1,0,2h-24a1,1,0,1,1,0-2" transform="translate(1372.115 -1103.5)" fill="#FFFFFF"></path>
                                    <path id="Path_64" data-name="Path 64" d="M210.23,129.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2" transform="translate(1367.615 -1104)" fill="#FFFFFF"></path>
                                    <line id="Line_3" data-name="Line 3" transform="translate(1609.643 -1015.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <line id="Line_4" data-name="Line 4" transform="translate(1578.047 -1015.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <path id="Path_65" data-name="Path 65" d="M265.23,116.382a8,8,0,0,0-8-8h-7.2a1,1,0,0,0,0,2h7.2a6,6,0,0,1,6,6v44a6,6,0,0,1-6,6h-48a6,6,0,0,1-6-6v-44a6,6,0,0,1,6-6h7.2a1,1,0,0,0,0-2h-7.2a8,8,0,0,0-8,8v44a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8Z" transform="translate(1360.615 -1125)" fill="#FFFFFF"></path>
                                </g>
                            </svg>

                            {{--</a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary py-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Ordenes del mes') }}</span>
                            </p>
                            <h4 class="mb-0 text-white fs-20">
                                {{ $current_month_orders->count() }}
                            </h4>
                        </div>
                        <div class="col-auto text-right">
                            {{--<a href="#" id="btn-print-dia">--}}
                            {{--<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40.001" fill="none"

                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="#d9d9d9" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                            </svg>--}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 64 64">
                                <g id="Group_25" data-name="Group 25" transform="translate(-1561.844 1020.618)">
                                    <path id="Path_58" data-name="Path 58" d="M229.23,106.382h-12a6,6,0,0,0,0,12h12a6,6,0,0,0,0-12m0,10h-12a4,4,0,0,1,0-8h12a4,4,0,0,1,0,8" transform="translate(1370.615 -1127)" fill="#FFFFFF"></path>
                                    <path id="Path_59" data-name="Path 59" d="M213.73,117.882h24a1,1,0,0,1,0,2h-24a1,1,0,0,1,0-2" transform="translate(1372.115 -1115.5)" fill="#FFFFFF"></path>
                                    <path id="Path_60" data-name="Path 60" d="M210.23,117.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2" transform="translate(1367.615 -1116)" fill="#FFFFFF"></path>
                                    <line id="Line_1" data-name="Line 1" transform="translate(1578.047 -1014.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <line id="Line_2" data-name="Line 2" transform="translate(1609.643 -1014.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <path id="Path_61" data-name="Path 61" d="M213.73,123.882h24a1,1,0,0,1,0,2h-24a1,1,0,0,1,0-2" transform="translate(1372.115 -1109.5)" fill="#FFFFFF"></path>
                                    <path id="Path_62" data-name="Path 62" d="M210.23,123.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2" transform="translate(1367.615 -1110)" fill="#FFFFFF"></path>
                                    <path id="Path_63" data-name="Path 63" d="M213.73,129.882h24a1,1,0,0,1,0,2h-24a1,1,0,1,1,0-2" transform="translate(1372.115 -1103.5)" fill="#FFFFFF"></path>
                                    <path id="Path_64" data-name="Path 64" d="M210.23,129.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2" transform="translate(1367.615 -1104)" fill="#FFFFFF"></path>
                                    <line id="Line_3" data-name="Line 3" transform="translate(1609.643 -1015.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <line id="Line_4" data-name="Line 4" transform="translate(1578.047 -1015.618)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142"></line>
                                    <path id="Path_65" data-name="Path 65" d="M265.23,116.382a8,8,0,0,0-8-8h-7.2a1,1,0,0,0,0,2h7.2a6,6,0,0,1,6,6v44a6,6,0,0,1-6,6h-48a6,6,0,0,1-6-6v-44a6,6,0,0,1,6-6h7.2a1,1,0,0,0,0-2h-7.2a8,8,0,0,0-8,8v44a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8Z" transform="translate(1360.615 -1125)" fill="#FFFFFF"></path>
                                </g>
                            </svg>
                            {{--</a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">

        <form id="sort_orders" action="" method="GET">

            <div class="card-header row gutters-5">

                <div class="col text-center text-md-left">

                    <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>

                </div>

                <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">
                    <div class="aiz-topbar-item">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-success btn-lg align-items-center rounded-25px"
                               href="#" id="btn-print">
                                <i class="las la-print"></i> Imprimir Rango de Fechas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header row gutters-5">

                <div class="col-md-3 ml-auto">

                    <select class="form-control aiz-selectpicker"

                            data-placeholder="{{ translate('Filter by Payment Status') }}" name="payment_status"

                            onchange="sort_orders()">

                        <option value="">{{ translate('Filter by Payment Status') }}</option>

                        <option value="paid"

                                @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>

                            Pagado
                        </option>

                        <option value="unpaid"

                                @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>

                            No Pagado
                        </option>
                        <option value="all"

                                @isset($payment_status) @if ($payment_status == 'all') selected @endif @endisset>

                            Todas
                        </option>

                    </select>

                </div>

                <div class="col-md-3 ml-auto">

                    <select class="form-control aiz-selectpicker"

                            data-placeholder="{{ translate('Filter by Payment Status') }}" name="delivery_status"

                            onchange="sort_orders()">

                        <option value="">{{ translate('Filter by Deliver Status') }}</option>

                        <option value="pending"

                                @isset($delivery_status) @if ($delivery_status == 'pending') selected @endif @endisset>

                            {{ translate('Pending') }}</option>

                        <option value="confirmed"

                                @isset($delivery_status) @if ($delivery_status == 'confirmed') selected @endif @endisset>

                            {{ translate('Confirmed') }}</option>

                        <option value="on_the_way"

                                @isset($delivery_status) @if ($delivery_status == 'on_the_way') selected @endif @endisset>

                            {{ translate('On The Way') }}</option>

                        <option value="delivered"

                                @isset($delivery_status) @if ($delivery_status == 'delivered') selected @endif @endisset>

                            {{ translate('Delivered') }}</option>

                    </select>

                </div>

                <div class="col-md-3 ml-auto">

                    <div class="from-group mb-0">

                        <input type="text" class="form-control" id="search" name="search"

                               @isset($sort_search) value="{{ $sort_search }}" @endisset

                               placeholder="{{ translate('Type Order code & hit Enter') }}">

                    </div>

                </div>

                <div class="col-md-3 ml-auto">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm aiz-date-range" id="date_range"
                               name="date_range" @isset($date_range) value="{{ $date_range }}" @endisset
                               placeholder="{{ translate('Daterange') }}" autocomplete="off">
                        <button class="btn btn-primary btn-sm" type="button" id="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

            </div>

        </form>


        @if (count($orders) > 0)

            <div class="card-body p-3">

                <table class="table aiz-table mb-0">

                    <thead>

                    <tr>

                        <th>#</th>

                        <th>{{ translate('Order Code') }}</th>

                        <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>

                        <th data-breakpoints="lg">{{ translate('Customer') }}</th>

                        <th data-breakpoints="md">{{ translate('Amount') }}</th>

                        <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>

                        <th>{{ translate('Payment Status') }}</th>

                        <th class="text-right">{{ translate('Options') }}</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach ($orders as $key => $order_id)

                        @php

                            $order = Order::find($order_id->id);

                        @endphp

                        @if ($order != null)
                            <tr>

                                <td>

                                    {{ $key + 1 }}
                                    @if ($order->viewed == 0 && $order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-info">{{ translate('new') }}</span>
                                        {{--<span
                                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"></span>--}}
                                    @endif

                                </td>

                                <td>

                                    <a href="#{{ $order->code }}"

                                       onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>

                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')

                                        <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>

                                    @endif

                                </td>

                                <td>

                                    {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}

                                </td>

                                <td>

                                    @if ($order->user_id != null)

                                        {{ optional($order->user)->name }}

                                    @else

                                        {{ translate('Guest') }} ({{ $order->guest_id }})

                                    @endif

                                </td>

                                <td>

                                    {{ single_price($order->grand_total) }}

                                </td>

                                <td>

                                    @php

                                        $status = $order->delivery_status;

                                    @endphp

                                    {{ translate(ucfirst(str_replace('_', ' ', $status))) }}

                                </td>

                                <td>

                                    @if ($order->payment_status == 'paid')

                                        <span class="badge badge-inline badge-success">Pagado</span>

                                    @elseif($order->payment_status == 'declined')

                                        <span class="badge badge-inline badge-danger">Rechazado</span>

                                    @else

                                        <span class="badge badge-inline badge-danger">No Pagado</span>

                                    @endif

                                </td>

                                <td class="text-right">

                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')

                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"

                                           href="{{ route('seller.invoice.thermal_printer', $order->id) }}"

                                           target="_blank" title="{{ translate('Thermal Printer') }}">

                                            <i class="las la-print"></i>

                                        </a>

                                    @endif

                                    <a href="{{ route('seller.orders.show', encrypt($order->id)) }}"

                                       class="btn btn-soft-info btn-icon btn-circle btn-sm"

                                       title="{{ translate('Order Details') }}">

                                        <i class="las la-eye"></i>

                                    </a>

                                    <a href="{{ route('seller.invoice.download', $order->id) }}"

                                       class="btn btn-soft-warning btn-icon btn-circle btn-sm"

                                       title="{{ translate('Download Invoice') }}">

                                        <i class="las la-download"></i>

                                    </a>

                                </td>

                            </tr>

                        @endif

                    @endforeach

                    </tbody>

                </table>

                <div class="aiz-pagination">

                    {{--{{ $orders->links() }}--}}
                    {{ $orders->appends(['date_range' => request('date_range')])->links() }}

                </div>

            </div>

        @endif

    </div>

@endsection

@section('script')

    <script type="text/javascript">

        function sort_orders(el) {
            $('#sort_orders').submit();
        }

        let url = '{{ route('seller.orders.generatePDF') }}';
        //let orders = '{{ $orders1 }}';

        $(document).ready(function () {
            $('#search-btn').on('click', function () {
                let dateRange = $('#date_range').val();
                console.log(dateRange);

                if (dateRange.trim() === '') {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Debes seleccionar un rango de fechas',
                        timer: 6000
                    });
                    return;
                }
                $('#sort_orders').submit();
            });

            $("#btn-print-dia").on('click', function () {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        orders: 'dia'
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var blob = new Blob([response], {type: 'application/pdf'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.target = '_blank';
                        link.click();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al generar el PDF:', error);
                    }
                });
            });

            $("#btn-print-mes").on('click', function () {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        orders: 'mes'
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var blob = new Blob([response], {type: 'application/pdf'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.target = '_blank';
                        link.click();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al generar el PDF:', error);
                    }
                });
            });

            $("#btn-print").on('click', function () {
                const orders = @json($orders1->pluck('id'));
                let dateRange = $('#date_range').val();
                if (dateRange.trim() === '') {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Debes seleccionar un rango de fechas',
                        timer: 6000
                    });
                    return;
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        orders: orders,
                        fecha: dateRange
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var blob = new Blob([response], {type: 'application/pdf'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.target = '_blank';
                        link.click();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al generar el PDF:', error);
                    }
                });
            });
        });

    </script>

@endsection



