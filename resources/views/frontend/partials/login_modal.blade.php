<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">Inicia Sesión</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3">
                    <form id="FormLogin" class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                        @csrf
                        @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                            <div class="form-group email-form-group mb-1">
                                <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                <input type="email" class="form-control rounded-15p {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}" name="email" id="email"
                                       autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group phone-form-group mb-1 d-none">
                                <label for="phone" class="fs-12 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                                <input type="tel" id="phone-code" class="rounded-15p form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                       value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                            </div>
                            <input type="hidden" name="country_code" value="">
                        @else
                            <div class="form-group email-form-group mb-1">
                                <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                <input type="email" class="form-control rounded-15p {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}" name="email" id="email"
                                       autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif
                        <!-- Password -->
                        <div class="form-group">
                            <input type="password" name="password" class="form-control h-auto rounded-15px form-control-lg"
                                   placeholder="{{ translate('Password') }}">
                        </div>
                        <!-- Remember Me & Forgot password -->
                        <div class="row mb-2">
                            <div class="col-6">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <span class=opacity-60>{{ translate('Remember Me') }}</span>
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('password.request') }}"
                                   class="text-reset opacity-60 hov-opacity-100 fs-14">{{ translate('Forgot password?') }}</a>
                            </div>
                        </div>
                        <!-- Login Button -->
                        <div class="mb-5">
                            <button type="submit" id="submitForm" class="btn btn-primary btn-block fw-600 rounded-25px">{{ translate('Login') }}</button>
                        </div>
                    </form>
                    <!-- Register Now -->
                    <div class="text-center mb-3">
                        <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                        <a href="{{ route('register') }}">{{ translate('Register Now') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
    $("#FormLogin").on("submit", function (e) {
        e.preventDefault();

        let email = $("#email").val().trim();
        let phone = $("input[name='phone']").val().trim();
        let password = $("input[name='password']").val().trim();
        let valid = true;
        let errorMessage = [];

        if (email === "" && phone === "") {
            errorMessage.push("El correo electrónico o el teléfono son obligatorios.");
            valid = false;
        }

        if (password === "") {
            errorMessage.push("La contraseña es obligatoria.");
            valid = false;
        }

        if (!valid) {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: errorMessage.join("\n"),
            });
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
                if (response.state === true) {
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                let response = JSON.parse(xhr.responseText);
                let errorMessages = Object.values(response.errors).flat().join("\n");

                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: errorMessages,
                });

            },
        });
    });
});

</script>
<script>
    $(document).ready(function () {
        // Obtener el estado almacenado en la cookie
        var rememberMe = getCookie('rememberMe');
        // Establecer el estado del checkbox según el valor de la cookie
        if (rememberMe === 'true') {
            $('input[name="remember"]').prop('checked', true);
        }
        // Manejar el evento de cambio del checkbox
        $('input[name="remember"]').change(function () {
            // Obtener el estado actual del checkbox
            var isChecked = $(this).is(':checked');
            // Almacenar el estado en la cookie
            setCookie('rememberMe', isChecked, 365);
        });

        // Función para obtener el valor de una cookie
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length === 2) {
                return parts.pop().split(";").shift();
            }
        }

        // Función para establecer el valor de una cookie
        function setCookie(name, value, days) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + value + ";expires=" + expires.toUTCString() + ";path=/";
        }
    });
</script>
