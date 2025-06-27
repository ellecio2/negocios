<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">

<style>



/* boton */
td a.button {
  display: inline-block;
  padding: 10px 10px; /* Espaciado de 20px arriba y abajo */
  background-color: #E63108; /* Color rojo claro similar al color de éxito de Bootstrap */
  color: white;
  text-decoration: none;
  border-radius: 5px; /* Bordes redondeados */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Sombra suave */
}

td a.button:hover {
  background-color: #d62e08; /* Color rojo más oscuro al pasar el cursor */
}



@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
</head>
<body>
	<table style="background-color: #003b73;" class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">


					@props(['url'])

					<!-- logo -->
					<tr style="text-align: center;">
						<td class="header">
							<a href="{{-- $url --}}" style="display: inline-block; padding: 50px;">
							<img src="{{ static_asset('assets/img/logo_blanco.png') }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-80px h-md-100px" height="100">
							</a>
						</td>
					</tr>
					
					

					<!-- Email Body -->
					<tr>
						<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
							<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
									<!-- Body content -->
									<tr>
										<td class="content-cell">
										

											{{-- Mensaje para taller avisandole al taller que el cliente le acpeto su propuesta--}}

                                            <table cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        Asunto: ¡El cliente {{ $nombreCliente }} ha aceptado tu propuesta de servicio!
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 18px; height: 20px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        ¡Buenas noticias! El cliente ha aceptado tu propuesta de servicio para el producto que ofreciste en La Pieza.DO. Nos complace informarte que has sido seleccionado para realizar la instalación o servicio.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 18px; height: 20px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        Te sugerimos que inicies sesión en tu cuenta de La Pieza.DO <a href="{{ route('workshop.login_taller', ['token' => $token]) }}" class="button button-primary" target="_blank" rel="noopener">Ir a mi perfil</a> y revises la sección de propuestas realizadas. Allí encontrarás más detalles sobre la propuesta aceptada por el cliente. Además, se ha habilitado una opción para que puedas agregar cualquier costo adicional que sea necesario para realizar el servicio de la mejor forma posible.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        Si tienes alguna pregunta o necesitas ayuda adicional, no dudes en contactarnos. Estamos aquí para ayudarte en todo lo que necesites.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 18px; height: 20px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        ¡Gracias por ser parte de La Pieza.DO y brindar tus servicios!
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        El equipo de La Pieza.DO
                                                    </td>
                                                </tr>
                                            </table>

											

										</td>
									</tr>
							</table>
						</td>
					</tr>
					

					<tr>
						<td>
							<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
								<tr>
									<td style="padding: 40px 0px; color: #ffffff; "  class="content-cell" align="center">

									© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
									</td>
								</tr>
							</table>
						</td>
					</tr>
							
				</table>
			</td>
		</tr>
	</table>
</body>
</html>







