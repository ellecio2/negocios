<div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="Third group">
        <button type="submit" name="button" value="publish"
                class="btn btn-primary">{{ translate('Update Product') }}</button>
    </div>
</div>

<script>
    window.APP_URL = "{{ env('APP_URL') }}"; 
   
</script>

<script>

    $(document).ready(function () {
        let product = {{ $product->id }};
        console.log(product);
        if (!product) {
            console.error("El parámetro 'product' no está definido.");
            return; // Detén la ejecución si 'product' no es válido
        }
        $("#choice_form_edit").on("submit", async function (e) {
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
                    title: 'Opss',
                    text: 'los puntos no puede estar vacío o ser menor o igual a cero',
                    timer: 6000,
                }).then(() => {
                    setTimeout(() => {
                        $('input[name="earn_point"]').focus();
                    }, 100);
                    $(".action-btn").attr('disabled', false);
                    $("#loader").attr('style', 'display: none !important');
                });
                return;
            }

            formData.append('button', buttonValue);
            const imageFile1 = formData.get('photos');
            if (!isNumber(imageFile1)) {
                let imageId1 = await uploadImageFromUrl(imageFile1);
                formData.set('photos', imageId1);
                formData.set('thumbnail_img', imageId1);
            }
            let url = "{{ route('seller.products.update', '__product__') }}".replace('__product__', product);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "X-Requested-With": "XMLHttpRequest",
                },
                success: function (response) {
                    if (response.state) {
                        Swal({
                            type: "success",
                            title: "Edición Exitosa",
                            text: response.msg,
                            timer: 6000,
                        }).then(() => {
                            //console.log('aca')
                            //this.reset();
                            location.reload();
                        });
                    } else {
                        Swal({
                            type: "error",
                            title: "Oops...",
                            text: response.msg,
                            timer: 6000,
                            showConfirmButton: false
                        }).then(() => {
                            $("#button").attr('disabled', false);
                            $("#loader").attr('style', 'display: none !important');
                        })
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
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
                    throw new Error('Error al obtener la imagen');
                }

                const blob = await response.blob();

                const formData = new FormData();
                formData.append('aiz_file', blob, 'uploaded-image.jpg');
                formData.append('relativePath', null);
                formData.append('name', 'uploaded-image.jpg');
                formData.append('type', 'image/jpeg');
                

                const uploadResponse = await fetch(`${APP_URL}/aiz-uploader/upload`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": AIZ.data.csrf,
                    },
                });

                if (!uploadResponse.ok) {
                    throw new Error('Error al subir la imagen');
                }

                const result = await uploadResponse.json();
                return result.id;
            } catch (error) {
                console.error('Error:', error);
            }
        }
    });

</script>
