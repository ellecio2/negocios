@extends('seller.layouts.app')
@section('panel_content')
    <div class="card">
        <form class="" action="" id="sort_commission_history" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Commission History') }}</h5>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm aiz-date-range" id="search"
                               name="date_range" @isset($date_range) value="{{ $date_range }}"
                               @endisset placeholder="{{ translate('Daterange') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th data-breakpoints="lg">{{ translate('Order Code') }}</th>
                    <th>{{ __('Plataforma') }}</th>
                    <th>{{ __('Revendedor') }}</th>
                    <th>{{ __('ITBIS') }}</th>
                    <th>{{ translate('Earning') }}</th>
                    <th data-breakpoints="lg">{{ translate('Created At') }}</th>
                </tr>
                </thead>
                <tbody>
                @if($commission_history->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron datos</td>
                    </tr>
                @else
                    @foreach ($commission_history as $key => $history)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>
                                @if(isset($history->order))
                                    <a href="{{ route('seller.orders.show', encrypt($history->order_id)) }}"
                                       title="{{ translate('Order Details') }}">{{ $history->order->code }}</a>
                                @else
                                    <span class="badge badge-inline badge-danger">
                                    {{ translate('Order Deleted') }}
                                </span>
                                @endif
                            </td>
                            <td><b>RD ${{ number_format($history->total_admin_commission, 2) }}</b></td>
                            <td><b>RD ${{ number_format($history->total_re_seller_earning, 2) }}</b></td>
                            <td><b>RD ${{ number_format($history->total_itbis, 2) }}</b></td>
                            <td><b>RD ${{ number_format($history->total_seller_earning, 2) }}</b></td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <tfoot style="background-color: #2e294e; color: white;">
                @if(!$commission_history->isEmpty())
                    <tr>
                        <td>&nbsp;</td>
                        <td class="font-weight-bold fs-14">Totales:</td>
                        <td class="font-weight-bold fs-14">RD
                            ${{ number_format($commission_history->sum('total_admin_commission'), 2) }}</td>
                        <td class="font-weight-bold fs-14">RD
                            ${{ number_format($commission_history->sum('total_re_seller_earning'), 2) }}</td>
                        <td class="font-weight-bold fs-14">RD
                            ${{ number_format($commission_history->sum('total_itbis'), 2) }}</td>
                        <td class="font-weight-bold fs-14">RD
                            ${{ number_format($commission_history->sum('total_seller_earning'), 2) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
                </tfoot>
            </table>
            <div class="aiz-pagination mt-4">
                @if(!$commission_history->isEmpty())
                    {{ $commission_history->links() }}
                @endif
            </div>
        </div>
    </div>
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
        function sort_commission_history(el) {
            $('#sort_commission_history').submit();
        }

        function showModalData(value) {
            if (value) { // Valida que el valor no sea nulo o vacío
                $.post('{{ route('seller.orders.show', '') }}' + '/' + value, { // Agregar un valor dinámico
                    _token: AIZ.data.csrf,
                    order_id: value
                }, function (data) {
                    $('#order-details-modal-body').html(data);
                    $('#order_details').modal();
                    $('.c-preloader').hide();
                    AIZ.plugins.bootstrapSelect('refresh');
                });
            }
        }
    </script>
@endsection
