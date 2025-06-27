<div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="Third group">
        <button type="submit" id="button" name="button" value="unpublish" class="btn btn-primary action-btn">
            Guardar Borrador
        </button>
    </div>
    <div class="btn-group" role="group" aria-label="Second group">
        <button type="submit" id="button" name="button" value="publish" class="btn btn-success action-btn">
            {{ translate('Save & Publish') }}
        </button>
    </div>
</div>

<script>
    window.APP_URL = "{{ env('APP_URL') }}";
</script>

<script>
    $(document).ready(function() {
        $("#choice_form").on("submit", async function(e) {
            e.preventDefault();
            $(".action-btn").attr('disabled', 'disabled');
            $("#loader").attr('style', 'display: true !important');
            let buttonValue = $(document.activeElement).val();
            let formData = new FormData(this);
            const sku = formData.get('sku');
            if (sku === null || sku === '' || sku <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'El campo código no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="sku"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const brandId = formData.get('brand_id');
            if (brandId === null || brandId === '') {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'El campo Marca es obligatorio',
                    timer: 6000,
                }).then(() => {
                    $('#brand_id').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const name = formData.get('name');
            if (name === null || name === '' || name <= 0) {
                Swal({
                    type: "error",
                    text: 'Opss',
                    title: 'El nombre no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="name"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const category_id = formData.get('category_id');
            if (category_id === null || category_id === '' || category_id <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'La categoria no puede estar vacío',
                    timer: 6000,
                }).then(() => {
                    $('input[name="category_id"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const current_stock = formData.get('current_stock');
            if (current_stock === null || current_stock === '' || current_stock <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'El stock no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="current_stock"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const length = formData.get('length');
            if (length === null || length === '' || length <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'EL largo no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="length"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const width = formData.get('width');
            if (width === null || width === '' || width <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'El ancho no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="width"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const height = formData.get('height');
            if (height === null || height === '' || height <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'La altura no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="height"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const tagsInput = $(this).find('input[name="tags[]"]');
            const tagsValue = tagsInput.val().trim();
            if (tagsValue === '') {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'Debes ingresar al menos una etiqueta.',
                    timer: 6000,
                }).then(() => {
                    tagsInput.focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const unitPrice = formData.get('unit_price');
            if (unitPrice === null || unitPrice === '' || unitPrice <= 0) {
                Swal({
                    type: "error",
                    title: 'Opss',
                    text: 'El campo precio unitario no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="unit_price"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            const earn_point = formData.get('earn_point');
            if (earn_point === null || earn_point === '' || earn_point <= 0) {
                Swal({
                    type: "error",
                    text: 'Opss',
                    title: 'los puntos no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    $('input[name="earn_point"]').focus();
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }


            formData.append('button', buttonValue);
            const imageFile1 = formData.get('photos');
            if (!isNumber(imageFile1)) {
                try {
                    let imageId1 = await uploadImageFromUrl(imageFile1);
                    if (imageId1) {
                        formData.set('photos', imageId1);
                        formData.set('thumbnail_img', imageId1);
                    } else {
                        throw new Error('Failed to upload image');
                    }
                } catch (error) {
                    console.error('Image upload error:', error);
                    Swal.fire({
                        type: "error",
                        title: "Image Upload Error",
                        text: "Failed to upload the image. Please try again.",
                        timer: 6000
                    });
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                    return;
                }
            }

            $.ajax({
                type: "POST",
                url: "{{ route('seller.products.store') }}",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "X-Requested-With": "XMLHttpRequest"
                },
                success: function(response) {
                    if (response.state) {
                        Swal.fire({
                            type: "success",
                            title: "Creación Exitosa",
                            text: response.msg,
                            timer: 6000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Oops...",
                            text: response.msg,
                            timer: 6000,
                            showConfirmButton: false
                        }).then(() => {
                            $("#button").attr('disabled', false);
                            $("#loader").attr('style', 'display: none !important');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Form submission error:', error);
                    Swal.fire({
                        type: "error",
                        title: "Submission Error",
                        text: "Failed to submit the form. Please try again.",
                        timer: 6000
                    });
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                }
            });
        });

        function isNumber(value) {
            return !isNaN(parseFloat(value)) && isFinite(value);
        }
        async function uploadImageFromUrl(imageUrl) {
            try {
                const response = await fetch(imageUrl);

                if (!response.ok) {
                    throw new Error(`Failed to fetch image: ${response.status} ${response.statusText}`);
                }

                const blob = await response.blob();
                const formData = new FormData();
                formData.append('aiz_file', blob, 'uploaded-image.jpg');
                formData.append('type', blob.type);

                const uploadResponse = await fetch(`${window.APP_URL}/aiz-uploader/upload`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!uploadResponse.ok) {
                    throw new Error(`Upload failed: ${uploadResponse.status} ${uploadResponse.statusText}`);
                }

                let result;
                const contentType = uploadResponse.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await uploadResponse.json();
                } else {
                    const text = await uploadResponse.text();
                    try {
                        result = JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse response:', text);
                        throw new Error('Invalid JSON response from server');
                    }
                }

                if (!result || !result.id) {
                    throw new Error('Invalid response format: missing image ID');
                }

                return result.id;
            } catch (error) {
                console.error('Upload error:', error);
                throw error;
            }
        }




    });
</script>