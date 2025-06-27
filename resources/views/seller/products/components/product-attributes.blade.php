<div class="card rounded-15p">
    <div class="card-header">
        <h5 class="mb-0 h6">Configuración en Pagina Principal</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-6 col-from-label">¿Publicar en Destacados?</label>
            <div class="col-md-6">
                <label class="aiz-switch aiz-switch-success mb-0">
                    <input type="checkbox" name="featured" value="1">
                    <span></span>
                </label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-6 col-from-label">¿Publicar en Oferta de Hoy?</label>
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
        <h5 class="mb-0 h6 d-block w-100">Descuentos Automáticos</h5>
        <small class="text-muted d-block w-100">
            Estos descuentos se aplican en los ragos de las fechas seleccionadas.
        </small>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-3 control-label" for="start_date">Rango de Fechas</label>
            <div class="col-sm-8">
                <input type="text"
                       class="form-control aiz-date-range"
                       name="date_range"
                       placeholder="Fecha Inicio y Final"
                       data-time-picker="true"
                       data-format="DD-MM-Y HH:mm:ss"
                       data-separator=" to "
                       autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-from-label">
                {{ translate('Discount') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-md-4">
                <input type="number" placeholder="{{ translate('Discount') }}" name="discount" class="form-control" value="0" required>
            </div>
            <div class="col-md-4">
                <select class="form-control aiz-selectpicker" name="discount_type">
                    <option value="amount">{{ translate('Flat') }}</option>
                    <option value="percent" selected>{{ translate('Percent') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>

