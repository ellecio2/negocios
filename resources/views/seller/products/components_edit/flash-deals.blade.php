@php use App\Models\FlashDeal; @endphp
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
                @foreach (FlashDeal::where('status', 1)->get() as $flash_deal)
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
            {{--<input type="number" name="flash_discount" value="{{$product->flash_discount}}" class="form-control">--}}
            <input type="number" placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                   value="{{ $product->discount }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="name">
                {{ translate('Discount Type') }}
            </label>
            {{--<select class="form-control aiz-selectpicker" name="flash_discount_type"
                    id="flash_discount_type">
                <option value="">{{ translate('Choose Discount Type') }}</option>
                <option value="amount">{{ translate('Flat') }}</option>
                <option value="percent">{{ translate('Percent') }}</option>
            </select>--}}
            <select class="form-control aiz-selectpicker" name="discount_type">
                {{--<option value="amount">{{ translate('Flat') }}</option>
                <option value="percent" selected>{{ translate('Percent') }}</option>--}}

                <option
                    value="amount" <?php if ($product->video_provider == 'amount') echo "selected"; ?>>
                    {{ translate('Flat') }}</option>
                <option
                    value="percent"<?php if ($product->video_provider == 'percent') echo "selected"; ?>>
                    {{ translate('Percent') }}</option>
            </select>
        </div>
    </div>
    <div class="card" style="display: none;">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-3 col-from-label">{{translate('Meta Title')}}</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" name="meta_title"
                           value="{{ $product->meta_title }}"
                           placeholder="{{translate('Meta Title')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                <div class="col-lg-8">
                                <textarea name="meta_description" rows="8"
                                          class="form-control">{{ $product->meta_description }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label"
                       for="signinSrEmail">{{translate('Meta Images')}}</label>
                <div class="col-md-8">
                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                         data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                {{ translate('Browse') }}
                            </div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="meta_img" value="{{ $product->meta_img }}"
                               class="selected-files">
                    </div>
                    <div class="file-preview box sm"></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">{{translate('Slug')}}</label>
                <div class="col-lg-8">
                    <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug"
                           value="{{ $product->slug }}" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>
