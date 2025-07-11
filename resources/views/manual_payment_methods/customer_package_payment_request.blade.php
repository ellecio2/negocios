@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="mb-0 h6">{{translate('Offline Customer Package Payment Requests')}}</h3>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Method')}}</th>
                    <th>{{translate('TXN ID')}}</th>
                    <th>{{translate('Reciept')}}</th>
                    <th>{{translate('Approval')}}</th>
                    <th>{{translate('Date')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($package_payment_requests as $key => $package_payment_request)
                    @if($package_payment_request->user != null && $package_payment_request->customer_package != null)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $package_payment_request->user->name }}</td>
                            <td>{{ $package_payment_request->customer_package->getTranslation('name') }}</td>
                            <td>{{ $package_payment_request->payment_method }}</td>
                            <td>{{ $package_payment_request->payment_details }}</td>
                            <td>
                                @if ($package_payment_request->reciept != null)
                                    <a href="{{ uploaded_asset($package_payment_request->reciept) }}" target="_blank">{{translate('Open Reciept')}}</a>
                                @endif
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    @if($package_payment_request->approval == 1)
                                        <input type="checkbox" checked disabled>
                                    @else
                                        <input 
                                            @can('approve_offline_customer_package_payment') onchange="offline_payment_approval(this)" @endcan
                                            id="payment_approval" type="checkbox"
                                            value="{{ $package_payment_request->id }}" 
                                            @cannot('approve_offline_customer_package_payment') disabled @endcan
                                        >
                                    @endif
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>{{ $package_payment_request->created_at }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $package_payment_requests->links() }}
        </div>
    </div>
</div>

@endsection
@section('script')
    <script type="text/javascript">
        function offline_payment_approval(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('offline_customer_package_payment.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    $( "#payment_approval" ).prop( "disabled", true );
                    AIZ.plugins.notify('success', '{{ translate('Offline Customer Package Payment approved successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
