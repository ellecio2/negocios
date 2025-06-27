<div class="card rounded-15p">
    <div class="card-header">
        <h5 class="mb-0 h6">Precio del Producto</h5>
    </div>
    <div class="card-body">
        {{-- Unit Price Field --}}
        <div class="form-group row">
            <label class="col-md-6 col-from-label">
                {{ translate('Unit price') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-md-6">
                <input type="number" value="{{ old('unit_price', $product->unit_price) }}" placeholder="0.00"
                       name="unit_price"
                       class="form-control">
            </div>
        </div>
        {{-- Unit Price Field --}}

        <!-- DETALLE DEL PRECIO FINAL -->
        @if (addon_is_activated('club_point'))
            <div class="form-group row">
                <label class="col-md-6 col-from-label">
                    La Pieza Puntos
                </label>
                <div class="col-md-6">
                    <input type="number" value="{{ old('earn_point', $product->earn_point) }}" placeholder="1"
                           name="earn_point"
                           class="form-control">
                </div>
            </div>
        @endif
        <div id="show-hide-div">
            {{-- Minimum Purchase Qty Field --}}
            <div class="form-group row">
                <label class="col-md-6 col-from-label">
                    {{ translate('Minimum Purchase Qty') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-md-6">
                    <input type="number" class="form-control" name="min_qty"
                           value="{{ old('min_qty', $product->min_qty ) }}" min="1"
                           required>
                </div>
            </div>
            {{-- Minimum Purchase Qty Field --}}
        </div>

        {{-- Seller Tax Field --}}
        <div class="form-group row">
            <label for="name" class="col-md-6 col-from-label">
                Comisión de Taller
                <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                        title="Si tu producto es vendido por un agente externo, esta comisión se restara de tu precio unitario. Coloca una comision llamativa para insentivar tus ventas">
                    !
                </button>
            </label>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="number" value="{{ old('seller_commission', $product->taxes()->first()->tax ) }}"
                           name="seller_commission" class="form-control"
                           id="re_seller_commission" required>
                    <div class="input-group-append">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Seller Tax Field --}}
        <div class="form-group row">
            <h1 class="col-12 bg-primary text-white fs-15 d-flex justify-content-between align-items-center"
                style="height: 35px; border-radius: 5px;">
                <span class="col-6">Tienda en Linea</span>
                <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                        title="Este es el detalle de tu producto en la tienda principal de LaPieza.DO">
                    !
                </button>
            </h1>
        </div>
        {{-- Seller Tax Field --}}
        <div class="form-group row">

            <div class="col-9">Precio Unitario:</div>
            <div class="col-3 text-right" id="store_unit_price">RD$ 0.00</div>
            <br>
            <div class="col-9">Comisión de La Pieza.DO <b>(<span id="store_comission_percent"></span>):</b></div>
            <div class="col-3 text-right text-danger" id="store_vendor_commission">- RD$ 0.00</div>

        </div>
        <div class="form-group row">

            <label class="col-6 col-from-label d-flex align-items-center font-weight-bold">
                Precio en Tienda
            </label>
            <div class="col-6">
                <input type="text" value="{{$product->itbis_price}}" name="itbis_price"
                       class="total-price form-control text-right"
                       id="store_total_price" readonly>
            </div>
            <label class="col-6 col-from-label d-flex align-items-center font-weight-bold">
                Ganancia
            </label>
            <div class="col-6">
                <input type="text" value="RD$ 0.00" class="recieve-income form-control text-right"
                       id="store_revenue" readonly>
            </div>
        </div>

        <div class="form-group row">

            <h1 class="col-12 bg-primary text-white fs-15 px-3 py-1 d-flex justify-content-between align-items-center"
                style="height: 35px; border-radius: 5px;">
                <span class="col-7">Precios del Taller</span>
                <button class="btn-info-warning" data-toggle="tooltip" data-placement="bottom"
                        title="Contamos con multiples asociados que podrán ofrecer tus productos, el precio se verá modificado de acuerdo a la comisión que les quieras proporcionar y esta se verá reducida de tu ganancia final.">
                    !
                </button>
            </h1>
        </div>


        {{-- Seller Tax Field --}}
        <div class="form-group row">
            <div class="col-9" style="display: none">Precio unitario</div>
            <div class="col-3 text-right" style="display: none" id="seller_unit_price">RD$ 0.00</div>
            <div class="col-9">Comisión de Taller(<span id="seller_commission_percent"></span>)</div>
            <div class="col-3 text-right text-success" id="seller_commission">+ RD$ 0.00</div>
            <div class="col-9" style="display: none">Comisión de LaPieza.Do(<span
                    id="seller_vendor_comission_percent"></span>)
            </div>
            <div class="col-3 text-right text-success" id="seller_vendor_commission" style="display: none">+ RD$ 0.00
            </div>
        </div>

        <div class="form-group row">
            <label class="col-6 col-from-label d-flex align-items-center font-weight-bold">
                Precio Max venta
            </label>
            <div class="col-6">
                <input type="text" value="RD$ 0.00" name="itbis_price" class="total-price form-control text-right"
                       id="seller_total_price" readonly>
            </div>
        </div>
        <div class="row justify-content-between px-3">

        </div>
        <div class="form-group row" style="display: none">
            <label class="col-4 col-from-label d-flex align-items-center font-weight-bold">
                Ganancia final obtenida
            </label>
            <div class="col-8">
                <input type="text" value="RD$ 0.00" class="recieve-income form-control text-right"
                       id="seller_revenue" readonly>
            </div>
        </div>

    </div>
</div>
