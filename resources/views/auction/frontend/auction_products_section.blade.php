<section class="mb-2 mb-md-3 mt-2 mt-md-3">
    <div class="container">
        <!-- Top Section -->
        <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
            <!-- Title -->
            <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                <span class="">{{ translate('Auction Products') }}</span>
            </h3>
            <!-- Links -->
            <div class="d-flex">
                <a class="text-negro fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary" href="{{ route('auction_products.all') }}">{{ translate('View All Products') }}</a>
            </div>
        </div>
        <!-- Products Section -->
        <div class="row gutters-16">
            <div class="col-xl-4 col-lg-6 mb-3 mb-lg-0">
                <div class="h-100 w-100 overflow-hidden" style="border-radius: 15px;">
                    <a href="{{ route('auction_products.all') }}" class="hov-scale-img">
                        <img class="img-fit lazyload mx-auto h-400px h-lg-485px has-transition"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset(get_setting('auction_banner_image')) }}"
                            alt="{{ env('APP_NAME') }} promo"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                </div>
            </div>
            @php
                $products = \App\Models\Product::latest()->where('published', 1)->where('auction_product', 1);
                if(get_setting('seller_auction_product') == 0){
                    $products = $products->where('added_by','admin');
                }
                $products = $products->where('auction_start_date','<=', strtotime("now"))->where('auction_end_date','>=', strtotime("now"))->limit(6)->get();
            @endphp
            <div class="col-xl-8 col-lg-6">
                <div class="aiz-carousel arrow-x-0 border-right arrow-inactive-none" data-items="2" data-xxl-items="2" data-xl-items="2" data-lg-items="1" data-md-items="2" data-sm-items="1" data-xs-items="1"  data-arrows="true" data-dots="false">
                    @php
                        $init = 0 ;
                        $end = 2 ;
                    @endphp
                    @for ($i = 0; $i < 2; $i++)
                        <div class="br-best-selling carousel-box border-top border-left">
                            @foreach ($products as $key => $product)
                                @if ($key >= $init && $key <= $end)
                                    <div class="position-relative border-bottom @if($i==1) border-right @endif has-transition hov-animate-outline-lapieza">
                                        <div class="row hov-scale-img">
                                            <div class="col-5">
                                                <a href="{{ route('auction-product', $product->slug) }}" class="d-block overflow-hidden h-100px h-sm-120px h-md-140px text-center p-2">
                                                    <img class="img-fluid h-100 lazyload mx-auto has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        alt="{{  $product->getTranslation('name')  }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                            <div class="col p-2 ">
                                                <h3 class="fw-400 fs-14 text-truncate-2 lh-1-4 mb-0 h-35px mb-3 d-none d-md-block">
                                                    <a href="{{ route('auction-product', $product->slug) }}" class="d-block text-reset hov-text-primary">{{  $product->getTranslation('name')  }}</a>
                                                </h3>
                                                <div class="fs-14">
                                                    <span class="text-secondary">{{ translate('Starting Bid') }}</span><br>
                                                    <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
                                                </div>
                                                @php 
                                                    $highest_bid = $product->bids->max('amount');
                                                    $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $product->starting_bid; 
                                                @endphp
                                                <button class="btn btn-primary btn-sm rounded-15px mt-3" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})">{{ translate('Place Bid') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        
                            @php
                                $init += 3;
                                $end += 3;
                            @endphp
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    // Country Code
    var isPhoneShown = true,
        countryData = window.intlTelInputGlobals.getCountryData(),
        input = document.querySelector("#phone-code");

    for (var i = 0; i < countryData.length; i++) {
        var country = countryData[i];
        if (country.iso2 == 'bd') {
            country.dialCode = '88';
        }
    }

    var iti = intlTelInput(input, {
        separateDialCode: true,
        utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
        onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            if (selectedCountryData.iso2 == 'bd') {
                return "01xxxxxxxxx";
            }
            return selectedCountryPlaceholder;
        }
    });

    var country = iti.getSelectedCountryData();
    $('input[name=country_code]').val(country.dialCode);

    input.addEventListener("countrychange", function(e) {
        // var currentMask = e.currentTarget.placeholder;

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

    });

    function toggleEmailPhone(el) {
        if (isPhoneShown) {
            $('.phone-form-group').addClass('d-none');
            $('.email-form-group').removeClass('d-none');
            $('input[name=phone]').val(null);
            isPhoneShown = false;
            $(el).html('{{ translate('Use Phone Instead') }}');
        } else {
            $('.phone-form-group').removeClass('d-none');
            $('.email-form-group').addClass('d-none');
            $('input[name=email]').val(null);
            isPhoneShown = true;
            $(el).html('{{ translate('Use Email Instead') }}');
        }
    }
</script>