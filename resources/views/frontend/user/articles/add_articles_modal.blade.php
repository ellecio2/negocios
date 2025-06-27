<div class="modal fade" id="add_articles_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Artículo</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>

            </div>

            <div>
                <form id="articleForm" action="{{ route('articles.store') }}" method="POST">
                    @csrf
                    <div class="card manual-payment-card rounded-0 shadow-none border p-4">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Seleccione el tipo de Artículo :</label>
                                    <select class="form-control" id="categories" name="category_ids" required
                                        style="width: 100%; border-radius: 15px;">
                                        <option></option> <!-- Select2 usará el placeholder definido en el JS -->
                                    </select>
                                    <input type="hidden" id="categoria_nombre" name="category_id" value="">
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Marca:</label>
                                    <select class="form-control rounded-15px" id="product_id" name="product_id"
                                        required>
                                        <option value="">Seleccione una marca</option>
                                    </select>
                                    <input type="hidden" id="category_make" name="make" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Modelo:</label>
                                    <select class="form-control rounded-15px" id="model_id" name="model" >
                                        <option value="">Seleccione modelo</option>
                                    </select>
                                    <input type="hidden" id="category_model" name="modelo" value="">
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Año:</label>
                                    <select class="form-control rounded-15px" id="year" name="year" >
                                        <option value="">Seleccione año</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Chásis | Serial:</label>
                                    <input type="text" lang="en" class="form-control rounded-15px"
                                        min="0" step="0.01" name="chasis_serial"
                                        placeholder="Chásis | Serial">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right mt-3 mr-4">
                        <button type="submit" class="btn btn-sm btn-primary rounded-25px w-150px transition-3d-hover">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
   $('#articleForm').on('submit', function (e) {
    // Evitar el envío del formulario para debug (puedes eliminar esta línea más adelante)
    e.preventDefault();

    // Obtener los textos seleccionados de cada select
    const selectedTextCategory = $('#categories option:selected').text();
    const selectedTextProduct = $('#product_id option:selected').text();
    const selectedTextModel = $('#model_id option:selected').text();

    // Asignar los valores a los inputs ocultos
    $('#categoria_nombre').val(selectedTextCategory);
    $('#category_make').val(selectedTextProduct);
    $('#category_model').val(selectedTextModel);

    // Si necesitas enviar el formulario tras la asignación
});

</script>
<script>
  $(document).ready(function() {
    // Inicializar selects
    const $categories = $('#categories');
    const $product = $('#product_id');
    const $model = $('#model_id');
    const $year = $('#year');
    const $chasis = $('input[name="chasis_serial"]');

    // Categorías que requieren mostrar año y chasis
    const vehicleCategories = ['6000', '6001', '6024', '6038', '6028'];

    // Deshabilitar y ocultar inicialmente
    function resetForm() {
        $product.prop('disabled', true).val('').selectpicker('refresh');
        $model.prop('disabled', true).val('').selectpicker('refresh');
        $year.closest('.form-group').hide(); // Ocultar el select de año
        $chasis.closest('.form-group').hide(); // Ocultar el input de chasis
    }

    resetForm();

    // Al cambiar de categoría
    $categories.on('changed.bs.select', function() {
        const categoryId = $(this).val();

        resetForm(); // Reiniciar el formulario

        if (categoryId) {
            // Habilitar el select de marca
            $product.prop('disabled', false).selectpicker('refresh');

            // Verificar si la categoría seleccionada está en las categorías de vehículos
            if (vehicleCategories.includes(categoryId)) {
                $year.closest('.form-group').show(); // Mostrar el select de año
                $chasis.closest('.form-group').show(); // Mostrar el input de chasis
            }
        }
    });

    // Al seleccionar una marca
    $product.on('changed.bs.select', function() {
        const brandId = $(this).val();
        $model.prop('disabled', !brandId).selectpicker(
            'refresh'); // Habilitar o deshabilitar modelos
    });

    // Opcional: Limpieza de selects cuando el modal se cierra
    $('#add_articles_modal').on('hidden.bs.modal', function() {
        $categories.val('').selectpicker('refresh');
        resetForm();
    });
});

</script>
<script>
    $(document).ready(function() {
        const $categories = $('#categories');

        $categories.selectpicker({
            liveSearch: true,
            size: 10,
            noneResultsText: 'No se encontraron resultados para {0}'
        });

        function loadCategories() {
            $.ajax({
                url: '{{ route("fetch.categories") }}',
                method: 'GET',
                success: function(response) {
                    console.log('Respuesta categorías:', response);

                    if (response.success && Array.isArray(response.categories)) {
                        $categories.empty();
                        $categories.append('<option value="">Seleccione el tipo</option>');

                        response.categories.forEach(function(category) {
                            const option =
                                `<option value="${category.categoryId}">${category.categoryName}</option>`;
                            $categories.append(option);
                        });

                        $categories.selectpicker('refresh');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando categorías:', {
                        xhr,
                        status,
                        error
                    });
                    $categories.empty()
                        .append('<option value="">Error al cargar categorías</option>')
                        .selectpicker('refresh');
                }
            });
        }

        loadCategories();
    });
</script>
{{-- para las marcas --}}
<script>
    $(document).ready(function() {
        let currentBrandRequest = null;
        let brandPage = 1;
        let lastBrandSearchTerm = '';
        let isBrandLoading = false;

        // Inicializar selectpicker para marcas
        $('#product_id').selectpicker({
            liveSearch: true,
            size: 10,
            noneResultsText: 'No se encontraron resultados para {0}'
        });

        // Manejar el cambio de categoría
        $('#categories').on('changed.bs.select', function() {
            const categoryId = $(this).val();
            if (categoryId) {
                brandPage = 1;
                loadBrands(categoryId);
            } else {
                // Limpiar el select de marcas si no hay categoría seleccionada
                $('#product_id').empty()
                    .append('<option value="">Seleccione una marca</option>')
                    .selectpicker('refresh');
            }
        });

        // Manejar la búsqueda en vivo para marcas
        $('#product_id').on('shown.bs.select', function() {
            const searchBox = $(this).closest('.bootstrap-select').find('.bs-searchbox input');

            searchBox.off('input').on('input', function() {
                const searchTerm = $(this).val();
                const categoryId = $('#categories').val();

                if (!categoryId) return;

                if (currentBrandRequest) {
                    currentBrandRequest.abort();
                }

                brandPage = 1;

                clearTimeout(window.brandSearchTimeout);
                window.brandSearchTimeout = setTimeout(function() {
                    loadBrands(categoryId, searchTerm, true);
                }, 250);
            });
        });

        function loadBrands(categoryId, searchTerm = '', isNewSearch = true) {
            if (isBrandLoading) return;

            isBrandLoading = true;
            lastBrandSearchTerm = searchTerm;

            if (isNewSearch) {
                $('#product_id').closest('.bootstrap-select').find('.dropdown-menu .inner').html(
                    '<div class="text-center p-3">Cargando marcas...</div>'
                );
            }

            currentBrandRequest = $.ajax({
                url: '{{ route('fetch.brands') }}',
                method: 'GET',
                data: {
                    category_id: categoryId,
                    search: searchTerm,
                    page: brandPage
                },
                success: function(response) {

                    const select = $('#product_id');

                    if (isNewSearch) {
                        select.empty();
                        select.append('<option value="">Seleccione una marca</option>');
                    }

                    if (response.success && response.brands.length > 0) {
                        // Agregar nuevas opciones
                        response.brands.forEach(function(brand) {
                            // Validar si brand.id o brand.name son undefined
                            const brandId = brand.id !== undefined ? brand.id : brand
                                .brandId;
                            const brandName = brand.name !== undefined ? brand.name : brand
                                .brandName;

                            select.append(
                                `<option value="${brandId}">${brandName}</option>`
                            );
                        });
                    } else {
                        // Si no se encontraron marcas, agregar una opción indicando esto
                        select.append('<option value="">No se encontró ninguna marca</option>');
                    }

                    select.selectpicker('refresh');

                    // Configurar scroll infinito
                    const dropdownInner = select.closest('.bootstrap-select').find(
                        '.dropdown-menu .inner');
                    dropdownInner.off('scroll');

                    if (response.total > (response.page * response.perPage)) {
                        dropdownInner.on('scroll', function() {
                            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0]
                                .scrollHeight - 50) {
                                if (!isBrandLoading) {
                                    brandPage++;
                                    loadBrands(categoryId, lastBrandSearchTerm, false);
                                }
                            }
                        });
                    }
                    isBrandLoading = false;
                },
                error: function() {
                    if (isNewSearch) {
                        $('#product_id').closest('.bootstrap-select').find('.dropdown-menu .inner')
                            .html(
                                '<div class="text-center p-3">Error al cargar las marcas</div>'
                            );
                    }
                    isBrandLoading = false;
                }
            });
        }
    });
</script>
{{-- funcion para mostrar los modelos --}}
<script>
   $(document).ready(function() {
    let currentModelRequest = null;
    let modelPage = 1;
    let lastModelSearchTerm = '';
    let isModelLoading = false;

    // Inicializar selectpicker para modelos
    $('#model_id').selectpicker({
        liveSearch: true,
        size: 10,
        noneResultsText: 'No se encontraron resultados para {0}'
    });

    // Agregar div para imagen
    $('#model_id').closest('.form-group').append(
        '<div class="mt-2">' +
            '<input type="hidden" name="image" id="image" class="form-control mb-2" placeholder="URL de la imagen" >' +
            '<img id="model-image" src="" alt="Imagen del modelo" class="img-fluid" style="max-height: 200px; display: none;">' +
        '</div>'
    );

    // Manejar el cambio de marca
    $('#product_id').on('changed.bs.select', function() {
        const brandName = $(this).find('option:selected').text();
        const categoryId = $('#categories').val();
        const categoryName = $('#categories').find('option:selected').text();

        if (brandName && (categoryId || categoryName)) {
            modelPage = 1;
            loadModels(brandName, categoryId, categoryName);
        } else {
            resetModelSelect();
        }
    });

    // Manejar el cambio de categoría
    $('#categories').on('changed.bs.select', function() {
        const brandName = $('#product_id').find('option:selected').text();
        const categoryId = $(this).val();
        console.log('categoryId', categoryId);

        const categoryName = $(this).find('option:selected').text();

        if (brandName && (categoryId || categoryName)) {
            modelPage = 1;
            loadModels(brandName, categoryId, categoryName);
        }
    });

    // Manejar selección de modelo
    $('#model_id').on('changed.bs.select', function() {
        const selected = $(this).find('option:selected');
        updateModelDetails(selected);
    });

    function loadModels(brandName, categoryId, categoryName, searchTerm = '', isNewSearch = true) {
        if (isModelLoading) return;
        isModelLoading = true;

        if (currentModelRequest) {
            currentModelRequest.abort();
        }

        if (isNewSearch) {
            showLoading();
        }

        currentModelRequest = $.ajax({
             url: '{{ route("fetch-models") }}',
            method: 'GET',
            data: {
                brand_name: brandName,
                category_id: categoryId,
                category: categoryName,
                search: searchTerm,
                page: modelPage
            },
            success: function(response) {
                handleModelResponse(response, isNewSearch);
            },
            error: function(xhr, status, error) {
                handleModelError(error, isNewSearch);
            },
            complete: function() {
                isModelLoading = false;
            }
        });
    }

    function handleModelResponse(response, isNewSearch) {
        const select = $('#model_id');

        if (isNewSearch) {
            select.empty().append('<option value="">Seleccione modelo</option>');
        }

        if (response.success && response.models && response.models.length > 0) {
            response.models.forEach(function(model) {
                if (select.find(`option[value="${model.modelId}"]`).length === 0) {
                    select.append(
                        `<option value="${model.modelId}"
                        data-image="${model.imageUrl || ''}"
                        data-description="${model.description || ''}"
                        data-price="${model.price?.value || ''}"
                        data-condition="${model.condition || ''}"
                        data-full-title="${model.fullTitle || ''}"
                    >${model.fullTitle}</option>`
                    );
                }
            });
        } else if (isNewSearch) {
            select.append('<option value="">No se encontraron modelos</option>');
        }

        select.selectpicker('refresh');
    }

    function updateModelDetails(selected) {
        const imageUrl = selected.data('image');
        const modelImage = $('#model-image');
        const imageInput = $('#image');

        if (imageUrl) {
            modelImage.attr('src', imageUrl).show();
            imageInput.val(imageUrl); // Actualiza el valor del input de texto
        } else {
            modelImage.hide();
            imageInput.val(''); // Limpia el valor del input si no hay imagen
        }
    }

    function showLoading() {
        $('#model_id')
            .closest('.bootstrap-select')
            .find('.dropdown-menu .inner')
            .html('<div class="text-center p-3">Cargando modelos...</div>');
    }

    function resetModelSelect() {
        $('#model_id')
            .empty()
            .append('<option value="">Seleccione modelo</option>')
            .selectpicker('refresh');
        $('#model-image').hide();
        $('#image').val(''); // Limpia el campo de texto
    }
});

</script>
