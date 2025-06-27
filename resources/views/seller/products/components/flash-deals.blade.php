<div class="card rounded-15p col-lg-12">
    <div class="card-header">
        <h5 class="mb-0 h6 d-block w-100">
            Configurar {{ translate('Flash Deal') }}
        </h5>
        <small class="text-muted d-block w-100">
            Estas ofertas activan las ventas Rápidas con descuento del producto.
        </small>
    </div>
    <div class="card-body">
        <div class="form-group mb-3">
            <label>{{ translate('Add To Flash') }}</label>
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
            <input type="number" name="flash_discount" value="0" class="form-control">
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
