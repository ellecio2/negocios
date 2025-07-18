@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card rounded-15p shadow-none border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark">{{ translate('Purchase History from Auction') }}</h5>
        </div>
        <div class="card-body py-0">
            <table class="table aiz-table mb-0">
                <thead class="text-gray fs-12">
                    <tr>
                        <th class="pl-0">{{ translate('Code') }}</th>
                        <th data-breakpoints="md">{{ translate('Date') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                        <th class="text-right pr-0">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody class="fs-14">
                    @foreach ($orders as $key => $order_id)
                        @php
                            $order = \App\Models\Order::find($order_id->id);
                        @endphp
                        <tr>
                            <td class="pl-0">
                                <a href="#{{ $order->code }}" onclick="show_purchase_history_details({{ $order->id }})"
                                    class="fw-700 text-primary">{{ $order->code }}</a>
                            </td>
                            <td class="text-secondary">{{ date('d-m-Y', $order->date) }}</td>
                            <td class="fw-700">
                                {{ single_price($order->grand_total) }}
                            </td>
                            <td class="fw-700">
                                {{ translate(ucfirst(str_replace('_', ' ', $order->orderDetails->first()->delivery_status))) }}
                                @if ($order->delivery_viewed == 0)
                                    <span class="ml-2" style="color:green"><strong>*</strong></span>
                                @endif
                            </td>
                            <td>
                                @if ($order->payment_status == 'paid')
                                    <span class="badge badge-inline badge-success p-3 fs-12"
                                        style="border-radius: 25px; min-width: 80px !important;">Pagado</span>
                                @else
                                    <span class="badge badge-inline badge-danger p-3 fs-12"
                                        style="border-radius: 25px; min-width: 80px !important;">No Pagado</span>
                                @endif
                                @if ($order->payment_status_viewed == 0)
                                    <span class="ml-2" style="color:green"><strong>*</strong></span>
                                @endif
                            </td>
                            <td class="text-right pr-0">
                                @if ($order->orderDetails->first()->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                    <a href="javascript:void(0)"
                                        class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white confirm-delete"
                                        data-href="{{ route('orders.destroy', $order->id) }}"
                                        title="{{ translate('Cancel') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12"
                                            viewBox="0 0 9.202 12">
                                            <path id="Path_28714" data-name="Path 28714"
                                                d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z"
                                                transform="translate(-6.478 -3.322)" fill="#d43533" />
                                        </svg>
                                    </a>
                                @endif
                                <a href="javascript:void(0)"
                                    class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white"
                                    onclick="show_purchase_history_details({{ $order->id }})"
                                    title="{{ translate('Order Details') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10"
                                        viewBox="0 0 12 10">
                                        <g id="Group_24807" data-name="Group 24807" transform="translate(-1339 -422)">
                                            <rect id="Rectangle_18658" data-name="Rectangle 18658" width="12"
                                                height="1" transform="translate(1339 422)" fill="#3490f3" />
                                            <rect id="Rectangle_18659" data-name="Rectangle 18659" width="12"
                                                height="1" transform="translate(1339 425)" fill="#3490f3" />
                                            <rect id="Rectangle_18660" data-name="Rectangle 18660" width="12"
                                                height="1" transform="translate(1339 428)" fill="#3490f3" />
                                            <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12"
                                                height="1" transform="translate(1339 431)" fill="#3490f3" />
                                        </g>
                                    </svg>
                                </a>
                                <a class="btn btn-soft-warning btn-icon btn-circle btn-sm hov-svg-white"
                                    href="{{ route('invoice.download', $order->id) }}"
                                    title="{{ translate('Download Invoice') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12.001"
                                        viewBox="0 0 12 12.001">
                                        <g id="Group_24807" data-name="Group 24807" transform="translate(-1341 -424.999)">
                                            <path id="Union_17" data-name="Union 17"
                                                d="M13936.389,851.5l.707-.707,2.355,2.355V846h1v7.1l2.306-2.306.707.707-3.538,3.538Z"
                                                transform="translate(-12592.95 -421)" fill="#f3af3d" />
                                            <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12"
                                                height="1" transform="translate(1341 436)" fill="#f3af3d" />
                                        </g>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#order_details').on('hidden.bs.modal', function() {
            location.reload();
        })
    </script>
@endsection
