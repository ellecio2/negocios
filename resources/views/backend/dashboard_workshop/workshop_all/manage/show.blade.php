@extends('backend.layouts.app')

@section('content')
   
       

        <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="container-fluid">
                      <div class="row">
                        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                          <div class="sidebar-sticky pt-3">
                            <ul class="nav flex-column">
                              <li class="nav-item">
                                <a class="nav-link active" href="#">
                                  <span data-feather="home"></span>
                                  Perfil
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="file"></span>
                                  Servicios Solicitudes
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="shopping-cart"></span>
                                  Compras realizados
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="users"></span>
                                  Comentarios de servicio
                                </a>
                              </li>
                              
                            </ul>
                    
                            {{-- <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                              <span>Saved reports</span>
                              <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
                                <span data-feather="plus-circle"></span>
                              </a>
                            </h6>
                            <ul class="nav flex-column mb-2">
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="file-text"></span>
                                  Current month
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="file-text"></span>
                                  Last quarter
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="file-text"></span>
                                  Social engagement
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">
                                  <span data-feather="file-text"></span>
                                  Year-end sale
                                </a>
                              </li>
                            </ul> --}}
                          </div>
                        </nav>
                    
                        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Perfil</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                              {{-- <div class="btn-group mr-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                              </div>
                              <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                                <span data-feather="calendar"></span>
                                This week
                              </button> --}}
                              <a class="btn btn-primary" href="{{ route('backend.dashboard_workshop.workshop_all.index') }}"
                              role="button">Regresar</a>
                            </div>
                          </div>
                    
                        
                    
                          {{-- <h2>Section title</h2> --}}
                          <div class="table-responsive">

                            <div class="card mb-3" style="max-width: 540px;">
                              <div class="row no-gutters">
                                <div class="col-md-4">
                                  <img src="https://png.pngtree.com/template/20220419/ourmid/pngtree-photo-coming-soon-abstract-admin-banner-image_1262901.jpg" class="card-img" alt="...">
                                </div>
                                <div class="col-md-8">
                                  <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <form class="was-validated">
                              <div class="mb-3">
                                <label for="validationTextarea">Textarea</label>
                                <textarea class="form-control is-invalid" id="validationTextarea" placeholder="Required example textarea" required></textarea>
                                <div class="invalid-feedback">
                                  Please enter a message in the textarea.
                                </div>
                              </div>
                            
                              <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customControlValidation1" required>
                                <label class="custom-control-label" for="customControlValidation1">Check this custom checkbox</label>
                                <div class="invalid-feedback">Example invalid feedback text</div>
                              </div>
                            
                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="radio-stacked" required>
                                <label class="custom-control-label" for="customControlValidation2">Toggle this custom radio</label>
                              </div>
                              <div class="custom-control custom-radio mb-3">
                                <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked" required>
                                <label class="custom-control-label" for="customControlValidation3">Or toggle this other custom radio</label>
                                <div class="invalid-feedback">More example invalid feedback text</div>
                              </div>
                            
                              <div class="mb-3">
                                <select class="custom-select" required>
                                  <option value="">Choose...</option>
                                  <option value="1">One</option>
                                  <option value="2">Two</option>
                                  <option value="3">Three</option>
                                </select>
                                <div class="invalid-feedback">Example invalid custom select feedback</div>
                              </div>
                            
                              <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="validatedCustomFile" required>
                                <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                <div class="invalid-feedback">Example invalid custom file feedback</div>
                              </div>
                            
                              <div class="mb-3">
                                <div class="input-group is-invalid">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text" id="validatedInputGroupPrepend">@</span>
                                  </div>
                                  <input type="text" class="form-control is-invalid" aria-describedby="validatedInputGroupPrepend" required>
                                </div>
                                <div class="invalid-feedback">
                                  Example invalid input group feedback
                                </div>
                              </div>
                            
                              <div class="mb-3">
                                <div class="input-group is-invalid">
                                  <div class="input-group-prepend">
                                    <label class="input-group-text" for="validatedInputGroupSelect">Options</label>
                                  </div>
                                  <select class="custom-select" id="validatedInputGroupSelect" required>
                                    <option value="">Choose...</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                  </select>
                                </div>
                                <div class="invalid-feedback">
                                  Example invalid input group feedback
                                </div>
                              </div>
                            
                              <div class="input-group is-invalid">
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="validatedInputGroupCustomFile" required>
                                  <label class="custom-file-label" for="validatedInputGroupCustomFile">Choose file...</label>
                                </div>
                                <div class="input-group-append">
                                   <button class="btn btn-outline-secondary" type="button">Button</button>
                                </div>
                              </div>
                              <div class="invalid-feedback">
                                Example invalid input group feedback
                              </div>

                              <div style="display: flex; justify-content: center; align-items: center">
                                <button type="submit" class="btn btn-success" style="width: 200px">Guardar</button>
                              </div>
                            </form>
                          </div>
                        </main>
                      </div>
                    </div>
                  </div>
                 
                </div> 
              </div>
            </div>
        </div>
       

        
        
    
@endsection
@section('script')
   
@endsection
