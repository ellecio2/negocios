@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('All Shipping Companies') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.shipping-companies.create') }}" class="btn btn-primary">
                <span>{{ translate('Add New Shipping Company') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Shipping Companies') }}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('WhatsApp Number') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shippingCompanies as $key => $company)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->whatsapp_number }}</td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('admin.shipping-companies.edit', $company->id) }}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('admin.shipping-companies.destroy', $company->id) }}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')
<script>
    $(document).ready(function() {
        console.log('Document is ready!');
        
        // Confirmación de eliminación con modal
        $('.confirm-delete').on('click', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $('#delete-link').attr('href', url);
            $('#delete-modal').modal('show');
        });

        // Opcional: Manejar la eliminación con AJAX para mejor experiencia
        $('#delete-link').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        $('#delete-modal').modal('hide');
                        location.reload(); // Recargar la página para ver los cambios
                    }
                },
                error: function(error) {
                    console.error(error);
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        });
    });
</script>
@endsection