<input type="hidden" name="added_by" value="seller">
<div class="card rounded-15p">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Update your product') }}</h5>
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
                    <input type="text" id="skuInput" placeholder="{{ translate('SKU') }} / SN" name="sku" class="form-control"
                           required value="{{ $product->stocks->first()->sku }}" id="skuInput">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="searchButton" disabled>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            {{-- OEM/SN Field --}}
            <script>
                $(document).ready(function() {
                    $('#skuInput').on('input', function() {
                        const skuValue = $(this).val();
                        $('#searchButton').prop('disabled', skuValue.trim() === '');
                    });
                });
            </script>

            {{-- Brand Field --}}
            <div class="form-group col-12 col-md-6">
                <label class="d-flex align-items-center">
                    {{ translate('Brand') }}
                    <span class="text-danger">*</span>
                </label>
                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true"
                        required data-selected="{{ $product->brand_id }}">
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
                    placeholder="Nombre del producto" required value="{{$product->getTranslation('name',$lang)}}">
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
                    data-live-search="true" data-selected="{{ $product->category_id }}">
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
                       class="form-control" required value="{{$product->current_stock}}">
            </div>

            <div class="col-12 col-lg-4 form-group">
                <label class="d-flex align-items-center">{{ translate('Unit') }}</label>
                <select name="unit" class="form-control" required>
                    <option value="pieza" @selected($product->unit == 'pieza')>Pieza</option>
                    <option value="kit" @selected($product->unit == 'kit')>Kit</option>
                    <option value="galon" @selected($product->unit == 'galon')>Galón</option>
                    <option value="litro" @selected($product->unit == 'litro')>Litro</option>
                    <option value="caja" @selected($product->unit == 'caja') >Caja</option>
                    <option value="paquete" @selected($product->unit == 'paquete')>Paquete</option>
                </select>
            </div>

            <div class="col-12 col-lg-4 form-group">
                <label class="d-flex align-items-center">{{ translate('Weight') }}</label>
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           name="weight"
                           step="0.01"
                           placeholder="0.00" value="{{$product->weight}}">
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
                        <input type="text" placeholder="Largo" name="length" class="form-control"
                               value="{{ $product->length() }}">
                        <div class="input-group-append">
                            <div class="input-group-text">Pulg.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 input-group mb-2">
                        <input type="text" placeholder="Ancho" name="width" class="form-control"
                               value="{{ $product->width() }}">
                        <div class="input-group-append">
                            <div class="input-group-text">Pulg.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 input-group mb-2">
                        <input type="text" placeholder="Alto" name="height" class="form-control"
                               value="{{ $product->height() }}">
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
                <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}"
                       value="{{$product->barcode}}">
            </div>

            @if (addon_is_activated('refund_request'))
                <div class="col-12 col-lg-5 form-group row align-items-center justify-content-center">
                    <label class="col-8 col-lg-6">
                        Reembolsable
                        <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                                title="Indica si el producto puede ser reembolsable">
                            !
                        </button>
                    </label>
                    <div class="col-3 col-lg-2">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" name="refundable" @if ($product->refundable == 1) checked
                                   @endif value="1">
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
                <textarea class="aiz-text-editor" name="description"
                          id="description">{{$product->getTranslation('description',$lang)}}</textarea>
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
                    <input type="hidden" name="pdf" class="selected-files" value="{{ $product->pdf }}">
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
                    <input type="hidden" name="photos" class="selected-files" value="{{ $product->photos }}">
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
                    <input type="hidden" name="thumbnail_img" class="selected-files"
                           value="{{ $product->thumbnail_img }}">
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

                {{--<input
                    type="text"
                    class="form-control aiz-tag-input"
                    name="tags[]"
                    placeholder="{{ translate('Type and hit enter to add a tag') }}" value="{{ $tagsAsString }}">--}}
                <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags"
                       value="{{ $product->tags }}" placeholder="{{ translate('Type to add a tag') }}"
                       data-role="tagsinput">
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
                    <option
                        value="youtube" <?php if ($product->video_provider == 'youtube') echo "selected"; ?>>
                        {{ translate('Youtube') }}</option>
                    <option
                        value="dailymotion"<?php if ($product->video_provider == 'dailymotion') echo "selected"; ?>>
                        {{ translate('Dailymotion') }}</option>
                    <option
                        value="vimeo" <?php if ($product->video_provider == 'vimeo') echo "selected"; ?>>
                        {{ translate('Vimeo') }}</option>
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
                {{--<input type="text" class="form-control" name="video_link" placeholder="{{ translate('Video Link') }}"
                       value="{{ $product->video_link }}">--}}
                <input type="text" class="form-control" name="video_link"
                       value="{{ $product->video_link }}"
                       placeholder="{{ translate('Video Link') }}">
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
                       value="{{ $product->external_link }}"
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
                       value="{{ $product->external_link_btn }}"
                       class="form-control">
                <small class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
            </div>
        </div>
        {{-- External Button Text Field --}}
    </div>
    <script>
        $(function () {
            $(".file-preview-item").css('width', '250px !important');
        })

        document.getElementById('searchButton').addEventListener('click', function() {
            $("#searchButton").attr('disabled', 'disabled');
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
                            const imageUrl = data.data[0].image.imageUrl;
                            const imageUrlThumbnails = data.data[0].thumbnailImages.map(thumbnail => thumbnail.imageUrl);
                            updateImagePreview(imageUrlThumbnails);
                            updateImagePreviewThumbnail(imageUrl);
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

            removeButton.addEventListener('click', function () {
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

            removeButton.addEventListener('click', function () {
                filePreview.innerHTML = '';
                selectedFilesInput.value = '';
            });

            imageContainer.appendChild(removeButton);
            filePreview.appendChild(imageContainer);
        }

        /*function updateImagePreviewThumbnail(imageUrl) {
            const filePreview = document.querySelector('.file-preview-thumbnail');
            const selectedFilesInput = document.querySelector('input[name="thumbnail_img"]');
            filePreview.innerHTML = '';

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-thumbnail', 'm-1');
            imgElement.style.width = '250px';
            filePreview.appendChild(imgElement);
            selectedFilesInput.value = imageUrl;
        }*/
    </script>
</div>
