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
										

											{{-- MENSAJE Propuesta de instalacion de SERVICIO taller a cliente --}}



											<table  cellpadding="0" cellspacing="0" role="presentation">
												<tr>
													<td style="color: #ffffff; font-size: 18px;">
                                                        Asunto: ¡Nueva solicitud de servicio disponible!
                                                    </td>
												</tr>
												<tr>
													<td style="font-size: 18px; height: 20px;">&nbsp;</td>
												</tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        Estimado taller,
                                                    </td>
                                                </tr>
                                                <tr>
													<td style="font-size: 18px; height: 20px;">&nbsp;</td>
												</tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        Esperamos que te encuentres bien. Nos complace informarte que un cliente ha solicitado un servicio que podría ser de tu interés. Te invitamos a revisar la sección de solicitudes de servicio en tu cuenta del panel  <a href="{{ route('workshop.login', ['token' => $token]) }}" class="button button-primary" target="_blank" rel="noopener">Ir a mi perfil</a>. Allí encontrarás todos los detalles y características del servicio solicitado.
                                                    </td>
                                                </tr>
                                                <tr>
													<td style="font-size: 18px; height: 20px;">&nbsp;</td>
												</tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        ¡Esta es una gran oportunidad para mostrar tus habilidades y ofrecer tus servicios profesionales! No pierdas la oportunidad de conectarte con nuevos clientes y expandir tu negocio.
                                                    </td>
                                                </tr>
                                                <tr>
													<td style="font-size: 18px; height: 20px;">&nbsp;</td>
												</tr>
                                                <tr>
                                                    <td style="color: #ffffff; font-size: 18px;">
                                                        Saludos cordiales,
                                                    </td>
                                                </tr>
												<tr>
													<td style="color: #ffffff; font-size: 18px;">
														<span>La Pieza.<span
															style="color: #E63108;">DO</span></span>
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







