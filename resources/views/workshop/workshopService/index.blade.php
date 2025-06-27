@extends('workshop.layouts.app')

@section('panel_content')

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">
                <div class="col-md-6">
                    <a href="{{ route('seller.dashboard') }}" class="btn btn-primary">Regresar</a>
                </div>
            </h1>
        </div>
        
    </div>
</div>

  
   

   

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Solicitudes de clientes</h5>
                    
                </div>
        
                <div class="card-body">
        
                     
                  
                    <div class="table-responsive-sm">
                      
                        <table id="workshop_Client_Requests" class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="row">N#</th>
                                    <th style="width: 130px;">Cliente</th>
                                    <th style="width: 130px;">Fecha de solicitud</th>
                                    <th class="text-center" >Servicio</th>
                                    <th class="text-center" style="width: 130px;">Estado propuesta</th>
                                    <th class="text-center" >Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $counter = count($workshopClientRequests);
                                @endphp


                                @foreach ($workshopClientRequests as $workshopRequest)

                                @php
                                $proposalExists = $workshopServiceProposals->where('order_id', $workshopRequest->order_id)->count() > 0;
                                @endphp
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td style="width: 130px;">{{ $workshopRequest->user->name }}</td>
                                    <td>{{ $workshopRequest->created_at->locale('es')->format('d F Y h:i A') }}</td>

                                    <td class="text-center">
                                        @if ($workshopRequest->estado_solicitud	== 'activo')
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Activo">
                                            <div class="alert alert-success" role="alert">
                                                
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>

                                            </div>
                                            </span>


                                        @else

                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Desactivado">
                                            <div class="alert alert-danger" role="alert">
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                                                    <path d="M15 8a6.973 6.973 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8ZM2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0Z"/>
                                                </svg>
                                            </div>
                                            </span>


                                        @endif
                                    </td>

                                    <td class="text-center"> 
                                        @if (!$proposalExists)
                                            @if ($workshopRequest->estado_solicitud == 'desactivad')
                                               <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Cliente aceptó servicio">
                                                <div class="alert alert-secondary" role="alert">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-index-thumb-fill" viewBox="0 0 16 16">
                                                        <path d="M8.5 1.75v2.716l.047-.002c.312-.012.742-.016 1.051.046.28.056.543.18.738.288.273.152.456.385.56.642l.132-.012c.312-.024.794-.038 1.158.108.37.148.689.487.88.716.075.09.141.175.195.248h.582a2 2 0 0 1 1.99 2.199l-.272 2.715a3.5 3.5 0 0 1-.444 1.389l-1.395 2.441A1.5 1.5 0 0 1 12.42 16H6.118a1.5 1.5 0 0 1-1.342-.83l-1.215-2.43L1.07 8.589a1.517 1.517 0 0 1 2.373-1.852L5 8.293V1.75a1.75 1.75 0 0 1 3.5 0z"/>
                                                    </svg>
                                                </div>
                                               </span> 
                                              
                                               
                                            @else
                                               <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Realiza propuesta!">
                                                <div class="alert alert-warning" role="alert">
                                                
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-text-fill" viewBox="0 0 16 16">
                                                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.5a1 1 0 0 0-.8.4l-1.9 2.533a1 1 0 0 1-1.6 0L5.3 12.4a1 1 0 0 0-.8-.4H2a2 2 0 0 1-2-2V2zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                                                    </svg>
                                                </div>
                                               </span> 
                                               
                                            @endif
                                        @else
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Esperando respuesta">
                                            <div class="alert alert-info" role="alert">
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                                </svg>
                                            </div>
                                            </span> 
                                           
                                        @endif 
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                          
                                          
                                            <div class="btn-group" role="group">
                                              <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                
                                              </button>
                                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                               
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#verworkshopRequest_{{ $workshopRequest->id }}">Ver detalles</a>
                                               
                                                @if ($proposalExists || $workshopRequest->estado_solicitud == 'desactivad')
                                                    

                                                    
                                                @else
                                                   <a class="dropdown-item" href="#" data-toggle="modal" data-target="#crearworkshopRequest_{{ $workshopRequest->id }}">Crear propuesta</a>
                                                @endif
                                                
                                              </div>
                                            </div>
                                          </div>
                                       
                                    </td>
                                </tr>

                                @php
                                $counter--;
                                @endphp


                               {{-- modal de ver --}}
                                <div class="modal fade" id="verworkshopRequest_{{ $workshopRequest->id }}" tabindex="-1" role="dialog" aria-labelledby="verworkshopRequest_Label" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="verworkshopRequest_Label">Detalles de solicitud</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">

                                                    




                                                    @if (!$proposalExists)
                                                        @if ($workshopRequest->estado_solicitud == 'desactivad')
                                                                
                                                            <div class="alert alert alert-dark" role="alert">
                                                                <h4 class="alert-heading">¡Atención!</h4>
                                                                <p>El cliente ya aceptó una propuesta. Sigue intentando con otras propuestas de clientes para realizar servicio de algún producto en la plataforma. No te preocupes que pronto algún cliente te dará oportunidad. Entre más servicios realices, mejor será tu calificación de excelente servicio.</p>
                                                            </div>
                                                        
                                                        @else
                                                                <div class="jumbotron">
                                                                    <h1 class="display-4">Estimado taller!</h1>
                                                                    <p class="lead">Este es un resumen de un producto.</p>
                                                                    <hr class="my-4">
                                                                    <p>Recientemente, compramos este producto y estamos muy contentos con él. Sin embargo, nos gustaría solicitar su servicio en referencia al producto obtenido. Estamos interesados en obtener un presupuesto para poder realizar con éxito la instalación del producto.</p>
                                                                    <p>Para realizar una propuesta en esta misma sección, haz clic en el botón "Crear propuesta" del servicio.</p>
                                                                </div>
                                                        
                                                        @endif
                                                    @else
                                                                <div class="alert alert-primary" role="alert">
                                                                    <h4 class="alert-heading">¡Felicidades!</h4>
                                                                    <p>Has realizado una propuesta de servicio a este cliente. Te estaremos avisando cuando el cliente acepte tu propuesta. También te informaremos por correo para cuando el cliente acepte tu propuesta. Además, existe otros clientes que han solicitado servicio, revisa para ver si existe otro trabajo que puedas realizar.</p>
                                                                </div>
                                                    
                                                    @endif 
                                                   


                                                    
                                                    @foreach ($workshopRequest->order->orderDetails as $key => $orderDetail)
    
                                                
                                                    <div class="card mb-3" style="max-width: 540px;">
                                                        <div class="row no-gutters">
                                                          <div class="col-md-4">
                                                            {{-- <img src="{{ $orderDetail->product->photos }}" class="card-img" alt="..."> --}}
                                                            @php
                                                                $photos = explode(',', $orderDetail->product->photos);
                                                            @endphp
    
                                                            <div id="imageSlider" class="carousel slide" data-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach ($photos as $index => $photo)
                                                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                                            <img src="{{ uploaded_asset($photo) }}" class="d-block w-100" height="170px" width="250px" alt="...">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <a class="carousel-control-prev" href="#imageSlider" role="button" data-slide="prev">
                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Previous </span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#imageSlider" role="button" data-slide="next">
                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div>
                                                          </div>
                                                          <div class="col-md-8">
                                                            <div class="card-body">
                                                              <h5 class="card-title">Producto</h5>
                                                              <p class="card-text">{{$orderDetail->product->name}}</p>
                                                              {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
    
                                                    <hr>
    
                                                    @endforeach
                                                   
                                                </div>

                                           


                                               

                                                
    
                                                  
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    
                                                    </div>
                                            </div>
                                            
                                          
                                            
                                          
                                        </div>
                                    </div>
                                </div>



                                <div class="modal fade" id="crearworkshopRequest_{{ $workshopRequest->id }}" tabindex="-1" role="dialog" aria-labelledby="crearworkshopRequest_Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="crearworkshopRequest_Label">Crear propuesta de servicio</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                           
                                            <form action="{{ route('workshop.workshopService.store') }}" method="POST">
                                                @csrf <!-- Agrega el token CSRF para protección contra ataques CSRF -->

                                          
                                                    <div class="modal-body">
                                                    
                                                    
                                                            <input type="hidden" class="form-control" id="client_id" name="client_id" value="{{$workshopRequest->user_id}}">
                                                            <input type="hidden" class="form-control" id="order_id" name="order_id" value="{{$workshopRequest->order_id}}">
                                                            {{-- negocio o teller id que hace la propuesta --}}
                                                            <input type="hidden" class="form-control" id="workshop_id" name="workshop_id" value="{{$workshop->id}}">
                                                            
                                                            <div class="form-group">
                                                                
                                                                <label for="nota">
                                                                    Nota:
                                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Para garantizar una instalación exitosa, te pedimos que nos proporciones todos los detalles relevantes sobre el servicio. Esto incluye cualquier información específica sobre el lugar de instalación, requisitos técnicos, herramientas necesarias y cualquier otro detalle importante que debamos tener en cuenta.">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                                                                        </svg>
                                                                    </span>
                                                                    
                                                                </label>
                                                                <textarea class="form-control" id="nota" name="nota" rows="3"></textarea>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="installation_amount">
                                                                    Monto de instalación:
                                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="El monto de instalación se calcula cuidadosamente teniendo en cuenta varios factores, como el tiempo estimado, los recursos necesarios y experiencia en el campo.">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                                                                        </svg>
                                                                    </span>
                                                                </label>
                                                                <div class="input-group mb-2 mr-sm-2">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">$</div>
                                                                    </div>
                                                                    <input type="number" step="0.01" class="form-control" id="installation_amount" name="installation_amount">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="date_time_inicial">
                                                                        Fecha y hora: Inicial
                                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="La Fecha y Hora Inicial es un momento importante, marca el comienzo de la inspección inicial, entrega del material de trabajo, de parte del cliente, en la fecha y hora acordadas.">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                                                                            </svg>
                                                                        </span>
                                                                    </label>
                                                                    <input type="datetime-local" class="form-control" id="date_time_inicial" name="date_time_inicial">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="date_time_final">
                                                                        Fecha y hora: Final
                                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="La Fecha y Hora Final representa la entrega estimada de la instalación completa. Nos esforzamos por cumplir con esta fecha y asegurarte que tendrás tu proyecto listo en el tiempo acordado.">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                                                                            </svg>
                                                                        </span>
                                                                    </label>
                                                                    <input type="datetime-local" class="form-control" id="date_time_final" name="date_time_final">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-row">
                                                                <div class="form-group col-md-8">
                                                                    <label for="time_estimate">
                                                                        Estimación de tiempo:
                                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="¡Hola! Queremos brindarte una estimación de tiempo para tu proyecto. Aunque hayas proporcionado una fecha estimada ejemplo de un mes, queremos aclarar que la duración real del trabajo se basará en las horas aproximadas que dedicaremos en esa fecha estimada.">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                                                                            </svg>
                                                                        </span>
                                                                    </label>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <input type="number" class="form-control text-center" id="time_estimate" name="time_estimate">
                                                                        </div> 
                                                                        <div class="form-group col-md-2 mt-4">
                                                                            <p>horas</p>
                                                                        </div> 
                                                                    </div>
                                                                </div>  
                                                            </div>
                                                            
                                                        
                                                            
                                                        
                                                            
                                                            <!-- Resto de campos -->
                                                            
                                                        
                                                        
                                                    </div>
                                                

                                                    
                                                    <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Crear</button>

                                                    </div>

                                            </form>
                                          
                                        </div>
                                    </div>
                                </div>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                   
                      
                     
        
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Propuesta servicio realizados</h5>
                </div>
        
                <div class="card-body">
        
                    <div class="table-responsive-sm">
                      
                        <table id="workshop_Service_Proposals" class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="row">N#</th>
                                    <th style="width: 130px;">Cliente</th>
                                    <th style="width: 130px;">Fecha de propuesta</th>
                                    <th>Estado</th>
                                    <th class="text-center" >Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                               



                                @foreach ($workshopServiceProposals as $index => $workshopService)
                                <tr>
                                    <td>{{ count($workshopServiceProposals) - $index }}</td>
                                    <td style="width: 130px;">{{ $workshopService->user->name }}</td>
                                    <td>{{ $workshopService->created_at->locale('es')->format('d F Y h:i A') }}</td>
                                    <td>
                                       

                                        @if ($workshopService->client_accepts_mechanic == 1)

                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Cliente aceptó servicio">
                                                <div class="alert alert-secondary" role="alert">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-index-thumb-fill" viewBox="0 0 16 16">
                                                        <path d="M8.5 1.75v2.716l.047-.002c.312-.012.742-.016 1.051.046.28.056.543.18.738.288.273.152.456.385.56.642l.132-.012c.312-.024.794-.038 1.158.108.37.148.689.487.88.716.075.09.141.175.195.248h.582a2 2 0 0 1 1.99 2.199l-.272 2.715a3.5 3.5 0 0 1-.444 1.389l-1.395 2.441A1.5 1.5 0 0 1 12.42 16H6.118a1.5 1.5 0 0 1-1.342-.83l-1.215-2.43L1.07 8.589a1.517 1.517 0 0 1 2.373-1.852L5 8.293V1.75a1.75 1.75 0 0 1 3.5 0z"/>
                                                    </svg>
                                                </div>
                                           </span> 
                                              
                                        @else
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Esperando respuesta">
                                                <div class="alert alert-info" role="alert">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                                    </svg>
                                                </div>
                                            </span> 
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Ver detalle">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#workshopService_{{ $workshopService->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                </svg>
                                            </button>
                                        </span>

                                    </td>
                                </tr>

                               

                               {{-- modal de ver --}}
                                <div class="modal fade" id="workshopService_{{ $workshopService->id }}" tabindex="-1" role="dialog" aria-labelledby="workshopService_Label" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="workshopService_Label">Detalles de solicitud</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        

                                        
                                            <div class="modal-body">

                                                
                                                @if ($workshopService->client_accepts_mechanic == 1)  
                                                  
                                                  <div class="alert alert-success" role="alert">
                                                    <p>¡Felicidades! El cliente ha aceptado tu servicio y está listo para que lo atiendas. Ahora puedes ver la sección de cargos adicionales que se ha habilitado en esta página. Allí podrás agregar los costos extras que necesites para realizar el servicio con éxito. Te agradezco por tu confianza y tu profesionalismo.</p>
                                                  </div>

                                                @else
                                                    
        
                                                    <div class="card mb-3" style="max-width: 100%">
                                                        <div class="row no-gutters">
                                                        <div class="col-md-4">
                                                            <img src="https://img.freepik.com/vector-premium/concepto-trabajo-equipo-diseno-plano-dispositivo_52683-76708.jpg?size=626&ext=jpg&ga=GA1.1.1016474677.1697241600&semt=ais" class="card-img" alt="...">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                            <h5 class="card-title">Informacion en espera!</h5>
                                                            <p class="card-text">Estoy contento de que hayas enviado tu propuesta al cliente. Espero que sea de su agrado y que acepte el servicio que ofreces.</p>
                                                            
                                                            <p>Te informaré tan pronto como tenga una respuesta del cliente sobre tu propuesta. Si el cliente acepta tu propuesta, podrás ver la sección de cargos adicionales en esta misma página. En esa sección, podrás agregar los costos extras que necesites para realizar el servicio con éxito. Te deseo lo mejor.</p>
                                                            
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
        
        
                                                @endif

                                                <div class="row">
                                                    @foreach ($workshopService->order->orderDetails as $key => $orderDetail)
                                                      <div class="col-md-6 mb-3">
                                                        <div class="card" style="max-width: 540px;">
                                                          <div class="row no-gutters">
                                                            <div class="col-md-4">
                                                              {{-- <img src="{{ $orderDetail->product->photos }}" class="card-img" alt="..."> --}}
                                                              @php
                                                                $photos = explode(',', $orderDetail->product->photos);
                                                              @endphp
                                                              <div id="imageSlider" class="carousel slide" data-ride="carousel">
                                                                <div class="carousel-inner">
                                                                  @foreach ($photos as $index => $photo)
                                                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                                      <img src="{{ uploaded_asset($photo) }}" class="d-block w-100" height="170px" width="250px" alt="...">
                                                                    </div>
                                                                  @endforeach
                                                                </div>
                                                                <a class="carousel-control-prev" href="#imageSlider" role="button" data-slide="prev">
                                                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                  <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#imageSlider" role="button" data-slide="next">
                                                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                  <span class="sr-only">Next</span>
                                                                </a>
                                                              </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <div class="card-body">
                                                                <h5 class="card-title">Producto</h5>
                                                                <p class="card-text">{{ $orderDetail->product->name }}</p>
                                                                {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    @endforeach
                                                </div>
                                                <hr>

                                                <div class="card">
                                                    <div class="card-header">
                                                      Propuesta
                                                    </div>
                                                    <div class="card-body">
                                                      <h5 class="card-title">Nota:</h5>
                                                      <p class="card-text">{{ $workshopService->nota }}</p>

                                                      <hr>

                                                      <h5 class="card-title">Monto de instalacion:</h5>
                                                      <p class="card-text">{{ $workshopService->installation_amount }}</p>

                                                      <hr>

                                                      <div class="row">
                                                        <div class="col-md-6">
                                                          <h5 class="card-title">Fecha de inicio:</h5>
                                                          <p class="card-text">{{ $workshopService->date_time_inicial }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                          <h5 class="card-title">Fecha final:</h5>
                                                          <p class="card-text">{{ $workshopService->date_time_final }}</p>
                                                        </div>
                                                      </div>

                                                      <hr>

                                                      <h5 class="card-title">Tiempo estimado:</h5>
                                                      <p class="card-text">{{ $workshopService->time_estimate }} Horas</p>

                                                      <hr>

                                                    </div>
                                                    <div class="card-footer text-muted">
                                                      Fecha creacion propuesta: {{ $workshopService->created_at->locale('es')->format('d F Y h:i A') }}
                                                    </div>
                                                </div>

                                                @if ($workshopService->client_accepts_mechanic == 1)  
                                                  
                                                    <div class="card">
                                                        <div class="card-header">
                                                           Cargos adicionales
                                                        </div>
                                                        <div class="card-body">
                                                            <h5 class="card-title">Cargos adicionales:</h5>
                                                            <p class="card-text">Cargos adicionales realizados a esta orden</p>
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="col" style="flex: 0 0 5%"><strong>#</strong></div>
                                                                        <div class="col" style="flex: 0 0 20%"><strong>Fecha</strong></div>
                                                                        <div class="col"  style="flex: 0 0 10%"><strong>Monto</strong></div>
                                                                        <div class="col" style="flex: 0 0 10%"><strong>Estado de entrega</strong></div>
                                                                        <div class="col" style="flex: 0 0 25%"><strong>Nota</strong></div>
                                                                        <div class="col"><strong>Estado de pago</strong></div>
                                                                        <div class="col"><strong>Acciones</strong></div>
                                                                    </div>
                                                                </div>

                                                                <div class="container-fluid">
                                                                    <hr style="border-width: 5px;">
                                                                </div>

                                                               

                                                                @php
                                                                $additionalCharges = $workshopService->workshopAdditionalCharges()->orderBy('created_at', 'desc')->get();
                                                                $counter = $additionalCharges->count();
                                                            @endphp
                                                            
                                                            @foreach ($additionalCharges as $key => $additionalCharge)
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="col" style="flex: 0 0 5%">{{ $counter }}</div>
                                                                        <div class="col" style="flex: 0 0 20%">{{ $additionalCharge->created_at->locale('es')->format('d F Y h:i A') }}</div>
                                                                        <div class="col"  style="flex: 0 0 10%">{{ $additionalCharge->monto }}</div>
                                                                        <div class="col"  style="flex: 0 0 10%">{{ $additionalCharge->estado_entrega }}</div>
                                                                        <div class="col" style="flex: 0 0 25%">
                                                                            <div class="overflow-auto p-1 mb-1 mb-md-0 mr-md-1 bg-light" style="max-width: 100%; max-height: 100px;">
                                                                                {{ $additionalCharge->nota }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col">
                                                                            @if($additionalCharge->estado_pago === 'proceso')
                                                                                <div class="alert alert-info" role="alert">
                                                                                    En Proceso
                                                                                </div>
                                                                            @elseif($additionalCharge->estado_pago === 'pagado')
                                                                                <div class="alert alert-success" role="alert">
                                                                                    Pagado
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col">
                                                                            <form action="{{ route('workshop.WorkshopAdditional.destroy', $additionalCharge->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger delete-btn">Eliminar</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Línea horizontal entre recorridos -->
                                                                <div class="container-fluid">
                                                                    <hr>
                                                                </div>

                                                                @php
                                                                $counter--;
                                                                @endphp
                                                            @endforeach

                                                            @if (count($additionalCharges) == 0)
                                                                <div class="container text-center my-4">
                                                                    <p>No tienes cargos adicionales</p>
                                                                </div>
                                                            @endif
                                                                

                                                                
                                                               
                                                               



                                                                
                                                                
                                                               
                                                        </div>
                                                        <div class="card-footer text-muted">
                                                           
                                                           
                                                            <div class="form-row">
                                                                <form action="{{ route('workshop.WorkshopAdditional.store') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="proposal_id" value="{{ $workshopService->id }}">
                                                                    <div class="form-group col-md-3">
                                                                        <label for="tipoCargo">Tipo de Cargo</label>
                                                                        <select class="form-control" id="tipoCargo" name="tipoCargo">
                                                                            <option value="reparacion">Reparación</option>
                                                                            <option value="mantenimiento">Mantenimiento</option>
                                                                            <option value="otros">Otros</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label for="monto">Monto</label>
                                                                        <input type="text" class="form-control" id="monto" name="monto">
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label for="horas">Horas</label>
                                                                        <input type="text" class="form-control" id="horas" name="horas">
                                                                    </div>
                                                                    <div class="form-group col-md-3">
                                                                        <label for="horas">Nota</label>
                                                                        <textarea class="form-control" id="nota" name="nota"></textarea>
                                                                    </div>
                                                                    <div class="form-group col-md-2 d-flex align-items-end">
                                                                        <button class="btn btn-primary abrir-modal-workshopService" type="submit">Agregar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        


                                                               
                                                     
                                                                 

                                                        </div>
                                                    </div>

                                                @else

                                                @endif
                                                
                                              
                                            
                                            </div>
                                        

                                            
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                                </div>
                                        
                                        </div>
                                    </div>
                                </div>


                            @endforeach
                            </tbody>
                        </table>
                    </div>
                  
                  


                </div>
            </div>
        </div>
    </div>

    
 





@endsection

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.css" />





@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
   $(document).ready(function() {
        // Verificar si hay un modal guardado en el almacenamiento local
        var modalAbierto = localStorage.getItem('modalAbierto');
        if (modalAbierto) {
            // Eliminar el modal guardado del almacenamiento local
            localStorage.removeItem('modalAbierto');
            // Abrir el modal correspondiente al ID guardado
            if (!modalAbierto.includes('crearworkshopRequest')) {
                $('#' + modalAbierto).modal('show');
            }
        }
    });

    $(document).on('submit', 'form', function(event) {
        // Obtener el ID del modal actual
        var modalId = $(this).closest('.modal').attr('id');
        // Guardar el ID del modal en el almacenamiento local
        if (!modalId.includes('crearworkshopRequest')) {
            localStorage.setItem('modalAbierto', modalId);
        }
        // Realizar el envío del formulario
        $(this).submit();
    });

    $('.abrir-modal-workshopService').click(function(event) {
        // Obtener el ID del modal actual
        var modalId = $(this).closest('.modal').attr('id');
        // Guardar el ID del modal en el almacenamiento local
        if (!modalId.includes('crearworkshopRequest')) {
            localStorage.setItem('modalAbierto', modalId);
        }
    });
        // function guardarModal(event) {
           
        //     // Obtener el ID del modal actual
        //     var modalId = event.target.closest('.modal').id;

        //     // Guardar el ID del modal en el almacenamiento local
        //     localStorage.setItem('modalAbierto', modalId);

        //     // Realizar el envío del formulario
        //     event.target.closest('form').submit();
        // }

        // $(document).ready(function() {
        //     // Verificar si hay un modal guardado en el almacenamiento local
        //     var modalAbierto = localStorage.getItem('modalAbierto');
        //     if (modalAbierto) {
        //         // Eliminar el modal guardado del almacenamiento local
        //         localStorage.removeItem('modalAbierto');

        //         // Abrir el modal correspondiente al ID guardado
        //         $('#' + modalAbierto).modal('show');
        //     }
        // });

        // $(document).on('submit', 'form', function(event) {
            

        //     // Obtener el ID del modal actual
        //     var modalId = $(this).closest('.modal').attr('id');

        //     // Guardar el ID del modal en el almacenamiento local
        //     localStorage.setItem('modalAbierto', modalId);

        //     // Realizar el envío del formulario
        //     $(this).submit();
        // });
</script>











{{-- <script>
    $(function() {
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var link = $(this).attr("action");
            var form = $(this).closest("form");
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, bórralo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value && result.value === true) {
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Acción Cancelada",
                        text: "La acción fue cancelada",
                        icon: "info"
                    });
                }
            });
        });
    });
</script> --}}

<script>
   $(document).ready(function() {
        $('#workshop_Client_Requests').DataTable({
            responsive: true,
            "autoWidth": false,
            language: {
                searchPlaceholder: "Buscar...",
                search: "",
                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",
            },
            'order': [],
            'columnDefs': [{
                    orderable: false,
                    targets: 0
                }, // Disable ordering on column 0 (checkbox)
            ],

        
        });
    });

</script>

<script>
    $(document).ready(function() {
         $('#workshop_Service_Proposals').DataTable({
             responsive: true,
             "autoWidth": false,
             language: {
                 searchPlaceholder: "Buscar...",
                 search: "",
                 url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",
             },
             'order': [],
             'columnDefs': [{
                     orderable: false,
                     targets: 0
                 }, // Disable ordering on column 0 (checkbox)
             ],
 
         
         });
     });
 
 </script>




   

        
    
@endsection
<script>
  
</script>
