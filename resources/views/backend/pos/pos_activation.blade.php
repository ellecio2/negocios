@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.css"/>
    <style>
        .nfc_vouchers_table_length {

        }
    </style>style
@endpush
@section('content')
    <h4 class="text-center text-muted">{{translate('POS Configuration')}}</h4>
    <div class="row">

        <div class="col-lg-4">
            <div class="card ">

                <div class="card-header">

                    <h5 class="mb-0 h6">{{ translate('POS Activation for Seller') }}</h5>

                </div>

                <div class="card-body text-center">

                    <label class="aiz-switch aiz-switch-success mb-0">

                        <input type="checkbox" onchange="updateSettings(this, 'pos_activation_for_seller')"

                               @if (get_setting('pos_activation_for_seller') == 1) checked @endif>

                        <span class="slider round"></span>

                    </label>

                </div>

            </div>
            <div class="card mt-2">

                <div class="card-header">

                    <h5 class="mb-0 h6">{{ translate('Thermal Printer Size') }}</h5>

                </div>

                <div class="card-body text-center">

                    <form class="form-horizontal" action="{{ route('seller_business_settings.update') }}" method="POST">

                        @csrf

                        <div class="form-group row">

                            <input type="hidden" name="types[]" value="print_width_seller_pos">

                            <div class="input-group mb-3">

                                <input type="text" class="form-control" name="print_width_seller_pos"
                                       placeholder="{{ translate('Print width in mm') }}"

                                       value="{{ get_setting('print_width_seller_pos') }}">

                                <div class="input-group-append">

                                    <span class="input-group-text" id="basic-addon2">{{ translate('mm') }}</span>

                                </div>

                            </div>

                        </div>

                        <div class="form-group mb-0 text-right">

                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    {{--<h5 class="mb-0 h6">{{ translate('Lista de Comprobantes Fiscales') }}</h5>--}}
                    <h5 class="mb-0 h6">Lista de Comprobantes Fiscales</h5>


                    <div class="float-right">

                        {{-- <a class="btn btn-info" href="{{ route('pos.configuration.collection') }}">Exportar (Collection)</a> --}}

                        <a class="btn btn-success" href="{{ route('pos.configuration.view') }}">Exportar en Excel</a>


                        <button class="btn btn-primary" data-toggle="modal" data-target="#agregarModal">Actualizar
                        </button>


                    </div>
                </div>


                <div class="card-body ">

                    <table id="nfc_vouchers_table" class="table table-striped">

                        <thead>

                        <tr>
                            <th>Tipo NCF</th>
                            <th>Cantidad NCF</th>
                            <th>Próximo NCF</th>
                            <th>Restantes NCF</th>
                            <th>NFC Vencimiento</th>
                        </tr>

                        </thead>

                        <tbody>

                        @foreach($nfc_vouchers as $nfc_voucher)

                            <tr>

                                <td>{{ $nfc_voucher->nfc_type }}</td>
                                <td>{{ $nfc_voucher->nfc_amount }}</td>
                                <td>{{ $nfc_voucher->nfc_next }}</td>
                                <td>{{  $nfc_voucher->nfc_amount - $nfc_voucher->nfc_next }}</td>
                                <td>{{ $nfc_voucher->nfc_expiration }}</td>

                                {{--<td>{{ $nfc_voucher->nfc_next }}</td>--}}

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabel"
             aria-hidden="true">

            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="agregarModalLabel">Agregar Comprobante Fiscal</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                            <span aria-hidden="true">&times;</span>

                        </button>

                    </div>

                    <form class="form-horizontal" action="{{ route('pos.configuration.admin.store') }}" method="POST">

                        @csrf

                        <div class="modal-body">

                            <!-- Contenido del modal -->


                            <div class="form-group row">

                                <div class="form-group col-md-3">

                                    <label for="nfc_type">Selección del Tipo</label>

                                    <select class="form-control @error('nfc_type') is-invalid @enderror" name="nfc_type"
                                            id="nfc_type">
                                        <option value="" selected>Seleccione NCF</option>
                                        <option value="B01">B01</option>
                                        <option value="B02">B02</option>
                                        <option value="B15">B15</option>
                                    </select>
                                    @error('nfc_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="nfc_following">Siguientes 8 Caracteres</label>
                                    <input type="number"
                                           class="form-control @error('nfc_following') is-invalid @enderror"
                                           name="nfc_following" id="nfc_following" placeholder="">
                                    @error('nfc_following')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nfc_expiration">Fecha de Vencimiento</label>
                                    <input type="date"
                                           class="form-control @error('nfc_expiration') is-invalid @enderror"
                                           name="nfc_expiration" id="nfc_expiration" placeholder="">
                                    @error('nfc_expiration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group col-md-5">
                                    <label for="nfc_amount">Cantidad de Comprobantes</label>
                                    <input type="number" class="form-control @error('nfc_amount') is-invalid @enderror"
                                           name="nfc_amount" id="nfc_amount" placeholder="">
                                    @error('nfc_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nfc_next">Próximo NFC a emitir</label>
                                    <input type="number" class="form-control @error('nfc_next') is-invalid @enderror"
                                           name="nfc_next" id="nfc_next" placeholder="">
                                    @error('nfc_next')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{--<div class="form-group col-md-3" style="top: 23px">
                                    <div class="custom-control @error('nfc_select') is-invalid @enderror custom-radio">
                                        <input type="radio" class="custom-control-input" id="customControlValidation2"
                                               name="nfc_select" value="active">
                                        <label class="custom-control-label" for="customControlValidation2">Activar
                                            NFC</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" class="custom-control-input" id="customControlValidation3"
                                               name="nfc_select" value="deactivated">
                                        <label class="custom-control-label" for="customControlValidation3">Desactivar
                                            NFC</label>
                                    </div>
                                    @error('nfc_select')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>--}}
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="guardar-btn" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {

            /*$('#nfc_vouchers_table').DataTable({
                responsive: true,
                "autoWidth": false,
                language: {
                    searchPlaceholder: "Buscar...",
                    search: "",
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",
                },
                order: [[0, 'desc']]
            });*/

            $("#nfc_type").on('change', function () {
                fetch("{{ route('pos.configuration.admin.update') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        nfc_type: $(this).val()
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.state) {
                            $("#nfc_type").val(data.message.nfc_type);
                            $("#nfc_following").val(data.message.nfc_following);
                            $("#nfc_expiration").val(data.message.nfc_expiration);
                            $("#nfc_next").val(data.message.nfc_next);
                            $("#nfc_amount").val(data.message.nfc_amount);

                        } else {
                            Swal({
                                type: 'error',
                                title: 'Opps!',
                                text: data.message,
                                confirmButtonText: 'Aceptar',
                                timer: 6000
                            });
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                    });
            });

        });

        function updateSettings(el, type) {

            if ($(el).is(':checked')) {

                var value = 1;

            } else {

                var value = 0;

            }

            $.post('{{ route('business_settings.update.activation') }}', {

                _token: '{{ csrf_token() }}',

                type: type,

                value: value

            }, function (data) {

                if (data == '1') {

                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');

                } else {

                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');

                }

            });

        }

    </script>

@endsection



