@extends('backend.layouts.app')



@section('content')



    @php

        CoreComponentRepository::instantiateShopRepository();

        CoreComponentRepository::initializeCache();

    @endphp



    <div class="aiz-titlebar text-left mt-2 mb-3">

        <h5 class="mb-0 h6">{{ translate('Add New Product') }}</h5>

    </div>

    <div class="">

        <!-- Error Meassages -->

        @if ($errors->any())

            <div class="alert alert-danger">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form class="form form-horizontal mar-top" action="{{ route('products.store') }}" method="POST"

            enctype="multipart/form-data" id="choice_form">

            <div class="row gutters-5 ">

                <div class="col-lg-8">

                    @csrf

                    <input type="hidden" name="added_by" value="admin">

                    <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>

                        </div>

                        <div class="card-body">

                            <div class="form-group row" id="category">

                                <label class="col-md-3 col-from-label">{{ translate('Category') }} <span

                                        class="text-danger">*</span></label>

                                <div class="col-md-8">

                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id" onchange="getBrandsByCategory()"

                                        data-live-search="true" required>

                                         <option value="" selected>Seleccionar Catergoría</option>



                                        @foreach ($categories as $category)

                                            <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}

                                            </option>

                                            @foreach ($category->childrenCategories as $childCategory)

                                                @include('categories.child_category', [

                                                    'child_category' => $childCategory,

                                                ])

                                            @endforeach

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            <div class="form-group row" id="brand">

                                <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>

                                <div class="col-md-8">

                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"

                                        data-live-search="true" required>

                                        

                                        <option value="">Seleccionar Marca</option>

                                        @foreach (\App\Models\Brand::all() as $brand)

                                            <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}

                                            </option>

                                            

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            <div class="form-group row">

                                    <label class="col-md-3 col-from-label">

                                       Código de Producto (S/N | {{ translate('SKU') }})

                                    </label>

                                    <div class="col-md-8">

                                        <input type="text" placeholder="S/N | {{ translate('SKU') }}" name="sku"

                                            class="form-control">

                                    </div>

                                </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span

                                        class="text-danger">*</span></label>

                                <div class="col-md-8">

                                    <input type="text" class="form-control " name="name"

                                        placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>

                                </div>

                            </div>

                            

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>

                                <div class="col-md-8">

                                    <textarea class="aiz-text-editor" name="description"></textarea>

                                </div>

                            </div>

                          

                            <div class="form-group row">

                                <label class="col-md-3 col-form-label"

                                    for="signinSrEmail">Especificación Técnica (PDF)</label>

                                <div class="col-md-8">

                                    <div class="input-group" data-toggle="aizuploader" data-type="document">

                                        <div class="input-group-prepend">

                                            <div class="input-group-text bg-soft-secondary font-weight-medium">

                                                {{ translate('Browse') }}</div>

                                        </div>

                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                        <input type="hidden" name="pdf" class="selected-files">

                                    </div>

                                    <div class="file-preview box sm">

                                    </div>

                                </div>

                            </div>





                            <div class="form-group row">

                                <label class="col-md-3 col-form-label"

                                    for="signinSrEmail">Imagenes del Producto <small>(600x600)</small></label>

                                <div class="col-md-8">

                                    <div class="input-group" data-toggle="aizuploader" data-type="image"

                                        data-multiple="true">

                                        <div class="input-group-prepend">

                                            <div class="input-group-text bg-soft-secondary font-weight-medium">

                                                {{ translate('Browse') }}</div>

                                        </div>

                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                        <input type="hidden" name="photos" class="selected-files">

                                    </div>

                                    <div class="file-preview box sm">

                                    </div>

                                    <small

                                        class="text-muted">{{ translate('These images are visible in product details page gallery. Use 600x600 sizes images.') }}</small>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-form-label"

                                    for="signinSrEmail">Imagen miniatura del Producto

                                    <small>(300x300)</small></label>

                                <div class="col-md-8">

                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                        <div class="input-group-prepend">

                                            <div class="input-group-text bg-soft-secondary font-weight-medium">

                                                {{ translate('Browse') }}</div>

                                        </div>

                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                        <input type="hidden" name="thumbnail_img" class="selected-files">

                                    </div>

                                    <div class="file-preview box sm">

                                    </div>

                                    <small

                                        class="text-muted">{{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Video Provider') }}</label>

                                <div class="col-md-8">

                                    <select class="form-control aiz-selectpicker" name="video_provider"

                                        id="video_provider">

                                        <option value="youtube">{{ translate('Youtube') }}</option>

                                        <option value="dailymotion">{{ translate('Dailymotion') }}</option>

                                        <option value="vimeo">{{ translate('Vimeo') }}</option>

                                    </select>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>

                                <div class="col-md-8">

                                    <input type="text" class="form-control" name="video_link"

                                        placeholder="{{ translate('Video Link') }}">

                                    <small

                                        class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>

                                </div>

                            </div>



                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Tags') }} <span

                                        class="text-danger">*</span></label>

                                <div class="col-md-8">

                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"

                                        placeholder="{{ translate('Type and hit enter to add a tag') }}">

                                    <small

                                        class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>

                                </div>

                            </div>



                            @if (addon_is_activated('pos_system'))

                                <div class="form-group row">

                                    <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>

                                    <div class="col-md-8">

                                        <input type="text" class="form-control" name="barcode"

                                            placeholder="{{ translate('Barcode') }}">

                                           

                                    </div>

                                </div>

                            @endif

                            <div class="form-group row">



                            <label class="col-md-3 col-from-label">{{ translate('Colors') }}</label>

                            <div class="col-md-2">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input value="1" type="checkbox" name="colors_active">

                                        <span></span>

                                    </label>

                                </div>

                            

                                <div class="col-md-6">

                                    <select class="form-control aiz-selectpicker" data-live-search="true"

                                        data-selected-text-format="count" name="colors[]" id="colors" multiple

                                        disabled>

                                        @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)

                                            <option value="{{ $color->code }}"

                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>">

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                              

                            </div>



                            @if (addon_is_activated('refund_request'))

                                <div class="form-group row">

                                    <label class="col-md-3 col-from-label">Reembolsable <br><small

                                        class="text-muted">Esto indica si el producto tiene devolución.</small></label>

                                    <div class="col-md-8">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="checkbox" name="refundable" checked value="1">

                                            <span></span>

                                        </label>

                                        

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                   

                   

                    <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>

                        </div>

                        <div class="card-body">

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Unit price') }} <span

                                        class="text-danger">*</span></label>

                                <div class="col-md-4">

                                    <input type="number" lang="en_US" min="0" value="0" step="0.01"

                                        placeholder="{{ translate('Unit price') }}" name="unit_price"

                                        class="form-control" required>

                                </div>



                                     <!-- DETALLE DEL PRECIO FINAL -->

                                                                                                                                                                                                            

                            <label class="col-lg-1 col-from-label"><b>Precio Final</b></label>

                            <div class="col-md-3">

                                <input type="text" lang="en_US" min="0" value="0" step="0.01"

                                    name="itbis_price"

                                    class="form-control" style="font-size: 1.5em; font-weight: bold; color: white; background-color: #00a600;" readonly>

                            </div>

                            </div>



                            



                            @if (addon_is_activated('club_point'))

                                <div class="form-group row">

                                    <label class="col-md-3 col-from-label">

                                        Fijar PiezaPuntos

                                    </label>

                                    <div class="col-md-6">

                                        <input type="number" lang="en" min="0" value="0"

                                            step="1" placeholder="{{ translate('1') }}" name="earn_point"

                                            class="form-control">

                                    </div>

                                </div>

                            @endif



                            <div id="show-hide-div">

                                <div class="form-group row">

                                    <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span

                                            class="text-danger">*</span></label>

                                    <div class="col-md-6">

                                        <input type="number" lang="en" min="0" value="0"

                                            step="1" placeholder="{{ translate('Quantity') }}"

                                            name="current_stock" class="form-control" required>

                                    </div>

                                </div>

                                <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Unit') }}</label>

                                <div class="col-md-8">

                                    <input type="text" class="form-control" name="unit"

                                        placeholder="{{ translate('Unit (e.g. Lib, Pc etc)') }}" required>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Weight') }}

                                    <small>(en Libras)</small></label>

                                <div class="col-md-8">

                                    <input type="number" class="form-control" name="weight" step="0.01" value="0.00"

                                        placeholder="0.00">

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Minimum Purchase Qty') }} <span

                                        class="text-danger">*</span></label>

                                <div class="col-md-8">

                                    <input type="number" lang="en" class="form-control" name="min_qty" value="1"

                                        min="1" required>

                                </div>

                            </div>

                               

                            </div>



                            @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)



                                <?php  

                                if(!empty($tax->id==3)){

                                    ?>

                                <div style="display:none">

                                <label for="name">

                                    {{ $tax->name }}

                                    <input type="hidden" value="{{ $tax->id }}"  name="tax_id[]">

                                </label> 



                                <div class="form-row">

                                    <div class="form-group col-md-6">

                                        <input type="number" lang="en" min="18" value="18"

                                            step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" id="itbis"

                                            class="form-control" required>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <select class="form-control aiz-selectpicker" name="tax_type[]">

                                            <option value="percent" selected>{{ translate('Percent') }}</option>

                                        </select>

                                    </div>

                                </div>

                                </div>



                                    <?php



                                }else{

                                

                                

                                ?>



                            <div class="form-group row">



                            

                                <label for="name"  class="col-md-3 col-from-label">

                                    {{ $tax->name }}

                                   

                                </label>



                           <div class="col-md-8 row">

                                           <div class="col-md-6">

                                            <input type="number" lang="en" min="0" value="0"

                                                step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]"

                                                class="form-control" required>

                                             </div>



                                            <div class="col-md-6">

                                                <select class="form-control aiz-selectpicker" name="tax_type[]">

                                                <option value="amount">{{ translate('Flat') }}</option>

                                                <option value="percent">{{ translate('Percent') }}</option>

                                                </select>



                                            <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">

                                           </div>

                                                





                                        </div>

                                       

                                

                            </div>

                                <?php } ?>



                            @endforeach

    

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">

                                    {{ translate('External link') }}

                                </label>

                                <div class="col-md-8">

                                    <input type="text" placeholder="{{ translate('External link') }}"

                                        name="external_link" class="form-control">

                                    <small

                                        class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">

                                    {{ translate('External link button text') }}

                                </label>

                                <div class="col-md-8">

                                    <input type="text" placeholder="{{ translate('External link button text') }}"

                                        name="external_link_btn" class="form-control">

                                    <small

                                        class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>

                                </div>

                            </div>

                            <br>

                            <div class="sku_combination" id="sku_combination">



                            </div>

                        </div>

                    </div>

                </div>



                <div class="col-lg-4">

                    <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">

                            Atributos de Envío del Producto

                            </h5>

                            <button type="button" class="btn btn-success" id="myImg">Ver GUIA DE TAMAÑOS</button>

                        </div>



                         <!-- Modal Guia Tamanos -->

                         <div id="myModal" class="modal">

                        

                        <img class="modal-content" id="img01">

                        <div id="caption"></div>

                        </div>



                        <div class="card-body">

                         



                            <div class="form-group row gutters-5">

                                <div class="col-md-3">

                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"

                                        disabled>

                                </div>

                                <div class="col-md-8">

                                    <select name="choice_attributes[]" id="choice_attributes"

                                        class="form-control aiz-selectpicker" data-selected-text-format="count"

                                        data-live-search="true" multiple

                                        data-placeholder="{{ translate('Choose Attributes') }}" required>

                                        @foreach (\App\Models\Attribute::all() as $key => $attribute)

                                            <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            <div>

                                <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}

                                </p>

                                <br>

                            </div>



                            <div class="customer_choice_options" id="customer_choice_options">



                            </div>

                        </div>



                                                    <!-- STILO PARA EL MODAL -->

                                                    <style>



                                                    #myImg {

                                                        border-radius: 5px;

                                                        cursor: pointer;

                                                        transition: 0.3s;

                                                    }



                                                    #myImg:hover {opacity: 0.7;}



                                                    /* The Modal (background) */

                                                    .modal {

                                                        display: none; /* Hidden by default */

                                                        position: fixed; /* Stay in place */

                                                        z-index: 1; /* Sit on top */

                                                        padding-top: 100px; /* Location of the box */

                                                        left: 0;

                                                        top: 0;

                                                        width: 100%; /* Full width */

                                                        height: 100%; /* Full height */

                                                        overflow: auto; /* Enable scroll if needed */

                                                        background-color: rgb(0,0,0); /* Fallback color */

                                                        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */

                                                    }



                                                    /* Modal Content (Image) */

                                                    .modal-content {

                                                        margin: auto;

                                                        display: block;

                                                        width: 90%;

                                                        max-width: 900px;

                                                    }



                                                    /* Add Animation - Zoom in the Modal */

                                                    .modal-content, #caption {

                                                        animation-name: zoom;

                                                        animation-duration: 0.6s;

                                                    }



                                                    @keyframes zoom {

                                                        from {transform:scale(0)}

                                                        to {transform:scale(1)}

                                                    }



                                                    /* The Close Button */

                                                    .close {

                                                        position: absolute;

                                                        /* top: 15px; */

                                                        right: 35px;

                                                        color: #f1f1f1;

                                                        font-size: 40px;

                                                        font-weight: bold;

                                                        transition: 0.3s;

                                                    }



                                                    .close:hover,

                                                    .close:focus {

                                                        color: #bbb;

                                                        text-decoration: none;

                                                        cursor: pointer;

                                                    }



                                                    /* 100% Image Width on Smaller Screens */

                                                    @media only screen and (max-width: 700px){

                                                        .modal-content {

                                                        width: 100%;

                                                        }

                                                    }

                                                    </style>







                        <!-- <div class="card-body">

                            @if (get_setting('shipping_type') == 'product_wise_shipping')

                                <div class="form-group row">

                                    <label class="col-md-6 col-from-label">{{ translate('Free Shipping') }}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="radio" name="shipping_type" value="free" >

                                            <span></span>

                                        </label>

                                    </div>

                                </div>



                                <div class="form-group row">

                                    <label class="col-md-6 col-from-label">{{ translate('Flat Rate') }}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="radio" name="shipping_type" value="flat_rate">

                                            <span></span>

                                        </label>

                                    </div>

                                </div>



                                <div class="flat_rate_shipping_div" style="display: none">

                                    <div class="form-group row">

                                        <label class="col-md-6 col-from-label">{{ translate('Shipping cost') }}</label>

                                        <div class="col-md-6">

                                            <input type="number" lang="en" min="0" value="0"

                                                step="0.01" placeholder="{{ translate('Shipping cost') }}"

                                                name="flat_shipping_cost" class="form-control" required>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-group row">

                                    <label

                                        class="col-md-6 col-from-label">{{ translate('Is Product Quantity Mulitiply') }}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="checkbox" name="is_quantity_multiplied" value="1">

                                            <span></span>

                                        </label>

                                    </div>

                                </div>

                            @else

                                <p>

                                    {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}

                                    <a href="{{ route('shipping_configuration.index') }}"

                                        class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">

                                        <span class="aiz-side-nav-text">{{ translate('Shipping Configuration') }}</span>

                                    </a>

                                </p>

                            @endif

                        </div> -->

                    </div>

                    <!-- <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">

                                {{ translate('Stock Visibility State') }}

                            </h5>

                        </div>



                        <div class="card-body">



                            <div class="form-group row">

                                <label class="col-md-6 col-from-label">{{ translate('Show Stock Quantity') }}</label>

                                <div class="col-md-6">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input type="radio" name="stock_visibility_state" value="quantity" checked>

                                        <span></span>

                                    </label>

                                </div>

                            </div>



                            <div class="form-group row">

                                <label

                                    class="col-md-6 col-from-label">{{ translate('Show Stock With Text Only') }}</label>

                                <div class="col-md-6">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input type="radio" name="stock_visibility_state" value="text">

                                        <span></span>

                                    </label>

                                </div>

                            </div>



                            <div class="form-group row">

                                <label class="col-md-6 col-from-label">{{ translate('Hide Stock') }}</label>

                                <div class="col-md-6">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input type="radio" name="stock_visibility_state" value="hide">

                                        <span></span>

                                    </label>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-6 col-from-label"> {{ translate('Low Stock Quantity Warning') }}</label>

                                <div class="col-md-4">

                              

                                        <input type="number" name="low_stock_quantity" value="3" min="0"

                                    step="1" class="form-control rounded-15px">

                                        <span></span>

                                 

                                </div>

                            </div>

                            



                        </div>

                    </div> -->



                    <!-- <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">{{ translate('Cash On Delivery') }}</h5>

                        </div>

                        <div class="card-body">

                            @if (get_setting('cash_payment') == '1')

                                <div class="form-group row">

                                    <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="checkbox" name="cash_on_delivery" value="1"

                                                >

                                            <span></span>

                                        </label>

                                    </div>

                                </div>

                            @else

                                <p>

                                    {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}

                                    <a href="{{ route('activation.index') }}"

                                        class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">

                                        <span class="aiz-side-nav-text">{{ translate('Cash Payment Activation') }}</span>

                                    </a>

                                </p>

                            @endif

                        </div>

                    </div> -->



                    <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">Configuración en Pagina Principal</h5>

                        </div>

                        <div class="card-body">

                            <div class="form-group row">

                                <label class="col-md-6 col-from-label">Publicar en Destacados?</label>

                                <div class="col-md-6">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input type="checkbox" name="featured" value="1">

                                        <span></span>

                                    </label>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-6 col-from-label">Publicar en Oferta de Hoy?</label>

                                <div class="col-md-6">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input type="checkbox" name="todays_deal" value="1">

                                        <span></span>

                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">Descuentos Automáticos</h5><br>

                            <small

                                        class="text-muted">Estos descuentos se aplican en los ragos de las fechas seleccionadas.</small>

                        </div>

                        <div class="card-body">

                        <div class="form-group row">

                                <label class="col-sm-3 control-label"

                                    for="start_date">Rango de Fechas</label>

                                <div class="col-sm-8">

                                    <input type="text" class="form-control aiz-date-range" name="date_range"

                                        placeholder="Fecha Inicio y Final" data-time-picker="true"

                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">

                                </div>

                            </div>



                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Discount') }} <span

                                        class="text-danger">*</span></label>

                                <div class="col-md-4">

                                    <input type="number" lang="en" min="0" value="0" step="0.01"

                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control"

                                        required>

                                </div>

                                <div class="col-md-4">

                                    <select class="form-control aiz-selectpicker" name="discount_type">

                                        <option value="amount">{{ translate('Flat') }}</option>

                                        <option value="percent">{{ translate('Percent') }}</option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>



                   



                    <div class="card rounded-15p col-lg-12"">

                        <div class="card-header">

                            <h5 class="mb-0 h6">Configurar {{ translate('Flash Deal') }}</h5> <br>

                            <small

                                        class="text-muted">Estas ofertas activan las ventas Rápidas con descuento del producto.</small>

                        </div>

                        <div class="card-body">

                            <div class="form-group mb-3 >

                                <label for="name">

                                    {{ translate('Add To Flash') }}

                                </label>

                                <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">

                                    <option value="">{{ translate('Choose Flash Title') }}</option>

                                    @foreach (\App\Models\FlashDeal::where('status', 1)->get() as $flash_deal)

                                        <option value="{{ $flash_deal->id }}">

                                            {{ $flash_deal->title }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            <div class="form-group mb-3">

                                <label for="name">

                                    {{ translate('Discount') }}

                                </label>

                                <input type="number" name="flash_discount" value="0" min="0"

                                    step="0.01" class="form-control">

                            </div>

                            <div class="form-group mb-3">

                                <label for="name">

                                    {{ translate('Discount Type') }}

                                </label>

                                <select class="form-control aiz-selectpicker" name="flash_discount_type"

                                    id="flash_discount_type">

                                    <option value="">{{ translate('Choose Discount Type') }}</option>

                                    <option value="amount">{{ translate('Flat') }}</option>

                                    <option value="percent">{{ translate('Percent') }}</option>

                                </select>

                            </div>

                        </div>

                    </div>

 

                    <div class="card rounded-15p" style="display: none;">

                        <div class="card-header">

                            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>

                        </div>

                        <div class="card-body">

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>

                                <div class="col-md-8">

                                    <input type="text" class="form-control" name="meta_title"

                                        placeholder="{{ translate('Meta Title') }}">

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>

                                <div class="col-md-8">

                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-form-label"

                                    for="signinSrEmail">{{ translate('Meta Image') }}</label>

                                <div class="col-md-8">

                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                        <div class="input-group-prepend">

                                            <div class="input-group-text bg-soft-secondary font-weight-medium">

                                                {{ translate('Browse') }}</div>

                                        </div>

                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                        <input type="hidden" name="meta_img" class="selected-files">

                                    </div>

                                    <div class="file-preview box sm">

                                    </div>

                                </div>

                            </div>

                    </div>





                    <!-- <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>

                        </div>

                        <div class="card-body">

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>

                                <div class="col-md-8">

                                    <input type="text" class="form-control" name="meta_title"

                                        placeholder="{{ translate('Meta Title') }}">

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>

                                <div class="col-md-8">

                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>

                                </div>

                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 col-form-label"

                                    for="signinSrEmail">{{ translate('Meta Image') }}</label>

                                <div class="col-md-8">

                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                        <div class="input-group-prepend">

                                            <div class="input-group-text bg-soft-secondary font-weight-medium">

                                                {{ translate('Browse') }}</div>

                                        </div>

                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                        <input type="hidden" name="meta_img" class="selected-files">

                                    </div>

                                    <div class="file-preview box sm">

                                    </div>

                                </div>

                            </div>

                            

                        </div>

                    </div> -->



                    <!-- <div class="card rounded-15p">

                        <div class="card-header">

                            <h5 class="mb-0 h6">{{ translate('Estimate Shipping Time') }}</h5>

                        </div>

                        <div class="card-body">

                            <div class="form-group mb-3">

                                <label for="name"></label>

                                    {{ translate('Shipping Days') }}

                                </label>

                                <div class="input-group">

                                    <input type="number" class="form-control" name="est_shipping_days" min="1"

                                        step="1" placeholder="{{ translate('Shipping Days') }}">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text"

                                            id="inputGroupPrepend">{{ translate('Days') }}</span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div> -->



                    

                    <!-- by kquiroz -->

                    

                    <!-- by kquirpz -->



                </div>

                <div class="col-12">

                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">

                        <div class="btn-group mr-2" role="group" aria-label="Third group">

                            <button type="submit" name="button" value="unpublish"

                                class="btn btn-primary action-btn">Guardar Borrador</button>

                        </div>

                        <div class="btn-group" role="group" aria-label="Second group">

                            <button type="submit" name="button" value="publish"

                                class="btn btn-success action-btn">{{ translate('Save & Publish') }}</button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>



@endsection



@section('script')

    <script type="text/javascript">

        $('form').bind('submit', function(e) {

            if ($(".action-btn").attr('attempted') == 'true') {

                //stop submitting the form because we have already clicked submit.

                e.preventDefault();

            } else {

                $(".action-btn").attr("attempted", 'true');

            }

            // Disable the submit button while evaluating if the form should be submitted

            // $("button[type='submit']").prop('disabled', true);



            // var valid = true;



            // if (!valid) {

            // e.preventDefault();



            ////Reactivate the button if the form was not submitted

            // $("button[type='submit']").button.prop('disabled', false);

            // }

        });



        $("[name=shipping_type]").on("change", function() {

            $(".flat_rate_shipping_div").hide();



            if ($(this).val() == 'flat_rate') {

                $(".flat_rate_shipping_div").show();

            }



        });



        function add_more_customer_choice_option(i, name) {

            $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                type: "POST",

                url: '{{ route('products.add-more-choice-option') }}',

                data: {

                    attribute_id: i

                },

                success: function(data) {

                    var obj = JSON.parse(data);

                    $('#customer_choice_options').append(

                        '\

                                                                                        <div class="form-group row">\

                                                                                            <div class="col-md-3">\

                                                                                                <input type="hidden" name="choice_no[]" value="' +

                        i +

                        '">\

                                                                                                <input type="text" class="form-control" name="choice[]" value="' +

                        name +

                        '" placeholder="{{ translate('Choice Title') }}" readonly>\

                                                                                            </div>\

                                                                                            <div class="col-md-8">\

                                                                                                <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' +

                        i + '[]" multiple>\

                                                                                                    ' + obj + '\

                                                                                                </select>\

                                                                                            </div>\

                                                                                        </div>');

                    AIZ.plugins.bootstrapSelect('refresh');

                }

            });





        }



        function getBrandsByCategory(){





            var data =$('#category_id').find(":selected").val();



            if(data < 10){

                //get brand by category id



            $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                type: "POST",

                url: '{{ route('products.add-brands') }}',

                data: {

                    parent_id: data

                },

                success: function(data) {

                    var obj = JSON.parse(data);

                    $('#brand_id').html(obj);

                    AIZ.plugins.bootstrapSelect('refresh');

                }

            });



                  



            }else{

                data='';



            }







            

       

            

        }



        $('input[name="colors_active"]').on('change', function() {

            if (!$('input[name="colors_active"]').is(':checked')) {

                $('#colors').prop('disabled', true);

                AIZ.plugins.bootstrapSelect('refresh');

            } else {

                $('#colors').prop('disabled', false);

                AIZ.plugins.bootstrapSelect('refresh');

            }

            update_sku();

        });



        $(document).on("change", ".attribute_choice", function() {

            update_sku();

        });



        $('#colors').on('change', function() {

            update_sku();

        });



        $('input[name="unit_price"]').on('keyup', function() {

            update_sku();

        });



        $('input[name="name"]').on('keyup', function() {

            update_sku();

        });



        function delete_row(em) {

            $(em).closest('.form-group row').remove();

            update_sku();

        }



        function delete_variant(em) {

            $(em).closest('.variant').remove();

        }



        function update_sku() {

            $.ajax({

                type: "POST",

                url: '{{ route('products.sku_combination') }}',

                data: $('#choice_form').serialize(),

                success: function(data) {

                    $('#sku_combination').html(data);

                    AIZ.uploader.previewGenerate();

                    AIZ.plugins.fooTable();

                    if (data.length > 1) {

                        $('#show-hide-div').hide();

                    } else {

                        $('#show-hide-div').show();

                    }

                }

            });

        }



        $('#choice_attributes').on('change', function() {

            $('#customer_choice_options').html(null);

            $.each($("#choice_attributes option:selected"), function() {

                add_more_customer_choice_option($(this).val(), $(this).text());

            });



            update_sku();

        });

    </script>



    <!-- Modal GUia Tamanos -->

<script>

// Get the modal

var modal = document.getElementById("myModal");



// Get the image and insert it inside the modal - use its "alt" text as a caption

var img = document.getElementById("myImg");

var modalImg = document.getElementById("img01");

var captionText = document.getElementById("caption");

img.onclick = function(){

  modal.style.display = "block";

  modalImg.src = '/public/assets/img/sizes.png';

  

}





function closeModal() {

   var modal = document.getElementById('myModal');

   modal.style.display = "none";

}



    window.onclick = function(event) {

        var modal = document.getElementById('myModal');

        if (event.target == modal) {

            modal.style.display = "none";

        }

    }





</script>

<script>

    document.querySelector('input[name="unit_price"]').addEventListener('input', function(e) {

    let itbis_price = e.target.value * 1.18;

    document.querySelector('input[name="itbis_price"]').value = itbis_price.toLocaleString('en-US', { style: 'currency', currency: 'USD' });

 });



</script>



@endsection

