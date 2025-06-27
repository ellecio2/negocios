{{-- @extends('frontend.layouts.user_panel')

@section('panel_content')


<div id="app_articles">
</div>

@endsection

@section('librerias')
    @vite('resources/js/app.js')
@endsection --}}

@extends('frontend.layouts.user_panel')

@section('panel_content')

    <style>
        .menu_article {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>

    <div class="aiz-titlebar mb-4">

        <div class="row align-items-center">

            <div class="col-md-6">

                <h1 class="fs-20 fw-700 text-dark">Mis Artículos</h1>

            </div>

        </div>

    </div>

    <div class="row gutters-16 mb-2">

        <!-- Offline Recharge Wallet -->

        @if (addon_is_activated('offline_payment'))

           {{--            <livewire:articles.articles />--}}

            <div class="col-sm-6 col-md-3 mx-auto mb-4 center">

                <div
                    class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-33 hov-bg-soft-light rounded-15p"
                    onclick="show_add_articles_modal()">

                    <span
                        class="size-60px rounded-circle mx-auto bg-dark d-flex align-items-center justify-content-center mb-3">

                        <i class="las la-plus la-3x text-white"></i>

                    </span>

                    <div class="fs-14 fw-600 text-dark">Artículos</div>
                </div>

            </div>

        @endif

    </div>
    <!-- Wallet Recharge History -->

    <div class="card rounded-0 shadow-none border rounded-15p">

        <div class="card-header border-bottom-0">

            <h5 class="mb-0 fs-20 fw-700 text-dark text-center text-md-left">Lista de Artículos Registrados</h5>

        </div>

        <div class="card-body py-0">
            <div class="table-responsive">
                <table class="table align-middle aiz-table mb-4">

                    <thead class="text-gray fs-12 fs-14 th-table">

                    <tr>
                        <th class="text-white">Categoría</th>
                        <th class="text-white">Marca</th>
                        <th class="text-white">Modelo</th>
                        <th class="text-white">Año</th>
                        <th class="text-white">Serial</th>
                        <th class="text-white">Imagen</th>
                        <th class="text-white">Accion</th>
                    </tr>

                    </thead>

                    <tbody class="fs-14">
                    @foreach ($articles as $key => $article)

                        <tr>
                            <td class="fw-700">{{ !empty($article->category_id) ? $article->category_id : 'No disponible' }}</td>
                            <td class="fw-700">{{ !empty($article->make) ? $article->make : 'No disponible' }}</td>

                            <td class="fw-700">{{ !empty($article->modelo) ? $article->modelo : 'No disponible' }}</td>
                            <td class="fw-700">{{ !empty($article->year) ? $article->year : 'No disponible' }}</td>
                            <td class="fw-700">{{ !empty($article->chasis_serial) ? $article->chasis_serial : 'No disponible' }}</td>
                            <td class="fw-700">
                                <img src="{{ $article->image }}" alt="Article Image" style="width: 150px; height: 100px; object-fit: cover;">
                            </td>
                            <td>
                                <button class="btn btn-danger btn-xs" onclick="delete_article({{ $article->id }})">
                                    Eliminar<i
                                        class="fa fa-trash"></i></button>
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $articles->links() }} <!-- Esto generará los enlaces de paginación -->
            </div>

            <!-- Pagination -->
        </div>

    </div>

@endsection

@section('modal')
    @include('frontend.user.articles.add_articles_modal')
@endsection
<script src="{{ static_asset('assets/js/jquery.min.js') }}"></script>

<script type="text/javascript">
    function delete_article(articleId) {
        Swal({
            title: "¿Estás seguro de que deseas eliminar este artículo?",
            text: "¡No podra recuperar este registro!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#003b73",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirmar",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: `/delete/articles/${articleId}`,
                    type: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal({
                                type: 'success',
                                title: 'Borrado Exitoso',
                                text: response.message,
                                timer: 4000
                            }).then(() => {
                                location.reload();
                            })
                        } else {
                            Swal({
                                type: 'error',
                                title: 'Opp...',
                                text: response.message,
                                timer: 4000
                            })
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr, ' ERRR')
                    }
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.show_add_articles_modal = function () {
            $('#add_articles_modal').modal('show');
        };
        let categorySelect = document.getElementById('categories');
        let product_id = document.getElementById('product_id');
        let model_id = document.getElementById('model_id');

        if (categorySelect) {
            categorySelect.addEventListener('change', function () {
                let categoryId = parseInt(this.value);

                product_id.innerHTML = '<option value="">Seleccione una marca</option>';
                if (categoryId) {
                    fetch(`get-marca/${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                data.forEach(brand => {
                                    let option = document.createElement('option');
                                    option.value = brand.id;
                                    option.textContent = brand.name;
                                    product_id.appendChild(option);
                                    product_id.classList.add('aiz-selectpicker');
                                    product_id.setAttribute('data-live-search', 'true');
                                    product_id.setAttribute('data-placeholder', 'Seleccione una marca');

                                    $(product_id).selectpicker('refresh');
                                });
                            } else {
                                let option = document.createElement('option');
                                option.textContent = "No se encontraron categorías";
                                product_id.appendChild(option);
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar las categorías:', error);
                        });
                }
            });
        }

        if (product_id) {
            product_id.addEventListener('change', function () {
                let marcaId = parseInt(this.value);

                model_id.innerHTML = '<option value="">Seleccione un producto</option>';

                if (product_id) {
                    fetch(`get-model/${marcaId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data, ' response data')
                             // Limpiar el select antes de agregar nuevos elementos
                            let yearSelect = document.getElementById('year');
                            yearSelect.innerHTML = '<option value="">Seleccione año</option>'; // Resetear opciones del select

                            // Verificar si la respuesta contiene años
                            if (data.years && data.years.length > 0) {
                                data.years.forEach(year => {
                                    let option = document.createElement('option');
                                    option.value = year.year; // El valor que se enviará al servidor
                                    option.textContent = year.year; // El texto que se verá en el select
                                    yearSelect.appendChild(option);
                                });
                            } else {
                                // Si no hay años, mostrar una opción por defecto
                                let option = document.createElement('option');
                                option.textContent = "No hay años disponibles";
                                yearSelect.appendChild(option);
                            }
                            if (data.length > 0) {
                                data.forEach(brand => {
                                    let option = document.createElement('option');
                                    option.value = brand.id;
                                    option.textContent = brand.model;
                                    model_id.appendChild(option);
                                    model_id.classList.add('aiz-selectpicker');
                                    model_id.setAttribute('data-live-search', 'true');
                                    model_id.setAttribute('data-placeholder', 'Seleccione un producto');
                                    $(model_id).selectpicker('refresh');
                                });

                            } else {
                                let option = document.createElement('option');
                                option.textContent = "No hay productos";
                                model_id.appendChild(option);
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar los productos:', error);
                        });
                }
            });
        }

        $('#articleForm').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {

                    if (response.status === true) {
                        $('#add_articles_modal').modal('hide');
                        Swal({
                            type: 'success',
                            title: 'Bien',
                            text: response.message,
                            timer: 4000
                        }).then(() => {
                            location.reload();
                        })

                    } else {
                        Swal({
                            type: 'error',
                            title: 'Opp...',
                            text: response.message,
                            timer: 4000
                        })
                    }
                },
                error: function (xhr, status, error) {

                    alert('Ocurrió un error. Intente de nuevo.');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>



