<input type="hidden" name="added_by" value="seller">
<div class="card rounded-15p">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center justify-content-between">
            {{-- OEM/SN Field --}}
            <div class="form-group col-12 col-md-6">
                <label class="d-flex align-items-center">
                    OEM/SN
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('SKU') }} / SN" name="sku" class="form-control"
                        required>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            {{-- OEM/SN Field --}}
      

            {{-- Brand Field --}}
            <div class="form-group col-12 col-md-6">
                <label class="d-flex align-items-center">
                    {{ translate('Brand') }}
                    <span class="text-danger">*</span>
                </label>
                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true"
                    required>
                    <option value="">{{ translate('Select Brand') }}</option>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Brand Field --}}
        </div>

        <div class="row align-items-center justify-content-between">
            {{-- Product Name Field --}}
            <div class="form-group col-12 col-md-6">
                <label class="d-flex align-items-center justify-content-start">
                    Nombre
                    <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    class="form-control"
                    name="name"
                    id="name"
                    placeholder="Nombre del producto" required>
            </div>
            {{-- Product Name Field --}}

            {{-- Category Field --}}
            <div class="form-group col-12 col-md-6">
                <label class="d-flex ">
                    {{ translate('Category') }}
                    <span class="text-danger">*</span>
                </label>
                <select
                    class="form-control aiz-selectpicker"
                    name="category_id"
                    id="category_id"
                    data-live-search="true">
                    <option value="" selected disabled>Seleccionar Categoría</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"> {{ $category->getTranslation('name') }} </option>
                    @foreach ($category->childrenCategories as $childCategory)
                    @include('categories.child_category', [
                    'child_category' => $childCategory,
                    ])
                    @endforeach
                    @endforeach
                </select>
            </div>
            {{-- Category Field --}}
        </div>

        <div class="row align-items-center justify-content-between">
            {{-- First Row --}}
            <div class="col-12 col-lg-4 form-group">
                <label class="d-flex align-items-center">
                    {{ translate('Existencias') }}
                    <span class="text-danger">*</span>
                </label>
                <input type="number"
                    lang="do"
                    min="1"
                    step="1"
                    placeholder="0"
                    name="current_stock"
                    class="form-control" required>
            </div>

            <div class="col-12 col-lg-4 form-group">
                <label class="d-flex align-items-center">{{ translate('Unit') }}</label>
                <select name="unit" class="form-control" required>
                    <option value="" selected>Seleccione Unidad:</option>
                    <option value="pieza">Pieza</option>
                    <option value="kit">Kit</option>
                    <option value="galon">Galón</option>
                    <option value="litro">Litro</option>
                    <option value="caja">Caja</option>
                    <option value="paquete">Paquete</option>
                </select>
            </div>

            <div class="col-12 col-lg-4 form-group">
                <label class="d-flex align-items-center">{{ translate('Weight') }}</label>
                <div class="input-group">
                    <input type="number"
                        class="form-control"
                        name="weight"
                        step="0.01"
                        placeholder="0.00">
                    <div class="input-group-append">
                        <div class="input-group-text">lb.</div>
                    </div>
                </div>
            </div>
            {{-- End of First Row --}}

            {{-- Second Row --}}


            <div class="col-12 form-group">
                <label class="d-flex align-items-center">Medidas del producto</label>
                <div class="row">
                    <div class="col-12 col-md-4 input-group mb-2">
                        <input type="text" placeholder="Largo" name="length" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">Pulg.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 input-group mb-2">
                        <input type="text" placeholder="Ancho" name="width" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">Pulg.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 input-group mb-2">
                        <input type="text" placeholder="Alto" name="height" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">Pulg.</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of Second Row --}}

            {{-- Third Row --}}
            <div class="col-12 col-lg-4 form-group">
                <label class="d-flex align-items-center">{{ translate('Barcode') }}</label>
                <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}">
            </div>

            @if (addon_is_activated('refund_request'))
            <div class="col-12 col-lg-4 form-group row align-items-center justify-content-center">
                <label class="col-8 col-lg-6">
                    Reembolsable
                    <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                        title="Indica si el producto puede ser reembolsable">
                        !
                    </button>
                </label>
                <div class="col-4 col-lg-3">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" name="refundable" checked value="1">
                        <span></span>
                    </label>
                </div>
            </div>
            @endif

            {{-- End of Third Row --}}
        </div>


        {{-- Description Field --}}
        <div class="form-group row">
            <label class="col-md-12 text-start bg-light font-weight-bold py-2">
                Por favor, a continuación agrega una breve descripción del producto
            </label>
            <div class="col-md-12">
                <textarea class="aiz-text-editor" name="description" id="description"></textarea>
            </div>
        </div>
        {{-- Description Field --}}

        {{-- PDF Technical specifications Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-form-label" for="signinSrEmail">
                Especificación Técnica (PDF)
            </label>
            <div class="col-md-9">
                <div class="input-group" data-toggle="aizuploader" data-type="document">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="pdf" class="selected-files">
                </div>
                <div class="file-preview box sm pdf-preview"></div>
            </div>
        </div>
        {{-- PDF Technical specifications Field --}}

        {{-- Product Images Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-form-label" for="signinSrEmail">
                Imagenes del Producto <small>(600x600)</small>
            </label>
            <div class="col-md-9">
                <div class="input-group" data-toggle="aizuploader" data-type="image"
                    data-multiple="true">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="photos" class="selected-files">
                </div>
                <div class="file-preview box xl image-preview"></div>
                <small class="text-muted">
                    {{ translate('These images are visible in product details page gallery. Use 600x600 sizes images.') }}
                </small>
            </div>
        </div>
        {{-- Product Images Field --}}

        {{-- Product thumbnail Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-form-label" for="signinSrEmail">
                Imagen miniatura del Producto <small>(300x300)</small>
            </label>
            <div class="col-md-9">
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="thumbnail_img" class="selected-files">
                </div>
                <div class="file-preview-thumbnail file-preview box xl"></div>
                <small class="text-muted">
                    {{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}
                </small>
            </div>
        </div>
        {{-- Product thumbnail Field --}}

        {{-- Product Tags Field --}}
        <div class="form-group row justify-content-start">
            <label class="col-md-3 col-from-label d-flex align-items-center">
                {{ translate('Tags') }}
                <span class="text-danger">*</span>
                <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                    title="{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}">
                    !
                </button>
            </label>
            <div class="col-md-9">
                <input
                    type="text"
                    class="form-control aiz-tag-input"
                    name="tags[]"
                    id="tags"
                    placeholder="{{ translate('Type and hit enter to add a tag') }}">
            </div>
        </div>
        {{-- Product Tags Field --}}

        {{-- Video Provider Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-from-label">
                {{ translate('Video Provider') }}
            </label>
            <div class="col-md-9">
                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                    <option value="youtube">{{ translate('Youtube') }}</option>
                    <option value="dailymotion">{{ translate('Dailymotion') }}</option>
                    <option value="vimeo">{{ translate('Vimeo') }}</option>
                </select>
            </div>
        </div>
        {{-- Video Provider Field --}}

        {{-- Video Link Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-form-label">
                {{ translate('Video Link') }}
                <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                    title="{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}">
                    !
                </button>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="video_link" placeholder="{{ translate('Video Link') }}">
            </div>
        </div>
        {{-- Video Link Field --}}

        {{-- Externam Link Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-from-label">
                {{ translate('External link') }}
            </label>
            <div class="col-md-9">
                <input type="text" placeholder="{{ translate('External link') }}" name="external_link"
                    class="form-control">
                <small class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
            </div>
        </div>
        {{-- Externam Link Field --}}

        {{-- External Button Text Field --}}
        <div class="form-group row">
            <label class="col-md-3 col-from-label">
                {{ translate('External link button text') }}
            </label>
            <div class="col-md-9">
                <input type="text" placeholder="{{ translate('External link button text') }}" name="external_link_btn"
                    class="form-control">
                <small class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
            </div>
        </div>
        {{-- External Button Text Field --}}

    </div>
    <script>
        $(function() {
            $(".file-preview-item").css('width', '250px !important');
        })

        document.getElementById('searchButton').addEventListener('click', async function() {
            const searchButton = $("#searchButton");
            $("#loader").attr('style', 'display: true !important');
            const sku = document.querySelector('input[name="sku"]').value;

            fetch(`/seller/products/oem/${sku}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    $("#searchButton").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');

                    if (data.success) {
                        const title = data.data[0].title; // Store the title in a variable

                        // Check if title is defined and is a string
                        if (typeof title === 'string') {
                            $("#name").val(title);
                            $("#description").val(title);

                            // Split the title into tags
                            const tagsArray = title.split(/\s+/).map(tag => tag.trim()).filter(tag => tag); // Split and clean up

                            // Initialize Tagify using the AIZ plugin function if not already initialized
                            if (!$("#tags").hasClass("tagify")) {
                                AIZ.plugins.tagify();
                            }

                            // Add tags to Tagify 
                            const tagifyInput = $("#tags").data("tagify");
                            if (tagifyInput) {
                                tagifyInput.addTags(tagsArray); // Add all tags at once
                            } else {
                                console.error("Tagify instance not found.");
                            }

                            $('#tags').val(tagsArray);
                            $('.aiz-text-editor').summernote('code', title);

                            const imageUrl = data.imagePath; // Use the image path from the response
                            const imageId = data.imageId;
                            const imageName = data.imageName;
                            const imageSize = data.imageSize;

                            updateImagePreview(imageUrl, imageId, imageName, imageSize);
                            updateImagePreviewThumbnail(imageUrl, imageId, imageName, imageSize);

                            // Update the hidden input and file amount text
                            $('input[name="photos"]').val(imageId);
                            $('input[name="thumbnail_img"]').val(imageId);
                            $('.file-amount').text('1 Archivo seleccionado');
                        } else {
                            console.error("Title is not a valid string:", title);
                            Swal.fire({
                                type: "error",
                                title: "Opp...",
                                text: "Title is not available.",
                            });
                        }
                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Opp...",
                            text: data.message,
                        });
                    }
                })
                .catch((error) => {
                    console.error('There was a problem with the fetch operation:', error);
                    Swal.fire({
                        type: "error",
                        title: "Error",
                        text: "There was a problem with the request.",
                    });
                });
        });

        function updateImagePreview(imageUrl, imageId, imageName, imageSize) {
            const filePreview = document.querySelector('.image-preview');
            const selectedFilesInput = document.querySelector('input[name="photos"]');
            filePreview.innerHTML = '';

            const imageContainer = document.createElement('div');
            imageContainer.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mt-2', 'file-preview-item');
            imageContainer.setAttribute('data-id', imageId);
            imageContainer.setAttribute('title', imageName);

            const thumbContainer = document.createElement('div');
            thumbContainer.classList.add('align-items-center', 'align-self-stretch', 'd-flex', 'justify-content-center', 'thumb');

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-fit');
            thumbContainer.appendChild(imgElement);

            const bodyContainer = document.createElement('div');
            bodyContainer.classList.add('col', 'body');

            const titleElement = document.createElement('h6');
            titleElement.classList.add('d-flex');

            const titleSpan = document.createElement('span');
            titleSpan.classList.add('text-truncate', 'title');
            titleSpan.textContent = imageName;

            const extSpan = document.createElement('span');
            extSpan.classList.add('flex-shrink-0', 'ext');
            extSpan.textContent = '.png';

            titleElement.appendChild(titleSpan);
            titleElement.appendChild(extSpan);

            const sizeElement = document.createElement('p');
            sizeElement.textContent = formatFileSize(imageSize);

            bodyContainer.appendChild(titleElement);
            bodyContainer.appendChild(sizeElement);

            const removeButtonContainer = document.createElement('div');
            removeButtonContainer.classList.add('remove');

            const removeButton = document.createElement('button');
            removeButton.classList.add('btn', 'btn-sm', 'btn-link', 'remove-attachment');
            removeButton.type = 'button';
            removeButton.innerHTML = '<i class="la la-close"></i>';

            removeButton.addEventListener('click', function() {
                filePreview.innerHTML = '';
                selectedFilesInput.value = '';
                $('.file-amount').text('Elija el archivo');
            });

            removeButtonContainer.appendChild(removeButton);

            imageContainer.appendChild(thumbContainer);
            imageContainer.appendChild(bodyContainer);
            imageContainer.appendChild(removeButtonContainer);

            filePreview.appendChild(imageContainer);
        }

        function updateImagePreviewThumbnail(imageUrl, imageId, imageName, imageSize) {
            const filePreview = document.querySelector('.file-preview-thumbnail');
            const selectedFilesInput = document.querySelector('input[name="thumbnail_img"]');
            filePreview.innerHTML = '';

            const imageContainer = document.createElement('div');
            imageContainer.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mt-2', 'file-preview-item');
            imageContainer.setAttribute('data-id', imageId);
            imageContainer.setAttribute('title', imageName);

            const thumbContainer = document.createElement('div');
            thumbContainer.classList.add('align-items-center', 'align-self-stretch', 'd-flex', 'justify-content-center', 'thumb');

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-fit');
            thumbContainer.appendChild(imgElement);

            const bodyContainer = document.createElement('div');
            bodyContainer.classList.add('col', 'body');

            const titleElement = document.createElement('h6');
            titleElement.classList.add('d-flex');

            const titleSpan = document.createElement('span');
            titleSpan.classList.add('text-truncate', 'title');
            titleSpan.textContent = imageName;

            const extSpan = document.createElement('span');
            extSpan.classList.add('flex-shrink-0', 'ext');
            extSpan.textContent = '.png';

            titleElement.appendChild(titleSpan);
            titleElement.appendChild(extSpan);

            const sizeElement = document.createElement('p');
            sizeElement.textContent = formatFileSize(imageSize);

            bodyContainer.appendChild(titleElement);
            bodyContainer.appendChild(sizeElement);

            const removeButtonContainer = document.createElement('div');
            removeButtonContainer.classList.add('remove');

            const removeButton = document.createElement('button');
            removeButton.classList.add('btn', 'btn-sm', 'btn-link', 'remove-attachment');
            removeButton.type = 'button';
            removeButton.innerHTML = '<i class="la la-close"></i>';

            removeButton.addEventListener('click', function() {
                filePreview.innerHTML = '';
                selectedFilesInput.value = '';
                $('.file-amount').text('Elija el archivo');
            });

            removeButtonContainer.appendChild(removeButton);

            imageContainer.appendChild(thumbContainer);
            imageContainer.appendChild(bodyContainer);
            imageContainer.appendChild(removeButtonContainer);

            filePreview.appendChild(imageContainer);
        }

        function formatFileSize(size) {
            const i = Math.floor(Math.log(size) / Math.log(1024));
            return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][i];
        }
       /* document.getElementById('searchButton').addEventListener('click', async function() {
                const searchButton = $("#searchButton");
                $("#loader").attr('style', 'display: true !important');
                const sku = document.querySelector('input[name="sku"]').value;

            fetch(`/seller/products/oem/${sku}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    $("#searchButton").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');

                    if (data.success) {
                        const title = data.data[0].title; // Store the title in a variable

                        // Check if title is defined and is a string
                        if (typeof title === 'string') {
                            $("#name").val(title);
                            $("#description").val(title);

                            // Split the title into tags
                            const tagsArray = title.split(/\s+/).map(tag => tag.trim()).filter(tag => tag); // Split and clean up

                            // Initialize Tagify using the AIZ plugin function if not already initialized
                            if (!$("#tags").hasClass("tagify")) {
                                AIZ.plugins.tagify();
                            }

                            // Add tags to Tagify 
                            const tagifyInput = $("#tags").data("tagify");
                            if (tagifyInput) {
                                tagifyInput.addTags(tagsArray); // Add all tags at once
                            } else {
                                console.error("Tagify instance not found.");
                            }

                          $('#tags').val(tagsArray);
                        
                            $('.aiz-text-editor').summernote('code', title);

                            const imageUrl = data.data[0].thumbnailImages[0].imageUrl;
                            const imageUrlThumbnails = data.data[0].thumbnailImages.map(thumbnail => thumbnail.imageUrl);
                            updateImagePreview(imageUrlThumbnails);
                            updateImagePreviewThumbnail(imageUrlThumbnails);
                        } else {
                            console.error("Title is not a valid string:", title);
                            Swal.fire({
                                type: "error",
                                title: "Opp...",
                                text: "Title is not available.",
                            });
                        }
                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Opp...",
                            text: data.message,
                        });
                    }
                })

                .catch((error) => {
                    console.error('There was a problem with the fetch operation:', error);
                    Swal.fire({
                        type: "error",
                        title: "Error",
                        text: "There was a problem with the request.",
                    });
                });
        });

       
        function updateImagePreview(imageUrl) {
            const filePreview = document.querySelector('.image-preview');
            const selectedFilesInput = document.querySelector('input[name="photos"]');
            filePreview.innerHTML = '';

            // Crear un contenedor para la imagen y el botón
            const imageContainer = document.createElement('div');
            imageContainer.classList.add('position-relative');

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-thumbnail', 'm-1');
            imgElement.style.width = '250px';
            imageContainer.appendChild(imgElement);

            const removeButton = document.createElement('button');
            removeButton.classList.add('btn', 'btn-sm', 'btn-link', 'remove-attachment', 'position-absolute');
            removeButton.type = 'button';
            removeButton.innerHTML = '<i class="la la-close"></i>';

            removeButton.addEventListener('click', function() {
                filePreview.innerHTML = '';
                selectedFilesInput.value = '';
            });

            imageContainer.appendChild(removeButton);
            filePreview.appendChild(imageContainer);
        }

        function updateImagePreviewThumbnail(imageUrl) {
            const filePreview = document.querySelector('.file-preview-thumbnail');
            const selectedFilesInput = document.querySelector('input[name="thumbnail_img"]');
            filePreview.innerHTML = '';

            const imageContainer = document.createElement('div');
            imageContainer.classList.add('position-relative');

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-thumbnail', 'm-1');
            imgElement.style.width = '250px';
            imageContainer.appendChild(imgElement);

            const removeButton = document.createElement('button');
            removeButton.classList.add('btn', 'btn-sm', 'btn-link', 'remove-attachment', 'position-absolute');
            removeButton.type = 'button';
            removeButton.innerHTML = '<i class="la la-close"></i>';

            removeButton.addEventListener('click', function() {
                filePreview.innerHTML = '';
                selectedFilesInput.value = '';
            });

            imageContainer.appendChild(removeButton);
            filePreview.appendChild(imageContainer);
        }*/




 /*function updateImagePreview(imageUrl) {
            const filePreview = document.querySelector('.image-preview');
            const selectedFilesInput = document.querySelector('input[name="photos"]');
            filePreview.innerHTML = '';

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-thumbnail', 'm-1');
            imgElement.style.width = '250px';
            filePreview.appendChild(imgElement);
            selectedFilesInput.value = imageUrl;
        }*/



        // /*function updateImagePreviewThumbnail(imageUrl) {
        //     const filePreview = document.querySelector('.file-preview-thumbnail');
        //     const selectedFilesInput = document.querySelector('input[name="thumbnail_img"]');
        //     filePreview.innerHTML = '';

        //     const imgElement = document.createElement('img');
        //     imgElement.src = imageUrl;
        //     imgElement.classList.add('img-thumbnail', 'm-1');
        //     imgElement.style.width = '250px';
        //     filePreview.appendChild(imgElement);
        //     selectedFilesInput.value = imageUrl;
        // }*/
        // $(document).ready(function() {
        //     // Configuración inicial
        //     $(".file-preview-item").css('width', '250px !important');

        //     // Manejador principal de búsqueda
        //     document.getElementById('searchButton').addEventListener('click', async function() {
        //         const searchButton = $("#searchButton");
        //         const loader = $("#loader");
        //         const sku = document.querySelector('input[name="sku"]').value;

        //         try {
        //             // Deshabilitar botón y mostrar loader
        //             searchButton.attr('disabled', true);
        //             loader.attr('style', 'display: true !important');

        //             const response = await fetch(`/seller/products/oem/${sku}`);
        //             if (!response.ok) {
        //                 throw new Error(`Error HTTP: ${response.status}`);
        //             }

        //             const data = await response.json();

        //             if (data.success && data.data && data.data.length > 0) {
        //                 await handleProductData(data.data[0]);
        //             } else {
        //                 throw new Error(data.message || 'No se encontraron datos del producto');
        //             }
        //         } catch (error) {
        //             console.error('Error en la búsqueda:', error);
        //             Swal.fire({
        //                 type: "error",
        //                 title: "Error",
        //                 text: error.message || "Hubo un problema con la búsqueda.",
        //             });
        //         } finally {
        //             // Rehabilitar botón y ocultar loader
        //             searchButton.attr('disabled', false);
        //             loader.attr('style', 'display: none !important');
        //         }
        //     });

        //     // Función para manejar los datos del producto
        //     async function handleProductData(productData) {
        //         try {
        //             // Actualizar título y descripción
        //             updateTextFields(productData);

        //             // Actualizar tags
        //             if (productData.title) {
        //                 updateTags(productData.title);
        //             }

        //             // Procesar imágenes si están disponibles
        //             if (productData.image && productData.thumbnailImages) {
        //                 await handleImages(productData);
        //             }
        //         } catch (error) {
        //             console.error('Error al procesar datos del producto:', error);
        //             throw error;
        //         }
        //     }

        //     // Función para actualizar campos de texto
        //     function updateTextFields(productData) {
        //         const title = productData.title;
        //         if (typeof title === 'string') {
        //             $("#name").val(title);
        //             $("#description").val(title);
        //             $('.aiz-text-editor').summernote('code', title);
        //         }
        //     }

        //     // Función para actualizar tags
        //     function updateTags(title) {
        //         try {
        //             const tagsArray = title
        //                 .split(/\s+/)
        //                 .map(tag => tag.trim())
        //                 .filter(tag => tag && tag.length >= 2); // Filtrar tags vacíos o muy cortos

        //             if (!$("#tags").hasClass("tagify")) {
        //                 AIZ.plugins.tagify();
        //             }

        //             const tagifyInput = $("#tags").data("tagify");
        //             if (tagifyInput) {
        //                 tagifyInput.removeAllTags();
        //                 tagifyInput.addTags(tagsArray);
        //             }
        //         } catch (error) {
        //             console.error('Error al actualizar tags:', error);
        //             throw new Error('No se pudieron actualizar las etiquetas');
        //         }
        //     }

        //     // Función para manejar imágenes
        //     async function handleImages(productData) {
        //         try {
        //             if (productData.image && productData.image.imageUrl) {
        //                 updateImagePreview([productData.image.imageUrl]);
        //             }

        //             if (productData.thumbnailImages && Array.isArray(productData.thumbnailImages)) {
        //                 const thumbnailUrls = productData.thumbnailImages
        //                     .filter(thumb => thumb && thumb.imageUrl)
        //                     .map(thumb => thumb.imageUrl);

        //                 if (thumbnailUrls.length > 0) {
        //                     updateImagePreviewThumbnail(thumbnailUrls[0]);
        //                 }
        //             }
        //         } catch (error) {
        //             console.error('Error al procesar imágenes:', error);
        //             throw new Error('No se pudieron procesar las imágenes');
        //         }
        //     }

        //     // Función para actualizar vista previa de imágenes
        //     function updateImagePreview(imageUrls) {
        //         const filePreview = document.querySelector('.image-preview');
        //         const selectedFilesInput = document.querySelector('input[name="photos"]');
        //         filePreview.innerHTML = '';

        //         imageUrls.forEach(url => {
        //             const imageContainer = createImageContainer(url, () => {
        //                 filePreview.innerHTML = '';
        //                 selectedFilesInput.value = '';
        //             });
        //             filePreview.appendChild(imageContainer);
        //         });

        //         selectedFilesInput.value = imageUrls.join(',');
        //     }

        //     // Función para actualizar vista previa de miniatura
        //     function updateImagePreviewThumbnail(imageUrl) {
        //         const filePreview = document.querySelector('.file-preview-thumbnail');
        //         const selectedFilesInput = document.querySelector('input[name="thumbnail_img"]');
        //         filePreview.innerHTML = '';

        //         const imageContainer = createImageContainer(imageUrl, () => {
        //             filePreview.innerHTML = '';
        //             selectedFilesInput.value = '';
        //         });
        //         filePreview.appendChild(imageContainer);
        //         selectedFilesInput.value = imageUrl;
        //     }

        //     // Función auxiliar para crear contenedor de imagen
        //     function createImageContainer(imageUrl, onRemove) {
        //         const container = document.createElement('div');
        //         container.classList.add('position-relative', 'd-inline-block', 'm-2');

        //         const img = document.createElement('img');
        //         img.src = imageUrl;
        //         img.classList.add('img-thumbnail');
        //         img.style.width = '250px';
        //         img.style.height = 'auto';

        //         const removeBtn = document.createElement('button');
        //         removeBtn.classList.add('btn', 'btn-sm', 'btn-link', 'remove-attachment', 'position-absolute');
        //         removeBtn.style.top = '5px';
        //         removeBtn.style.right = '5px';
        //         removeBtn.innerHTML = '<i class="la la-close"></i>';
        //         removeBtn.addEventListener('click', onRemove);

        //         container.appendChild(img);
        //         container.appendChild(removeBtn);

        //         return container;
        //     }
        // });
    </script>
</div>