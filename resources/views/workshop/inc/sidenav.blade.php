<div class="aiz-sidebar-wrap">

    <div class="aiz-sidebar left c-scrollbar">

        <div class="aiz-side-nav-logo-wrap">

            <div class="d-block text-center my-3">

                @if (optional(Auth::user()->shop)->logo != null)

                    <img class="mw-100 mb-3" src="{{ uploaded_asset(optional(Auth::user()->shop)->logo) }}"

                        class="brand-icon" alt="{{ get_setting('site_name') }}">

                @else

                    <img class="mw-100 mb-3" src="{{ uploaded_asset(get_setting('header_logo')) }}" class="brand-icon"

                        alt="{{ get_setting('site_name') }}">

                @endif

                <h3 class="fs-16  m-0 text-primary">{{ optional(Auth::user()->shop)->name }}</h3>

                <p class="text-primary">{{ Auth::user()->email }}</p>

            </div>

        </div>

        <div class="aiz-side-nav-wrap">

            {{-- <div class="px-20px mb-3">

                <input class="form-control bg-soft-secondary border-0 form-control-sm" type="text" name=""

                    placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">

            </div> --}}

            {{-- <ul class="aiz-side-nav-list" id="search-menu">

            </ul> --}}

            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">

                {{-- <li class="aiz-side-nav-item">

                    <a href="{{ route('workshop.dashboard') }}" class="aiz-side-nav-link">

                        <i class="las la-home aiz-side-nav-icon"></i>

                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>

                    </a>

                </li> --}}

                {{-- <li class="aiz-side-nav-item">

                    <a href="{{ route('workshop.workshopService.index') }}"

                        class="aiz-side-nav-link {{ areActiveRoutes(['workshop.workshopService.index']) }}">

                        <i class="las la-folder-open aiz-side-nav-icon"></i>

                        <span class="aiz-side-nav-text">Solicitudes de clientes</span>

                    </a>

                </li> --}}



                <li class="aiz-side-nav-item">

                    <a href="{{ route('mark-as-read-and-redirect') }}"

                    class="aiz-side-nav-link {{ areActiveRoutes(['workshop.workshopService.index']) }}">

                    <i class="las la-folder-open aiz-side-nav-icon"></i>

                    <span class="aiz-side-nav-text">Solicitudes de clientes</span>

                    </a>

                </li>



                {{-- <li class="aiz-side-nav-item">

                    <a href="#" class="aiz-side-nav-link">

                        <i class="las la-shopping-cart aiz-side-nav-icon"></i>

                        <span class="aiz-side-nav-text">{{ translate('Products') }}</span>

                        <span class="aiz-side-nav-arrow"></span>

                    </a>

                  

                    <ul class="aiz-side-nav-list level-2">

                        <li class="aiz-side-nav-item">

                            <a href="{{ route('seller.products') }}"

                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.products', 'seller.products.create', 'seller.products.edit']) }}">

                                <span class="aiz-side-nav-text">{{ translate('Products') }}</span>

                            </a>

                        </li>



                        <li class="aiz-side-nav-item">

                            <a href="{{ route('seller.product_bulk_upload.index') }}"

                                class="aiz-side-nav-link {{ areActiveRoutes(['product_bulk_upload.index']) }}">

                                <span class="aiz-side-nav-text">{{ translate('Product Bulk Upload') }}</span>

                            </a>

                        </li>

                      

                    </ul>

                </li> --}}

                {{-- <li class="aiz-side-nav-item">

                    <a href=""

                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.shop.index']) }}">

                        <i class="las la-cog aiz-side-nav-icon"></i>

                        <span class="aiz-side-nav-text">{{ translate('Shop Setting') }}</span>

                    </a>

                </li> --}}


            </ul><!-- .aiz-side-nav -->

        </div><!-- .aiz-side-nav-wrap -->

    </div><!-- .aiz-sidebar -->

    <div class="aiz-sidebar-overlay"></div>

</div><!-- .aiz-sidebar -->

