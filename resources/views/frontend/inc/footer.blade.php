<!-- footer Description -->
@if (get_setting('footer_title') != null || get_setting('footer_description') != null)
    <section class="bg-light border-top border-bottom mt-auto">
        <div class="container py-4">
            <h1 class="fs-18 fw-700 text-gray-dark mb-3">
                {{ get_setting('footer_title', null, get_system_language()->code) }}</h1>
            <p class="fs-13 text-gray-dark text-justify mb-0">
                {!! nl2br(get_setting('footer_description', null, get_system_language()->code)) !!}
            </p>
        </div>
    </section>
@endif
<!-- footer top Bar -->
<section class="bg-light-pieza border-top mt-auto">
    <div class="container px-xs-0">
        <div class="row no-gutters border-left border-soft-light">
            <!-- Terms & conditions -->
            <div class="col-lg-3 col-6 policy-file">
                <a class="text-reset h-100  border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1"
                    href="https://soporte.lapieza.do/?q=terminos" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26.004" height="32" viewBox="0 0 26.004 32">
                        <path id="Union_8" data-name="Union 8"
                            d="M-14508,18932v-.01a6.01,6.01,0,0,1-5.975-5.492h-.021v-14h1v13.5h0a4.961,4.961,0,0,0,4.908,4.994h.091v0h14v1Zm17-4v-1a2,2,0,0,0,2-2h1a3,3,0,0,1-2.927,3Zm-16,0a3,3,0,0,1-3-3h1a2,2,0,0,0,2,2h16v1Zm18-3v-16.994h-4v-1h3.6l-5.6-5.6v3.6h-.01a2.01,2.01,0,0,0,2,2v1a3.009,3.009,0,0,1-3-3h.01v-4h.6l0,0H-14507a2,2,0,0,0-2,2v22h-1v-22a3,3,0,0,1,3-3v0h12l0,0,7,7-.01.01V18925Zm-16-4.992v-1h12v1Zm0-4.006v-1h12v1Zm0-4v-1h12v1Z"
                            transform="translate(14513.998 -18900.002)" fill="#919199" />
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Terms & conditions') }}</h4>
                </a>
            </div>
            <!-- Return Policy -->
            <div class="col-lg-2 col-6 policy-file">
                <a class="text-reset h-100  border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1"
                    href="https://soporte.lapieza.do/?q=devoluciones" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.001" height="23.971" viewBox="0 0 32.001 23.971">
                        <path id="Union_7" data-name="Union 7"
                            d="M-14490,18922.967a6.972,6.972,0,0,0,4.949-2.051,6.944,6.944,0,0,0,2.052-4.943,7.008,7.008,0,0,0-7-7v0h-22.1l7.295,7.295-.707.707-7.779-7.779-.708-.707.708-.7,7.774-7.779.712.707-7.261,7.258H-14490v0a8.01,8.01,0,0,1,8,8,8.008,8.008,0,0,1-8,8Z"
                            transform="translate(14514.001 -18900)" fill="#919199" />
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Return Policy') }}</h4>
                </a>
            </div>
            <!-- Privacy Policy -->
            <div class="col-lg-2 col-6 policy-file">
                <a class="text-reset h-100 border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1"
                    href="https://soporte.lapieza.do/?q=politicas-privacidad" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <g id="Group_24236" data-name="Group 24236" transform="translate(-1454.002 -2430.002)">
                            <path id="Subtraction_11" data-name="Subtraction 11"
                                d="M-14498,18932a15.894,15.894,0,0,1-11.312-4.687A15.909,15.909,0,0,1-14514,18916a15.884,15.884,0,0,1,4.685-11.309A15.9,15.9,0,0,1-14498,18900a15.909,15.909,0,0,1,11.316,4.688A15.885,15.885,0,0,1-14482,18916a15.9,15.9,0,0,1-4.687,11.316A15.909,15.909,0,0,1-14498,18932Zm0-31a14.9,14.9,0,0,0-10.605,4.393A14.9,14.9,0,0,0-14513,18916a14.9,14.9,0,0,0,4.395,10.607A14.9,14.9,0,0,0-14498,18931a14.9,14.9,0,0,0,10.607-4.393A14.9,14.9,0,0,0-14483,18916a14.9,14.9,0,0,0-4.393-10.607A14.9,14.9,0,0,0-14498,18901Z"
                                transform="translate(15968 -16470)" fill="#919199" />
                            <g id="Group_24196" data-name="Group 24196" transform="translate(0 -1)">
                                <rect id="Rectangle_18406" data-name="Rectangle 18406" width="2" height="10"
                                    transform="translate(1469 2440)" fill="#919199" />
                                <rect id="Rectangle_18407" data-name="Rectangle 18407" width="2" height="2"
                                    transform="translate(1469 2452)" fill="#919199" />
                            </g>
                        </g>
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Privacy Policy') }}</h4>
                </a>
            </div>
            <!-- Security Policy -->
            <div class="col-lg-2 col-6 policy-file">
                <a class="text-reset h-100 border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1"
                    href="https://soporte.lapieza.do/?q=politicas-seguridad" target="_blank">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <g id="Group_24236" data-name="Group 24236" transform="translate(-1454.002 -2430.002)">
                            <path id="Subtraction_11" data-name="Subtraction 11"
                                d="M-14498,18932a15.894,15.894,0,0,1-11.312-4.687A15.909,15.909,0,0,1-14514,18916a15.884,15.884,0,0,1,4.685-11.309A15.9,15.9,0,0,1-14498,18900a15.909,15.909,0,0,1,11.316,4.688A15.885,15.885,0,0,1-14482,18916a15.9,15.9,0,0,1-4.687,11.316A15.909,15.909,0,0,1-14498,18932Zm0-31a14.9,14.9,0,0,0-10.605,4.393A14.9,14.9,0,0,0-14513,18916a14.9,14.9,0,0,0,4.395,10.607A14.9,14.9,0,0,0-14498,18931a14.9,14.9,0,0,0,10.607-4.393A14.9,14.9,0,0,0-14483,18916a14.9,14.9,0,0,0-4.393-10.607A14.9,14.9,0,0,0-14498,18901Z"
                                transform="translate(15968 -16470)" fill="#919199" />
                            <g id="Group_24196" data-name="Group 24196" transform="translate(0 -1)">
                                <rect id="Rectangle_18406" data-name="Rectangle 18406" width="2" height="10"
                                    transform="translate(1469 2440)" fill="#919199" />
                                <rect id="Rectangle_18407" data-name="Rectangle 18407" width="2" height="2"
                                    transform="translate(1469 2452)" fill="#919199" />
                            </g>
                        </g>
                    </svg> -->
                    <svg fill="#000000" height="32" width="32" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 214.27 214.27" xml:space="preserve">
                            <g>
                                <path d="M196.926,55.171c-0.11-5.785-0.215-11.25-0.215-16.537c0-4.142-3.357-7.5-7.5-7.5c-32.075,0-56.496-9.218-76.852-29.01
                                    c-2.912-2.832-7.546-2.831-10.457,0c-20.354,19.792-44.771,29.01-76.844,29.01c-4.142,0-7.5,3.358-7.5,7.5
                                    c0,5.288-0.104,10.755-0.215,16.541c-1.028,53.836-2.436,127.567,87.331,158.682c0.796,0.276,1.626,0.414,2.456,0.414
                                    c0.83,0,1.661-0.138,2.456-0.414C199.36,182.741,197.954,109.008,196.926,55.171z M107.131,198.812
                                    c-76.987-27.967-75.823-89.232-74.79-143.351c0.062-3.248,0.122-6.396,0.164-9.482c30.04-1.268,54.062-10.371,74.626-28.285
                                    c20.566,17.914,44.592,27.018,74.634,28.285c0.042,3.085,0.102,6.231,0.164,9.477C182.961,109.577,184.124,170.844,107.131,198.812
                                    z" fill="#919199"/>
                                <path d="M132.958,81.082l-36.199,36.197l-15.447-15.447c-2.929-2.928-7.678-2.928-10.606,0c-2.929,2.93-2.929,7.678,0,10.607
                                    l20.75,20.75c1.464,1.464,3.384,2.196,5.303,2.196c1.919,0,3.839-0.732,5.303-2.196l41.501-41.5
                                    c2.93-2.929,2.93-7.678,0.001-10.606C140.636,78.154,135.887,78.153,132.958,81.082z" fill="#919199"/>
                            </g>
                            </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">Políticas de Seguridad</h4>
                </a>
            </div>
              <!-- Support Policy -->
              <div class="col-lg-3 col-6 policy-file">
                <a class="text-reset h-100  border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1"
                    href="https://soporte.lapieza.do" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.002" height="32.002" viewBox="0 0 32.002 32.002">
                        <g id="Group_24198" data-name="Group 24198" transform="translate(-1113.999 -2398)">
                            <path id="Subtraction_14" data-name="Subtraction 14"
                                d="M-14508,18916h0l-1,0a12.911,12.911,0,0,1,3.806-9.187A12.916,12.916,0,0,1-14496,18903a12.912,12.912,0,0,1,9.193,3.811A12.9,12.9,0,0,1-14483,18916l-1,0a11.918,11.918,0,0,0-3.516-8.484A11.919,11.919,0,0,0-14496,18904a11.921,11.921,0,0,0-8.486,3.516A11.913,11.913,0,0,0-14508,18916Z"
                                transform="translate(15626 -16505)" fill="#919199" />
                            <path id="Subtraction_15" data-name="Subtraction 15"
                                d="M-14510,18912h-1a3,3,0,0,1-3-3v-6a3,3,0,0,1,3-3h1a2,2,0,0,1,2,2v8A2,2,0,0,1-14510,18912Zm-1-11a2,2,0,0,0-2,2v6a2,2,0,0,0,2,2h1a1,1,0,0,0,1-1v-8a1,1,0,0,0-1-1Z"
                                transform="translate(15628 -16489)" fill="#919199" />
                            <path id="Subtraction_19" data-name="Subtraction 19"
                                d="M4,12H3A3,3,0,0,1,0,9V3A3,3,0,0,1,3,0H4A2,2,0,0,1,6,2v8A2,2,0,0,1,4,12ZM3,1A2,2,0,0,0,1,3V9a2,2,0,0,0,2,2H4a1,1,0,0,0,1-1V2A1,1,0,0,0,4,1Z"
                                transform="translate(1146.002 2423) rotate(180)" fill="#919199" />
                            <path id="Subtraction_17" data-name="Subtraction 17"
                                d="M-14512,18908a2,2,0,0,1-2-2v-4a2,2,0,0,1,2-2,2,2,0,0,1,2,2v4A2,2,0,0,1-14512,18908Zm0-7a1,1,0,0,0-1,1v4a1,1,0,0,0,1,1,1,1,0,0,0,1-1v-4A1,1,0,0,0-14512,18901Z"
                                transform="translate(20034 16940.002) rotate(90)" fill="#919199" />
                            <rect id="Rectangle_18418" data-name="Rectangle 18418" width="1" height="4.001"
                                transform="translate(1137.502 2427.502) rotate(90)" fill="#919199" />
                            <path id="Intersection_1" data-name="Intersection 1"
                                d="M-14508.5,18910a4.508,4.508,0,0,0,4.5-4.5h1a5.508,5.508,0,0,1-5.5,5.5Z"
                                transform="translate(15646.004 -16482.5)" fill="#919199" />
                        </g>
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">Centro de Soporte</h4>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- footer subscription & icons -->
<section class="py-3 text-light footer-widget "
    style="background-color: #003b73 !important;">
    <div class="container">
        <!-- footer logo -->
        <div class="row">
            <div class="mt-3 mb-4 col-xl-3 col-lg-2">
                <a href="{{ route('home') }}" class="d-block centerthis">
                    @if (get_setting('footer_logo') != null)
                        <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}"
                            height="100" style="height: 100px;">
                    @else
                        <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                            height="100" style="height: 100px;">
                    @endif
                </a>
                     <!-- Contacts -->
                <div class="text-center text-sm-left mt-4">
                    <!-- <h4 class="fs-14 text-secondary text-uppercase fw-700 mb-3">{{ translate('Contacts') }}</h4> -->
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <p class="fs-13 text-secondary mb-1">{{ translate('Address') }}</p>
                            <p class="fs-13 text-white">
                                {{ get_setting('contact_address', null, App::getLocale()) }}</p>
                        </li>
                        <li class="mb-2">
                            <p class="fs-13 text-secondary mb-1">{{ translate('Phone') }}</p>
                            <p class="fs-13 text-white">{{ get_setting('contact_phone') }}</p>
                        </li>
                        <li class="mb-2">
                            <p class="fs-13 text-secondary mb-1">{{ translate('Email') }}</p>
                            <p class="">
                                <a href="mailto:{{ get_setting('contact_email') }}"
                                    class="fs-13 text-white hov-text-primary">{{ get_setting('contact_email') }}</a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-3 mb-4 col-xl-1 col-lg-1"></div>
            <div class="mt-3 mb-4 col-xl-4 col-lg-4">
                <div class="mb-4 text-secondary text-justify">
                    {!! get_setting('about_us_description', null, App::getLocale()) !!}
                </div>
                <h5 class="fs-14 fw-700 text-soft-light mt-1 mb-3">
                    {{ translate('Subscribe to our newsletter for regular updates about Offers, Coupons & more') }}
                </h5>
                <div class="mb-3">
                    <form method="POST" action="{{ route('subscribers.store') }}">
                        @csrf
                        <div class="row gutters-10">
                            <div class="col-8">
                                <input type="email"
                                    class="form-control border-secondary rounded-15px text-white w-100 bg-transparent"
                                    placeholder="{{ translate('Your Email Address') }}" name="email" required>
                            </div>
                            <div class="col-4">
                                <button type="submit"
                                    class="btn btn-light rounded-25px w-100">{{ translate('Subscribe') }}</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <br>
                    <br>
                     <!-- Social -->
                @if (get_setting('show_social_links'))
                    <h5 class="fs-14 fw-700 text-white text-uppercase mt-3 mt-lg-0">{{ translate('Follow Us') }}
                    </h5>
                    <br>
                    <ul class="list-inline social colored mb-4">
                        @if (!empty(get_setting('facebook_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i
                                        class="lab la-facebook-f"></i></a>
                            </li>
                        @endif
                        @if (!empty(get_setting('twitter_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('twitter_link') }}" target="_blank" class="twitter"><i
                                        class="lab la-twitter"></i></a>
                            </li>
                        @endif
                        @if (!empty(get_setting('instagram_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i
                                        class="lab la-instagram"></i></a>
                            </li>
                        @endif
                        @if (!empty(get_setting('youtube_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i
                                        class="lab la-youtube"></i></a>
                            </li>
                        @endif
                        @if (!empty(get_setting('linkedin_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin"><i
                                        class="lab la-linkedin-in"></i></a>
                            </li>
                        @endif
                    </ul>
                @endif
                </div>
            </div>
            <div class="mt-3 mb-4 col-xl-1 col-lg-1"></div>
            <div class="mt-3 mb-4 col-xl-3 col-lg-3">
                <!-- Apps link -->
                @if (get_setting('play_store_link') != null || get_setting('app_store_link') != null)
                    <h5 class="fs-14 fw-700 text-white text-uppercase mt-3">{{ translate('Mobile Apps') }}</h5>
                    <div class="d-flex mt-3" style="justify-content: space-between; align-items: start;">
                        <div class="">
                            <a href="{{ get_setting('play_store_link') }}" target="_blank"
                                class="mr-2 mb-2 overflow-hidden hov-scale-img">
                                <img class="lazyload has-transition" style="height: 3.3rem;"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ static_asset('assets/img/play.png') }}" alt="{{ env('APP_NAME') }}"
                                    height="44">
                            </a>
                        </div>
                        <div class="">
                            <a href="{{ get_setting('app_store_link') }}" target="_blank"
                                class="overflow-hidden hov-scale-img">
                                <img class="lazyload has-transition" style="height: 3.3rem;"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ static_asset('assets/img/app.png') }}" alt="{{ env('APP_NAME') }}"
                                    height="44">
                            </a>
                        </div>
                    </div>
                @endif
                @if (get_setting('play_store_link') != null || get_setting('app_store_link') != null)
                    <div class="d-flex mt-3" style="justify-content: space-between; align-items: start;">
                        <div class="">
                            <a href="#" target="_blank" class="mr-2 mb-2 overflow-hidden hov-scale-img" >
                                <img class="lazyload has-transition br-banner " style="height: 3.5rem;" src="{{ static_asset('assets/img/ssl.png') }}"
                                    alt="{{ env('APP_NAME') }}" height="44">
                            </a>
                        </div>
                        <div class="">
                            <a href="#" target="_blank" class="overflow-hidden hov-scale-img " >
                                <img class="lazyload has-transition br-banner round15" style="height: 3.5rem;" src="{{ static_asset('assets/img/pci.png') }}"
                                    alt="{{ env('APP_NAME') }}" height="44">
                            </a>
                        </div>
                    </div>
                @endif
                <div class="d-flex mt-3">
                <div class="text-center text-lg-center">
                    <ul class="list-inline mb-0">
                        {{-- @if (get_setting('payment_method_images') != null)
                            @foreach (explode(',', get_setting('payment_method_images')) as $key => $value) --}}
                        <li class="list-inline-item mr-3">
                            <img src="{{ static_asset('assets/img/cards.png') }}" class="img-size2 overflow-hidden hov-scale-img rounded-15px"
                                alt="">
                        </li>
                        {{-- @endforeach
                        @endif --}}
                    </ul>
                </div>
                        </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col d-none d-lg-block"></div>
            <div class="col-xl-3 col-lg-3">
            </div>
        </div> -->
    </div>
</section>
@php
    $col_values = get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy') ? 'col-lg-3 col-md-6 col-sm-6' : 'col-md-4 col-sm-6';
@endphp
<section class="py-lg-3 text-light footer-widget border-bottom" style="background-color: #002549 !important;">
    <!-- footer widgets ========== [Accordion Fotter widgets are bellow from this]-->
    <div class="container d-none d-lg-block">
        <div class="row">
            <!-- Quick links -->
            <div class="text-center text-sm-left mt-4">
            </div>
            <!-- My Account -->
            <div class="{{ $col_values }}">
                <div class="text-center text-sm-left mt-4">
                    <h4 class="fs-14 text-secondary text-uppercase fw-700 mb-3">{{ translate('My Account') }}</h4>
                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li class="mb-2">
                                <a class="fs-13 text-soft-light animate-underline-white"
                                    href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li class="mb-2">
                                <a class="fs-13 text-soft-light animate-underline-white"
                                    href="{{ route('user.login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endif
                        <li class="mb-2">
                            <a class="fs-13 text-soft-light animate-underline-white"
                                href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="fs-13 text-soft-light animate-underline-white"
                                href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="fs-13 text-soft-light animate-underline-white"
                                href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>
                        @if (addon_is_activated('affiliate_system'))
                            <li class="mb-2">
                                <a class="fs-13 text-soft-light animate-underline-white"
                                    href="{{ route('affiliate.apply') }}">
                                    {{ translate('Be an affiliate partner') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- Seller & Delivery Boy -->
            @if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="text-center text-sm-left mt-4">
                        <!-- Seller -->
                        @if (get_setting('vendor_system_activation') == 1)
                            <h4 class="fs-14 text-secondary text-uppercase fw-700 mb-3">{{ translate('Seller Zone') }}
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <p class="fs-13 text-soft-light mb-0">
                                        {{ translate('Become A Seller') }}
                                        {{-- <a href="{{ route('shops.create') }}" class="fs-13 fw-700 text-warning ml-2">{{ translate('Apply Now') }}</a> --}}
                                        <a href="{{ route('register') }}"
                                            class="fs-13 fw-700 text-warning ml-2">{{ translate('Apply Now') }}</a>
                                    </p>
                                </li>
                                @guest
                                    <li class="mb-2">
                                        <a class="fs-13 text-soft-light animate-underline-white"
                                            href="{{ route('seller.login') }}">
                                            {{ translate('Login to Seller Panel') }}
                                        </a>
                                    </li>
                                @endguest
                                @if (get_setting('seller_app_link'))
                                    <li class="mb-2">
                                        <a class="fs-13 text-soft-light animate-underline-white" target="_blank"
                                            href="{{ get_setting('seller_app_link') }}">
                                            {{ translate('Download Seller App') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            @endif
<!-- by kquiroz -->
@if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="text-center text-sm-left mt-4">
                      <!-- Delivery Boy -->
                        @if (addon_is_activated('delivery_boy'))
                            <h4 class="fs-14 text-secondary text-uppercase fw-700 mt-4 mb-3">
                                {{ translate('Delivery Boy') }}</h4>
                            <ul class="list-unstyled">
                                @guest
                                    <li class="mb-2">
                                        <a class="fs-13 text-soft-light animate-underline-white"
                                            href="{{ route('deliveryboy.login') }}">
                                            {{ translate('Login to Delivery Boy Panel') }}
                                        </a>
                                    </li>
                                @endguest
                                @if (get_setting('delivery_boy_app_link'))
                                    <li class="mb-2">
                                        <a class="fs-13 text-soft-light animate-underline-white" target="_blank"
                                            href="{{ get_setting('delivery_boy_app_link') }}">
                                            {{ translate('Download Delivery Boy App') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            @endif
<!-- by kquiroz -->
            <div class="{{ $col_values }}">
                <div class="text-center text-sm-left mt-4 ">
                    <a class="help-button" href="https://soporte.lapieza.do" target="_blank">
                <img src="{{ static_asset('assets/img/banner_help.png') }}" height="251" class="mw-100 h-auto rounded-15px"
                                style="max-height: 251px;" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Accordion Fotter widgets -->
    <div class="d-lg-none bg-transparent">
        <!-- Quick links -->
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button
                    class="aiz-accordion fs-14 text-white bg-transparent">Ayuda y Soporte</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #212129 !important;">
                <div class="container">
                <a class="help-button" href="https://soporte.lapieza.do" target="_blank">
                <img src="{{ static_asset('assets/img/banner_help.png') }}" height="251" class="mw-100 h-auto rounded-15px"
                                style="max-height: 251px;" alt=""></a>
                </div>
            </div>
        </div>
        <!-- Contacts -->
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('Contacts') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #212129 !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <p class="fs-13 text-secondary mb-1">{{ translate('Address') }}</p>
                            <p class="fs-13 text-soft-light">
                                {{ get_setting('contact_address', null, App::getLocale()) }}</p>
                        </li>
                        <li class="mb-2">
                            <p class="fs-13 text-secondary mb-1">{{ translate('Phone') }}</p>
                            <p class="fs-13 text-soft-light">{{ get_setting('contact_phone') }}</p>
                        </li>
                        <li class="mb-2">
                            <p class="fs-13 text-secondary mb-1">{{ translate('Email') }}</p>
                            <p class="">
                                <a href="mailto:{{ get_setting('contact_email') }}"
                                    class="fs-13 text-soft-light hov-text-primary">{{ get_setting('contact_email') }}</a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- My Account -->
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('My Account') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #212129 !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        @auth
                            <li class="mb-2 pb-2">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                    href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['user.login'], ' active') }}">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                    href="{{ route('user.login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endauth
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['purchase_history.index'], ' active') }}">
                            <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['orders.track'], ' active') }}">
                            <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>
                        @if (addon_is_activated('affiliate_system'))
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['affiliate.apply'], ' active') }}">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                    href="{{ route('affiliate.apply') }}">
                                    {{ translate('Be an affiliate partner') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- Seller -->
        @if (get_setting('vendor_system_activation') == 1)
            <div class="aiz-accordion-wrap bg-black">
                <div class="aiz-accordion-heading container bg-black">
                    <button
                        class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('Seller Zone') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent" style="background-color: #212129 !important;">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            {{-- <li class="mb-2 pb-2 {{ areActiveRoutes(['shops.create'],' active')}}">
                            <p class="fs-13 text-soft-light text-sm-secondary mb-0">
                                {{ translate('Become A Seller') }}
                                <a href="{{ route('shops.create') }}" class="fs-13 fw-700 text-warning ml-2">{{ translate('Apply Now') }}</a>
                            </p>
                        </li> --}}
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['register'], ' active') }}">
                                <p class="fs-13 text-soft-light text-sm-secondary mb-0">
                                    {{ translate('Become A Seller') }}
                                    <a href="{{ route('register') }}"
                                        class="fs-13 fw-700 text-warning ml-2">{{ translate('Apply Now') }}</a>
                                </p>
                            </li>
                            @guest
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                    <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                        href="{{ route('seller.login') }}">
                                        {{ translate('Login to Seller Panel') }}
                                    </a>
                                </li>
                            @endguest
                            @if (get_setting('seller_app_link'))
                                <li class="mb-2 pb-2">
                                    <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                        target="_blank" href="{{ get_setting('seller_app_link') }}">
                                        {{ translate('Download Seller App') }}
                                    </a>
                                </li>
                            @endif
                            
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <!-- Delivery Boy -->
        @if (addon_is_activated('delivery_boy'))
            <div class="aiz-accordion-wrap bg-black">
                <div class="aiz-accordion-heading container bg-black">
                    <button
                        class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('Delivery Boy') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent" style="background-color: #212129 !important;">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            @guest
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                    <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                        href="{{ route('deliveryboy.login') }}">
                                        {{ translate('Login to Delivery Boy Panel') }}
                                    </a>
                                </li>
                            @endguest
                            @if (get_setting('delivery_boy_app_link'))
                                <li class="mb-2 pb-2">
                                    <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white"
                                        target="_blank" href="{{ get_setting('delivery_boy_app_link') }}">
                                        {{ translate('Download Delivery Boy App') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!-- FOOTER -->
<footer class="pt-3 pb-7 pb-xl-3 bg-black text-soft-light">
    <div class="container">
        <div class="row align-items-center py-3">
            <!-- Copyright -->
            <div class="col-lg-4 order-1 order-lg-0">
            </div>
            <!-- Payment Method Images -->
            <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="text-center text-lg-left fs-14" current-verison="{{ get_setting('current_version') }}">
                    <!-- {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!} -->
                    <span style="color: #fff; font-family: Tofino, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px;">© 2024 <b><span style="color: #43B0F1;">La Pieza.<span
                                        style="color: #E63108;">DO</span></span></b> by <b>GQ Labs S.R.L.</b></span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Mobile bottom nav -->
<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom border-top border-sm-bottom border-sm-left border-sm-right mx-auto mb-sm-2"
    style="background-color: #E63118 !important;">
    <div class="row align-items-center gutters-5">
        <!-- Home -->
        <div class="col">
            <a href="{{ route('home') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['home'], 'svg-active') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                        <path id="Path_2916" data-name="Path 2916"
                            d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                            transform="translate(-3495.144 602)" fill="#fff" />
                    </g>
                </svg>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-white {{ areActiveRoutes(['home'], 'text-white') }}">{{ translate('Home') }}</span>
            </a>
        </div>
        <!-- Categories -->
        <div class="col">
            <a href="{{ route('categories.all') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['categories.all'], 'svg-active') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_25497" data-name="Group 25497" transform="translate(3373.432 -602)">
                        <path id="Path_2917" data-name="Path 2917"
                            d="M126.713,0h-5V5a2,2,0,0,0,2,2h3a2,2,0,0,0,2-2V2a2,2,0,0,0-2-2m1,5a1,1,0,0,1-1,1h-3a1,1,0,0,1-1-1V1h4a1,1,0,0,1,1,1Z"
                            transform="translate(-3495.144 602)" fill="#fff" />
                        <path id="Path_2918" data-name="Path 2918"
                            d="M144.713,18h-3a2,2,0,0,0-2,2v3a2,2,0,0,0,2,2h5V20a2,2,0,0,0-2-2m1,6h-4a1,1,0,0,1-1-1V20a1,1,0,0,1,1-1h3a1,1,0,0,1,1,1Z"
                            transform="translate(-3504.144 593)" fill="#fff" />
                        <path id="Path_2919" data-name="Path 2919"
                            d="M143.213,0a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5"
                            transform="translate(-3504.144 602)" fill="#fff" />
                        <path id="Path_2920" data-name="Path 2920"
                            d="M125.213,18a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5"
                            transform="translate(-3495.144 593)" fill="#fff" />
                    </g>
                </svg>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-white {{ areActiveRoutes(['categories.all'], 'text-white') }}">{{ translate('Categories') }}</span>
            </a>
        </div>
        @php
            $cart = get_user_cart();
        @endphp
        <!-- Cart -->
        @php
            $count = isset($cart) && count($cart) ? count($cart) : 0;
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}"
                class="text-secondary d-block text-center pb-2 pt-3 px-3 {{ areActiveRoutes(['cart'], 'svg-active') }}">
                <span class="d-inline-block position-relative px-2">
                    <svg id="Group_25499" data-name="Group 25499" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="16.001" height="16"
                        viewBox="0 0 16.001 16">
                        <defs>
                            <clipPath id="clip-pathw">
                                <rect id="Rectangle_1383" data-name="Rectangle 1383" width="16" height="16"
                                    fill="#91919c" />
                            </clipPath>
                        </defs>
                        <g id="Group_8095" data-name="Group 8095" transform="translate(0 0)"
                            clip-path="url(#clip-pathw)">
                            <path id="Path_2926" data-name="Path 2926"
                                d="M8,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                transform="translate(-3 -11.999)" fill="#fff" />
                            <path id="Path_2927" data-name="Path 2927"
                                d="M24,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                transform="translate(-10.999 -11.999)" fill="#fff" />
                            <path id="Path_2928" data-name="Path 2928"
                                d="M15.923,3.975A1.5,1.5,0,0,0,14.5,2h-9a.5.5,0,1,0,0,1h9a.507.507,0,0,1,.129.017.5.5,0,0,1,.355.612l-1.581,6a.5.5,0,0,1-.483.372H5.456a.5.5,0,0,1-.489-.392L3.1,1.176A1.5,1.5,0,0,0,1.632,0H.5a.5.5,0,1,0,0,1H1.544a.5.5,0,0,1,.489.392L3.9,9.826A1.5,1.5,0,0,0,5.368,11h7.551a1.5,1.5,0,0,0,1.423-1.026Z"
                                transform="translate(0 -0.001)" fill="#fff" />
                        </g>
                    </svg>
                    @if ($count > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary badge-primary-green position-absolute absolute-top-right"
                            style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block mt-1 fs-10 fw-600 text-white {{ areActiveRoutes(['cart'], 'text-primary') }}">
                    {{ translate('Cart') }}
                    (<span class="cart-count">{{ $count }}</span>)
                </span>
            </a>
        </div>
        <!-- Notifications -->
        <div class="col">
            <a href="{{ route('all-notifications') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['all-notifications'], 'svg-active') }}">
                <span class="d-inline-block position-relative px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                        <path id="ecf3cc267cd87627e58c1954dc6fbcc2"
                            d="M5.488,14.056a.617.617,0,0,0-.8-.016.6.6,0,0,0-.082.855A2.847,2.847,0,0,0,6.835,16h0l.174-.007a2.846,2.846,0,0,0,2.048-1.1h0l.053-.073a.6.6,0,0,0-.134-.782.616.616,0,0,0-.862.081,1.647,1.647,0,0,1-.334.331,1.591,1.591,0,0,1-2.222-.331H5.55ZM6.828,0C4.372,0,1.618,1.732,1.306,4.512h0v1.45A3,3,0,0,1,.6,7.37a.535.535,0,0,0-.057.077A3.248,3.248,0,0,0,0,9.088H0l.021.148a3.312,3.312,0,0,0,.752,2.2,3.909,3.909,0,0,0,2.5,1.232,32.525,32.525,0,0,0,7.1,0,3.865,3.865,0,0,0,2.456-1.232A3.264,3.264,0,0,0,13.6,9.249h0v-.1a3.361,3.361,0,0,0-.582-1.682h0L12.96,7.4a3.067,3.067,0,0,1-.71-1.408h0V4.54l-.039-.081a.612.612,0,0,0-1.132.208h0v1.45a.363.363,0,0,0,0,.077,4.21,4.21,0,0,0,.979,1.957,2.022,2.022,0,0,1,.312,1h0v.155a2.059,2.059,0,0,1-.468,1.373,2.656,2.656,0,0,1-1.661.788,32.024,32.024,0,0,1-6.87,0,2.663,2.663,0,0,1-1.7-.824,2.037,2.037,0,0,1-.447-1.33h0V9.151a2.1,2.1,0,0,1,.305-1.007A4.212,4.212,0,0,0,2.569,6.187a.363.363,0,0,0,0-.077h0V4.653a4.157,4.157,0,0,1,4.2-3.442,4.608,4.608,0,0,1,2.257.584h0l.084.042A.615.615,0,0,0,9.649,1.8.6.6,0,0,0,9.624.739,5.8,5.8,0,0,0,6.828,0Z"
                            fill="#fff" />
                    </svg>
                    @if (Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-white {{ areActiveRoutes(['all-notifications'], 'text-primary') }}">{{ translate('Notifications') }}</span>
            </a>
        </div>
        <!-- Account -->
        <div class="col">
            @if (Auth::check())
                @if (isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if (Auth::user()->photo != null)
                                <img src="{{ custom_asset(Auth::user()->avatar_original) }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-white">{{ translate('My Account') }}</span>
                    </a>
                @elseif(isSeller())
                    <a href="{{ route('dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if (Auth::user()->photo != null)
                                <img src="{{ custom_asset(Auth::user()->avatar_original) }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-white">{{ translate('My Account') }}</span>
                    </a>
                @else
                    <a href="javascript:void(0)"
                        class="text-secondary d-block text-center pb-2 pt-3 mobile-side-nav-thumb"
                        data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <span class="d-block mx-auto">
                            @if (Auth::user()->photo != null)
                                <img src="{{ custom_asset(Auth::user()->avatar_original) }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-white">{{ translate('My Account') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="text-secondary d-block text-center pb-2 pt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
                            <path id="Path_2924" data-name="Path 2924"
                                d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3"
                                transform="translate(-3499.144 602)" fill="#fff" />
                            <path id="Path_2925" data-name="Path 2925"
                                d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4"
                                transform="translate(-3495.144 592)" fill="#fff" />
                        </g>
                    </svg>
                    <span class="d-block mt-1 fs-10 fw-600 text-white">{{ translate('My Account') }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
<!-- User Side nav -->
@if (Auth::check() && !isAdmin())
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static"
            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif
