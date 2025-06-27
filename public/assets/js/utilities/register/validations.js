window.addEventListener("load", () => {
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    function validateFileInput(file, statusElement) {
        if (!file) {
            statusElement.textContent = "(Campo requerido)";
            statusElement.style.color = "#dc3545";
            return false;
        }

        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            statusElement.textContent = "Archivo no permitido. Solo se aceptan formatos: " + allowedExtensions.join(', ');
            statusElement.style.color = "#dc3545";
            return false;
        }

        statusElement.textContent = "\u2714 Archivo cargado correctamente";
        statusElement.style.color = "#28a745";
        return true;
    }

    document.getElementById('name').addEventListener('input', function (e) {
        const input = e.target;
        const value = input.value.trim();
        if (value.length >= 3) {
            input.classList.remove('is-invalid');
            input.classList.add('valid');
        } else {
            input.classList.remove('valid');
            input.classList.add('is-invalid');
        }
    });

    document.getElementById('categories_id').addEventListener('change', function (e) {
        const select = e.target;
        if (select.value) {
            select.classList.remove('is-invalid');
            select.classList.add('valid');
        } else {
            select.classList.remove('valid');
            select.classList.add('is-invalid');
        }
    });

    /*document.getElementById('cedula_photo').addEventListener('change', function (e) {
        const fileStatus = document.getElementById('file-status');
        const message = e.target.files.length > 0 ? "\u2714 Archivo cargado correctamente" : "(Campo requerido)";
        fileStatus.textContent = message;
        fileStatus.style.color = e.target.files.length > 0 ? "#28a745" : "#dc3545";
    });*/
    document.getElementById('cedula_photo').addEventListener('change', function (e) {
        const fileStatus = document.getElementById('file-status');
        validateFileInput(e.target.files[0], fileStatus);
    });

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const phoneunoInput = document.getElementById("phone");

    if (phoneunoInput.value.length <= 3) {
        phoneunoInput.value = "+1";
    }

    $("#name_user").keyup(function () {
        const input = $(this).val().trim();
        const errorSpan = $("#nameError");
        // Validar si el campo está vacío
        if (input.length === 0) {
            errorSpan.text("El campo nombre y apellido es obligatorio.");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        }
        // Validar si el campo contiene solo letras, espacios y la letra "ñ"
        else if (!/^[a-zA-ZñÑ\s]+$/.test(input)) {
            errorSpan.text("Solo se permiten letras y espacios");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        } else {
            errorSpan.text("");
            $(this).css("border", "1px solid rgba(0, 0, 0, 0.12)");
            $(this).css("box-shadow", "0 0 5px rgba(0, 0, 0, 0.12)");
            // Validar si el campo tiene más de 3 letras
            if (input.length < 3) {
                errorSpan.text("Debe tener al menos 3 letras");
                $(this).css("border", "1px solid #dc3545");
                $(this).css("box-shadow", "0 0 5px #dc3545");
            } else {
                errorSpan.text("");
                $(this).css("border", "1px solid #198754");
                $(this).css("box-shadow", "0 0 5px #198754");
            }
        }
    });
    $("#phone").on("input", function () {
        console.log("entrsso");

        const phoneField = document.getElementById("phone");
        const inputValue = phoneField.value.trim();
        const input = $(this).val().trim().replace(/\s/g, ""); // Eliminar espacios en blanco
        const errorSpan = $("#phoneError");
        const phoneInput = $(this); // Almacenar una referencia al elemento

        addPlusOneToPhoneInputs(phoneunoInput);

        // Validar si el campo está vacío
        if (input.length === 0) {
            errorSpan.text("El campo número móvil es obligatorio.");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        }
        // Validar si el campo contiene solo números y el símbolo "+"
        else if (!/^[\d+]+$/.test(input)) {
            errorSpan.text('Solo se permiten números y el símbolo "+".');
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        }
        // Validar si el campo tiene el formato correcto para el código de área de la República Dominicana y los siguientes 8 números
        else if (!/^\+1\d{10}$/.test(input)) {
            // Eliminar el espacio después de "+1"
            errorSpan.text(
                "El número móvil debe tener el formato +1 seguido de 10 números."
            );
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        } else {
            const user = $(this).data().user;
            errorSpan.text("");
            $(this).css("border", "1px solid #198754");
            $(this).css("box-shadow", "0 0 5px #198754");
            // Realiza la llamada Ajax para verificar el número de teléfono

            fetch(phoneCheckUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    phone: input,
                    user_type: $("#phone").data("user"),
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) {
                        $("#phoneStatus").html(
                            '<div style="font-size: 80%; color:#dc3545;" >El número de teléfono ya ha sido registrado.</div>'
                        );
                        phoneInput.css("border", "1px solid #dc3545"); // Usar la referencia almacenada
                        phoneInput.css("box-shadow", "0 0 5px #dc3545"); // Usar la referencia almacenada
                    } else {
                        $("#phoneStatus").html(
                            '<div class="text-success"></div>'
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }
    });
    $("#telefono_tienda").on("input", function () {
        const phoneField = document.getElementById("telefono_tienda");
        const input = $(this).val().trim().replace(/\s/g, ""); // Eliminar espacios en blanco
        const errorSpan = $("#mobileError");
        const telinput = $(this);

        // Función para mostrar error
        const showError = (message, element) => {
            errorSpan.text(message);
            element.css({
                "border": "1px solid #dc3545",
                "box-shadow": "0 0 5px #dc3545"
            });
        };

        // Validaciones iniciales
        if (input.length === 0) {
            showError("El campo Teléfono Tienda es obligatorio.", $(this));
            return;
        }
        if (!/^\d+$/.test(input)) {
            showError('Solo se permiten números.', $(this));
            return;
        }
        if (!/^\d{10}$/.test(input)) {
            showError("El número móvil debe tener el formato 8091234567.", $(this));
            return;
        }

        // Si pasa las validaciones, verificar duplicidad
        errorSpan.text("");
        $(this).css({
            "border": "1px solid #198754",
            "box-shadow": "0 0 5px #198754"
        });

        // Llamada Ajax para verificar el número de teléfono
        fetch(telCheckUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                telefono_tienda: input,
                user_type: $("#telefono_tienda").data("user"),
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log('Valor de data:', data); // Agregar este log
            console.log('Tipo de data.exists:', typeof data.exists); // Agregar este log

            if (data.exists === true) {
                console.log("entro true");
                $("#mobileStatus").html(
                    '<div style="font-size: 80%; color:#dc3545;">Este teléfono ya ha sido registrado.</div>'
                );
                telinput.css({
                    "border": "1px solid #dc3545",
                    "box-shadow": "0 0 5px #dc3545"
                });
            } else {
                console.log("entro false");
                $("#mobileStatus").html('<div class="text-success"></div>');
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    });
    $("#email").on("input", function () {
        const input = $(this).val().trim();
        const errorSpan = $("#emailError");
        const emailInput = $(this); // Almacenar una referencia al elemento
        // Validar si el campo está vacío
        $("#emailStatus").html('');
        if (input.length === 0) {
            errorSpan.text("El campo correo electrónico es obligatorio.");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        }
        // Validar si el campo contiene un correo electrónico válido
        else if (
            !/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.(com|do|net|com\.do)$/.test(
                input
            )
        ) {
            errorSpan.text("Ingrese un correo electrónico válido");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        } else {
            errorSpan.text("");
            $(this).css("border", "1px solid #198754");
            $(this).css("box-shadow", "0 0 5px #198754");
            // Realiza la llamada Ajax para verificar el correo electrónico
            fetch(emailCheckUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    email: input,
                    user_type: $("#email").data("user"),
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) {
                        $("#emailStatus").html(
                            '<div style="font-size: 80%; color:#dc3545;" >El correo electrónico ya ha sido registrado.</div>'
                        );
                        emailInput.css("border", "1px solid #dc3545");
                        emailInput.css("box-shadow", "0 0 5px #dc3545");
                    } else {
                        $("#emailStatus").html(
                            '<div class="text-success"></div>'
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }
    });

    $("#cedula_input").on("input", function () {
        const input = $(this).val().trim();
        const errorSpan = $("#cedulaError");
        const cedulaInput = $(this);
        const cedulaStatus = $("#cedulaStatus");

        // Limpiar el estado previo de errores
        cedulaStatus.html('');
        errorSpan.text('');
        cedulaInput.css("border", "").css("box-shadow", "");

        // Validar si el campo está vacío
        if (input.length === 0) {
            errorSpan.text("El campo Cédula Representante es obligatorio.");
            cedulaInput.css({
                "border": "1px solid #dc3545",
                "box-shadow": "0 0 5px #dc3545"
            });
            return; // Detener la ejecución si el campo está vacío
        }

        // Realiza la llamada Ajax para verificar si la cédula está registrada
        fetch(cedulaCheckUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                cedula: input,
                user_type: $("#email").data("user"),
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.exists) {
                    cedulaStatus.html('<div style="font-size: 80%; color:#dc3545;" >La cédula de representante ya ha sido registrada.</div>');
                    cedulaInput.css({
                        "border": "1px solid #dc3545",
                        "box-shadow": "0 0 5px #dc3545"
                    });
                } else {
                    cedulaStatus.html('<div class="text-success"></div>');
                    cedulaInput.css({
                        "border": "1px solid #198754",
                        "box-shadow": "0 0 5px #198754"
                    });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

    $("#password").on("input", function () {
        const password = $(this).val().trim();
        const errorSpan = $("#password1Error");
        // Validar si el campo está vacío
        if (password.length === 0) {
            errorSpan.text("El campo de contraseña es obligatorio.");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        }
        // Validar si la contraseña tiene al menos 6 caracteres
        else if (password.length < 8) {
            errorSpan.text("La contraseña debe tener al menos 8 caracteres.");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        } else {
            errorSpan.text("");
            $(this).css("border", "1px solid #198754");
            $(this).css("box-shadow", "0 0 5px #198754");
        }
    });
    $("#password_confirmation").on("input", function () {
        const password = $("#password").val().trim();
        const confirmPassword = $(this).val().trim();
        const errorSpan = $("#passwordError");
        // Validar si el campo está vacío
        if (confirmPassword.length === 0) {
            errorSpan.text(
                "El campo de confirmación de contraseña es obligatorio."
            );
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        }
        // Validar si la contraseña y la confirmación coinciden
        else if (password !== confirmPassword) {
            errorSpan.text("Las contraseñas no coinciden.");
            $(this).css("border", "1px solid #dc3545");
            $(this).css("box-shadow", "0 0 5px #dc3545");
        } else {
            errorSpan.text("");
            $(this).css("border", "1px solid rgba(0, 0, 0, 0.12)");
            $(this).css("box-shadow", "0 0 5px rgba(0, 0, 0, 0.12)");
            // Validar si la contraseña tiene al menos 6 caracteres
            if (password.length < 8) {
                errorSpan.text(
                    "La contraseña debe tener al menos 8 caracteres."
                );
                $(this).css("border", "1px solid #dc3545");
                $(this).css("box-shadow", "0 0 5px #dc3545");
            } else {
                errorSpan.text("");
                $(this).css("border", "1px solid #198754");
                $(this).css("box-shadow", "0 0 5px #198754");
            }
        }
    });

// Validación si no está seleccionada la opción de persona física
    if (!$("#is-physical-person").is(":checked")) {
        $("#rnc_input").on("input", function () {
            const input = $(this).val().trim();
            const errorSpan = $("#rncError");
            const rncInput = $(this);
            const rncStatus = $("#rncStatus");

            // Limpiar estado previo de errores
            errorSpan.text('');
            rncStatus.html('');
            rncInput.css("border", "").css("box-shadow", "");

            // Validar si el campo está vacío
            if (input.length === 0) {
                errorSpan.text("El campo RNC es obligatorio.");
                rncInput.css({
                    "border": "1px solid #dc3545",
                    "box-shadow": "0 0 5px #dc3545"
                });
                return; // Detener si el campo está vacío
            }

            // Realizar la llamada Ajax para verificar si el RNC está registrado
            fetch(rncCheckUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    rnc: input,
                    user_type: $("#email").data("user"),
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) {
                        rncStatus.html(
                            '<div style="font-size: 80%; color:#dc3545;" >Este RNC ya ha sido registrado.</div>'
                        );
                        rncInput.css({
                            "border": "1px solid #dc3545",
                            "box-shadow": "0 0 5px #dc3545"
                        });
                    } else {
                        rncStatus.html('<div class="text-success"></div>');
                        rncInput.css({
                            "border": "1px solid #198754",
                            "box-shadow": "0 0 5px #198754"
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });

        /*document.getElementById('registro_mercantil').addEventListener('change', function (e) {
            const fileStatus = document.getElementById('file-status-rnc');
            const message = e.target.files.length > 0 ? "\u2714 Archivo cargado correctamente" : "(Campo requerido)";
            fileStatus.textContent = message;
            fileStatus.style.color = e.target.files.length > 0 ? "#28a745" : "#dc3545";
        });*/
        document.getElementById('registro_mercantil').addEventListener('change', function (e) {
            const fileStatus = document.getElementById('file-status-rnc');
            validateFileInput(e.target.files[0], fileStatus);
        });
    }

    // Función para añadir o quitar clases de validación
    function toggleValidationClass(element, isValid, errorMessage = '') {
        const errorElement = document.getElementById(element.id + 'Error');
        if (isValid) {
            element.classList.remove('is-invalid');
            element.classList.add('valid');
            errorElement.textContent = '';
        } else {
            element.classList.remove('valid');
            element.classList.add('is-invalid');
            errorElement.textContent = errorMessage;
        }
    }

// Función para actualizar el estado del archivo
    function updateFileStatus(element, message, color) {
        element.textContent = message;
        element.style.color = color;
    }

    function addPlusOneToPhoneInputs(phoneInput) {
        const inputValue = phoneInput.value;
        if (!inputValue || !inputValue.startsWith("+1")) {
            phoneInput.value = "+1";
        }

        if (inputValue && inputValue.length <= 2) {
            phoneInput.value = "+1";
        }
    }
});


