@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="aiz-titlebar mb-4">

        <div class="row align-items-center">

            <div class="col-md-6">

                <h1 class="fs-20 fw-700 text-dark">Mis Articulos</h1>

            </div>

        </div>

    </div>

    <div class="row gutters-16 mb-2">

        <!-- Offline Recharge Wallet -->

        @if (addon_is_activated('offline_payment'))

            <div class="col-md-4 mx-auto mb-4">

                <div
                    class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light rounded-15p"
                    onclick="show_add_articles_modal()">

            <span
                class="size-60px rounded-circle mx-auto bg-dark d-flex align-items-center justify-content-center mb-3">

                <i class="las la-plus la-3x text-white"></i>

            </span>

                    <div class="fs-14 fw-600 text-dark">Agregar Artículos</div>
                </div>

            </div>

        @endif

    </div>
    <!-- Wallet Recharge History -->

    <div class="card rounded-0 shadow-none border rounded-15p">

        <div class="card-header border-bottom-0">

            <h5 class="mb-0 fs-20 fw-700 text-dark text-center text-md-left">Lista de Articulos Registrados</h5>

        </div>

        <div class="card-body py-0">

            <table class="table aiz-table mb-4">

                <thead class="text-gray fs-12">

                <tr>

                    <th class="pl-0">#</th>

                    <th>Tipos de Artículos</th>

                    <th>Marca</th>

                    <th>Modelo</th>

                    <th>Año</th>

                    <th>Chasis / SN</th>


                </tr>

                </thead>

                <tbody class="fs-14">

                @foreach ($categories as $key => $category)

                    <tr>

                        <td class="pl-0">{{ sprintf('%02d', $key + 1) }}</td>

                        <td class="fw-700">{{ ($category->name) }}</td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            <!-- Pagination -->
        </div>

    </div>

@endsection

@section('modal')

    @include('frontend.user.article.add_articles_modal')

    <div class="modal fade" id="add_articles_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Artículo</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>

                </div>

                <div>
                    <form class="" action="" method="post" enctype="multipart/form-data" id="add_article">
                        @csrf
                        <div class="card manual-payment-card rounded-0 shadow-none border p-4">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Seleccione el tipo de Artículo:</label>
                                            <select class="form-control aiz-selectpicker mb-3"
                                                    data-placeholder="Seleccione el tipo:" id="categories"
                                                    name="category_id" data-live-search="true" required>
                                                <option value="Seleccione el tipo:" selected>Seleccione el tipo:
                                                </option>
                                                @foreach ($categories as $key => $category)
                                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subcategory">Subcategoría:</label>
                                        <select name="subcategory_id" id="subcategory" class="form-control">
                                            <option value="">Seleccione una subcategoría</option>
                                            dd($data)
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Marca:</label>
                                            <input type="text" lang="en" class="form-control mb-3 rounded-15px" min="0"
                                                   step="0.01" name="marca" placeholder="Marca" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Model:</label>
                                            <input type="text" lang="en" class="form-control mb-3 rounded-15px" min="0"
                                                   step="0.01" name="modelo" placeholder="Marca" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Año:</label>
                                            <input type="text" lang="en" class="form-control mb-3 rounded-15px" min="0"
                                                   step="0.01" name="Año" placeholder="Marca" required>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="card manual-payment-card image-gallery-card mt-4">
                                        <div class="row gutters-5">
                                            <div class="col-md-12 col-xl-12">
                                                <div class="card">
                                                    <img class="card-img-top"
                                                         src="https://via.placeholder.com/600x400/E2E2E2" alt="image 1">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end row-->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label">Chásis | SN:</label>
                                        <input type="text" lang="en" class="form-control mb-3 rounded-15px" min="0"
                                               step="0.01" name="chasis" placeholder="Chásis | SN" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit"
                                        class="btn btn-sm btn-primary rounded-25px w-150px transition-3d-hover">
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Show Images -->

                </div>


            </div>

        </div>

    </div>

@endsection

@section('script')

    <script type="text/javascript">
        function show_articles_modal() {

            $('#articles_modal').modal('show');
            console.log('aca');

        }

        function show_add_articles_modal() {
            // Make an AJAX request to the server to fetch the content of the modal.
            $.get('{{ route("add_articles_modal")}}', {
                // Include the CSRF token in the request headers.
                _token: '{{ csrf_token() }}'
            }, function (data) {
                // When the AJAX request is completed, update the HTML of the modal body
                // with the response from the server.
                $('#add_articles_modal_body').html(data);

                // Show the modal on the page.
                $('#add_articles_modal').modal('show');
            });
        }


        $('#categories').on('change', function () {
            console.log('entre al on change');
            var categoryId = $(this).val(); // Obtiene el ID de la categoría seleccionada

            // Si se selecciona una categoría
            if (categoryId) {
                $.ajax({
                    url: "{{ route('get.getBrands') }}", // Ruta para obtener las subcategorías
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // Añadir el token CSRF
                        category_id: categoryId // Enviar el ID de la categoría seleccionada
                    },
                    success: function (categories) {
                        console.log(categories, ' response')
                        $('#subcategory').empty();
                        // Añadir la opción por defecto
                        $('#subcategory').append('<option value="">Seleccione una subcategoría</option>');

                        // Añadir las subcategorías obtenidas
                        $.each(data, function (key, value) {
                            $('#subcategory').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                // Si no se selecciona nada, vaciar el select de subcategorías
                $('#subcategory').empty();
                $('#subcategory').append('<option value="">Seleccione una subcategoría</option>');
            }
        });

        $('#add_article').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route("article.store") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);

                },
                error: function (xhr) {
                    console.log(xhr.responseText);

                }
            });
        });

    </script>

@endsection
