@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-md-4">

                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Lista de talleres registrados</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                {{-- <div class="btn-group mr-2">
                                      <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                                      <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                                      <span data-feather="calendar"></span>
                                      This week
                                    </button> 
                                  --}}
                                <a class="btn btn-primary" href="{{ route('backend.dashboard_workshop.index') }}"
                                    role="button">Regresar</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="workshop_all" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre del taller</th>
                                        <th scope="col">Nombre usuario</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Fecha de registro</th>
                                        <th scope="col">Accione</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Usamos un bucle foreach para recorrer los usuarios talleres -->
                                    @foreach ($workshops as $workshop)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <!-- aqui el nombre-->
                                            <td>

                                              {{ $workshop->shop->name }}
                                            </td> 
                                            <td>
                                              {{ $workshop->name }}</td> <!-- Accedemos al atributo name del usuario -->
                                            <td>
                                            
                                              @if ( $workshop->email) <!-- Si el usuario tiene número de email -->
                                                   {{  $workshop->email }} <!-- Lo mostramos -->
                                              @else <!-- Si no tiene número de email -->
                                                   No tiene correo electrónico registrado <!-- Mostramos este texto -->
                                              @endif
                                            </td> <!-- Accedemos al atributo email del usuario -->
                                            <td>

                                              @if ($workshop->phone) <!-- Si el usuario tiene número de teléfono -->
                                                   {{ $workshop->phone }} <!-- Lo mostramos -->
                                              @else <!-- Si no tiene número de teléfono -->
                                                   No tiene teléfono registrado <!-- Mostramos este texto -->
                                              @endif
                                            
                                            </td> <!-- Accedemos al atributo phone del usuario -->
                                            <td>{{ $workshop->created_at }}</td>
                                            <!-- Accedemos al atributo created_at del usuario -->
                                            <td>
                                              <a class="btn btn-info" href="{{ route('backend.dashboard_workshop.workshop_all.manage.show', $workshop->id) }}" role="button">Gestionar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </main>
                </div>
            </div>
        </div>

    </div>
@endsection

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.css" />


@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#workshop_all').DataTable({
                responsive: true,
                "autoWidth": false,
                language: {
                    searchPlaceholder: "Buscar...",
                    search: "",
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",
                },
                'order': [],
                'columnDefs': [{
                        orderable: false,
                        targets: 0
                    }, // Disable ordering on column 0 (checkbox)
                ],


            });
        });
    </script>
@endsection
