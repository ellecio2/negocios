window.addEventListener('load', () => {

    const phoneunoInput = document.getElementById('phone');

    if(phoneunoInput.value.length <= 3){
        phoneunoInput.value = '+1';
    }

    $('#name_user').keyup(function () {
        const input = $(this).val().trim();
        const errorSpan = $('#nameError');
        // Validar si el campo está vacío
        if (input.length === 0) {
            errorSpan.text('El campo nombre y apellido es obligatorio.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        }
        // Validar si el campo contiene solo letras, espacios y la letra "ñ"
        else if (!/^[a-zA-ZñÑ\s]+$/.test(input)) {
            errorSpan.text('Solo se permiten letras y espacios');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        } else {
            errorSpan.text('');
            $(this).css('border', '1px solid rgba(0, 0, 0, 0.12)');
            $(this).css('box-shadow', '0 0 5px rgba(0, 0, 0, 0.12)');
            // Validar si el campo tiene más de 3 letras
            if (input.length < 3) {
                errorSpan.text('Debe tener al menos 3 letras');
                $(this).css('border', '1px solid #dc3545');
                $(this).css('box-shadow', '0 0 5px #dc3545');
            } else {
                errorSpan.text('');
                $(this).css('border', '1px solid #198754');
                $(this).css('box-shadow', '0 0 5px #198754');
            }
        }
    });
    $('#phone').on('input', function () {
        const phoneField = document.getElementById('phone');
        const inputValue = phoneField.value.trim();
        const input = $(this).val().trim().replace(/\s/g, ''); // Eliminar espacios en blanco
        const errorSpan = $('#phoneError');
        const phoneInput = $(this); // Almacenar una referencia al elemento


        addPlusOneToPhoneInputs(phoneunoInput);


        // Validar si el campo está vacío
        if (input.length === 0) {
            errorSpan.text('El campo número móvil es obligatorio.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        }
        // Validar si el campo contiene solo números y el símbolo "+"
        else if (!/^[\d+]+$/.test(input)) {
            errorSpan.text('Solo se permiten números y el símbolo "+".');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        }
        // Validar si el campo tiene el formato correcto para el código de área de la República Dominicana y los siguientes 8 números
        else if (!/^\+1\d{10}$/.test(input)) { // Eliminar el espacio después de "+1"
            errorSpan.text('El número móvil debe tener el formato +1 seguido de 10 números.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        } else {
            const user = $(this).data().user
            errorSpan.text('');
            $(this).css('border', '1px solid #198754');
            $(this).css('box-shadow', '0 0 5px #198754');
            // Realiza la llamada Ajax para verificar el número de teléfono
            fetch(phoneCheckUrl + user, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phone: input,
                    user_type: $('#phone').data('user')
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        $('#phoneStatus').html('<div style="font-size: 80%; color:#dc3545;" >El número de teléfono ya ha sido registrado.</div>');
                        phoneInput.css('border', '1px solid #dc3545'); // Usar la referencia almacenada
                        phoneInput.css('box-shadow', '0 0 5px #dc3545'); // Usar la referencia almacenada
                    } else {
                        $('#phoneStatus').html('<div class="text-success"></div>');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });
    $('#email').on('input', function () {
        const input = $(this).val().trim();
        const errorSpan = $('#emailError');
        const emailInput = $(this); // Almacenar una referencia al elemento
        // Validar si el campo está vacío
        if (input.length === 0) {
            errorSpan.text('El campo correo electrónico es obligatorio.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        }
        // Validar si el campo contiene un correo electrónico válido
        else if (!/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.(com|do|net|com\.do)$/.test(input)) {
            errorSpan.text('Ingrese un correo electrónico válido');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        } else {
            errorSpan.text('');
            $(this).css('border', '1px solid #198754');
            $(this).css('box-shadow', '0 0 5px #198754');
            // Realiza la llamada Ajax para verificar el correo electrónico
            fetch(emailCheckUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: input,
                    user_type: $('#email').data('user')
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        $('#emailStatus').html('<div style="font-size: 80%; color:#dc3545;" >El correo electrónico ya ha sido registrado.</div>');
                        emailInput.css('border', '1px solid #dc3545');
                        emailInput.css('box-shadow', '0 0 5px #dc3545');
                    } else {
                        $('#emailStatus').html('<div class="text-success"></div>');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });
    $('#password').on('input', function () {
        const password = $(this).val().trim();
        const errorSpan = $('#password1Error');
        // Validar si el campo está vacío
        if (password.length === 0) {
            errorSpan.text('El campo de contraseña es obligatorio.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        }
        // Validar si la contraseña tiene al menos 6 caracteres
        else if (password.length < 6) {
            errorSpan.text('La contraseña debe tener al menos 6 caracteres.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        } else {
            errorSpan.text('');
            $(this).css('border', '1px solid #198754');
            $(this).css('box-shadow', '0 0 5px #198754');
        }
    });
    $('#password_confirmation').on('input', function () {
        const password = $('#password').val().trim();
        const confirmPassword = $(this).val().trim();
        const errorSpan = $('#passwordError');
        // Validar si el campo está vacío
        if (confirmPassword.length === 0) {
            errorSpan.text('El campo de confirmación de contraseña es obligatorio.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        }
        // Validar si la contraseña y la confirmación coinciden
        else if (password !== confirmPassword) {
            errorSpan.text('Las contraseñas no coinciden.');
            $(this).css('border', '1px solid #dc3545');
            $(this).css('box-shadow', '0 0 5px #dc3545');
        } else {
            errorSpan.text('');
            $(this).css('border', '1px solid rgba(0, 0, 0, 0.12)');
            $(this).css('box-shadow', '0 0 5px rgba(0, 0, 0, 0.12)');
            // Validar si la contraseña tiene al menos 6 caracteres
            if (password.length < 6) {
                errorSpan.text('La contraseña debe tener al menos 6 caracteres.');
                $(this).css('border', '1px solid #dc3545');
                $(this).css('box-shadow', '0 0 5px #dc3545');
            } else {
                errorSpan.text('');
                $(this).css('border', '1px solid #198754');
                $(this).css('box-shadow', '0 0 5px #198754');
            }
        }
    });

    function addPlusOneToPhoneInputs(phoneInput) {
        const inputValue = phoneInput.value;
        if (!inputValue || !inputValue.startsWith('+1')) {
            phoneInput.value = '+1';
        }

        if(inputValue && inputValue.length <= 2) {
            phoneInput.value = '+1';
        }
    }
})
