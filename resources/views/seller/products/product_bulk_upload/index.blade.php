@extends('seller.layouts.app')
@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="h3">{{ translate('Bulk Products Upload') }}</h2>
                <h1 class="h3">Siga las Instrucciones Paso a Paso, para el llenado de Productos Másivos.</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table aiz-table mb-0" style="font-size:14px; background-color: #cce5ff; border-color: #b8daff">
                <tr>
                    <td><b>1ro.</b> Descarga el Archivo <b>"carga_masiva_productos_lapiezado.xlsm"</b> en tu computador, haciendo clic en el botón <b>"Descargar Archivo"</b>.</td>
                </tr>
                <tr>
                    <td><b>2do.</b> Al abrir el archivo, el primer paso es llenar la columna <b>"OEM"</b>, donde te permitirá llenar <b>1,000 (mil)</b> productos por archivo. Luego de introducir tus <b>"OEM"</b>, da clic al botón <b>"Buscar"</b>, y automáticamente se llenarán los campos de <b>"nombre"</b>, <b>"imagen"</b>, <b>"descripción"</b> y <b>"etiquetas"</b>, (Puede tardar unos segundo o minutos en cargar los datos, asi que no lo cierres ni interrumpas ese proceso.), estos detalles los puedes editar en el mismo archivo, luego de que la busqueda finalice, en caso de que alguno de estos datos no sea correcto o falte algún detalle en ellos, luego solo deberás llenar los datos siguientes: <b>"marca"</b>, <b>"categoría"</b>, <b>"subcategorías"</b>, y <b>"opciones"</b>, estas columnas tienen incluído un listado que al seleccionar las opciones requeridas, cambiará a un número que corresponde con nuestra base de datos. Ya los campos adicionales son propios de su listado de productos.</td>
                </tr>
                <tr>
                    <td><b>3ro.</b> Luego de completado <b>"TODOS"</b>, los datos en la hoja de Excel, (*No se pueden dejar Espacios Vacíos), guarda el archivo como <b>".CSV (delimitado por comas)"</b> y ese archivo, súbelo a la plataforma, cargándolo donde dice: <b>"Subir archivo CSV"</b>, le das clic a <b>"BROWSE"</b>, buscas el archivo donde lo guardaste y lo seleccionas. Luego, le das clic al botón: <b>"SUBIR CSC"</b> y tus productos se van a cargar en tu panel de Productos.</td>
                    </td>
                </tr>
                <tr>
                    <td><b>4to.</b> Ya luego de que la carga de productos sea exitosa, debes ir a la sección <b>"Productos"</b> y verificar que tus productos esten correctamente subidos y que no falte ningun detalle en ellos, tambien puedes editarlos cuando necesites, en caso de querer agregar, cambiar o quitar alguna informacion del producto. Y Listo! Ya tienes tus productos cargados correctamente en <b><span style="color: #003b73;">La Pieza.<span style="color: #E63108;">DO</span></span></b></td>
                </tr>
            </table>
            <a href="{{ static_asset('download/carga_masiva_productos_lapiezado.xlsm') }}" download>
                <button class="btn btn-primary mt-4">Descargar Archivo</button>
            </a>
        </div>
    </div>
    <div class="card" style="display: none;">
        <div class="card-body">
            <table class="table aiz-table mb-0" style="font-size:14px;background-color: #cce5ff;border-color: #b8daff">
                <tr>
                    <td>{{ translate('1. Category and Brand should be in numerical id.')}}:</td>
                </tr>
                <tr>
                    <td>{{ translate('2. You can download the pdf to get Category and Brand id.')}}:</td>
                </tr>
            </table>
            <a href="{{ route('seller.pdf.download_category') }}">
                <button class="btn btn-primary mt-2">{{ translate('Download Category')}}</button>
            </a>
            <a href="{{ route('seller.pdf.download_brand') }}">
                <button class="btn btn-primary mt-2">{{ translate('Download Brand')}}</button>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Upload CSV File') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('seller.bulk_product_upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('CSV') }}</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <label class="custom-file-label">
                                <input type="file" name="bulk_file" class="custom-file-input" required>
                                <span class="custom-file-name">{{ translate('Choose File')}}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Upload CSV')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
