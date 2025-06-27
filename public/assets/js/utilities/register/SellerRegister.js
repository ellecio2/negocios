$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip({
        container: "#tooltip-link",
    });

    const rncInput = document.getElementById("rnc_input");
    const cedulaInput = document.getElementById("cedula_input");
    const form = document.getElementById("registerForm");

    const checkbox = form.querySelector("#is-physical-person");
    const rncGroup = form.querySelector("#rnc_group");
    const name = form.querySelector("#name_user");
    const shopName = form.querySelector("#name");

    const rncFileInput = form.querySelector("#registro_mercantil");
    const cedulaFileInput = form.querySelector("#cedula_photo");

    const invalidFeedBackCedula = form.querySelector(
        "#invalid-feedback-cedula",
    );
    const invalidFeedBackRnc = form.querySelector("#invalid-feedback-rnc");

    const registroMercantil = document.getElementById('registro_mercantil').files[0];
    const cedulaPhoto = document.getElementById('cedula_photo').files[0];
    const fileStatusRNC = document.getElementById('file-status-rnc');
    const fileStatusCedula = document.getElementById('file-status');

    /*if (!registroMercantil || !cedulaPhoto) {
        e.preventDefault(); // Evita el envío del formulario
        if (!registroMercantil) validateFileInput(null, fileStatusRNC);
        if (!cedulaPhoto) validateFileInput(null, fileStatusCedula);
        alert("Por favor, sube todos los archivos requeridos.");
    }*/

    checkbox.addEventListener("change", function () {
        if (this.checked) {
            rncGroup.classList.add("d-none");
            if (shopName.value.length === 0) {
                shopName.value = name.value;
            }
            rncInput.required = false;
            rncFileInput.required = false;
        } else {
            rncGroup.classList.remove("d-none");
            rncInput.required = true;
            rncFileInput.required = true;
            shopName.value = "";
        }
    });

    $("#registerForm").on("submit", function (e) {
        e.preventDefault();

        $("#submitButton").prop("disabled", true);
        $("#preloader").show();
        const recaptchaResponse = grecaptcha.getResponse();

        if (recaptchaResponse.length === 0) {
            Swal({
                type: "error",
                /*title: "Oops...",*/
                title: "Por favor, verifica que no eres un robot.",
                timer: 6000,
            });
            $("#preloader").hide();
            $("#submitButton").prop("disabled", false);
            return;
        }
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "X-Requested-With": "XMLHttpRequest",
            },
            success: function (response) {
                console.log(response, "response");
                if (!response.state) {
                    $("#preloader").hide();
                    $("#submitButton").prop("disabled", false);
                    Swal({
                        type: "error",
                        /*title: "Oops...",*/
                        title: response.message,
                        timer: 6000,
                    }).then(() => {
                        grecaptcha.reset();
                        // Reiniciar el input de registro mercantil
                        const fileInput = document.getElementById("registro_mercantil");
                        const fileStatus = document.getElementById("file-status-rnc");
                        resetFileInput(fileInput, fileStatus);

                        // Reiniciar el input de cédula
                        const fileInputDoc = document.getElementById("cedula_photo");
                        const fileStatusDoc = document.getElementById("file-status");
                        resetFileInput(fileInputDoc, fileStatusDoc);
                    });
                } else {
                    window.location.href = response.redirect;
                }

                function resetFileInput(fileInput, fileStatus) {
                    const parent = fileInput.parentNode;
                    const newFileInput = fileInput.cloneNode(); // Clonar el input de archivo
                    parent.replaceChild(newFileInput, fileInput); // Reemplazar en el DOM
                    newFileInput.value = ""; // Asegurarse de que el nuevo input esté vacío

                    // Actualizar el estado visual
                    fileStatus.textContent = "(Campo requerido)";
                    fileStatus.style.color = "#dc3545";
                }
            },
        });
    });
});

/*form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (cedulaFileInput.files.length === 0) {
        invalidFeedBackCedula.classList.remove("d-none");
        console.log("entra al if cuando cedula file esta vacio");
        return;
    } else {
        invalidFeedBackCedula.classList.add("d-none");
    }

    if (!checkbox.checked) {
        if (rncFileInput.files.length === 0) {
            invalidFeedBackRnc.classList.remove("d-none");
            console.log("entra al if cuando rnfile esta vacio");
            return;
        } else {
            invalidFeedBackRnc.classList.add("d-none");
        }
    }

    this.submit();
});*/
