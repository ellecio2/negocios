<!-- Top Bar Banner -->
@if (get_setting('topbar_banner') != null)
    <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner" data-value="removed">
        <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset">
            <img src="{{ uploaded_asset(get_setting('topbar_banner')) }}" class="d-none d-xl-block img-fit"
                alt="{{ translate('topbar_banner') }}">
            <!-- For Large device -->
            <img src="{{ get_setting('topbar_banner_medium') != null ? uploaded_asset(get_setting('topbar_banner_medium')) : uploaded_asset(get_setting('topbar_banner')) }}"
                class="d-none d-md-block d-xl-none img-fit" alt="{{ translate('topbar_banner') }}">
            <!-- For Medium device -->
            <img src="{{ get_setting('topbar_banner_small') != null ? uploaded_asset(get_setting('topbar_banner_small')) : uploaded_asset(get_setting('topbar_banner')) }}"
                class="d-md-none img-fit" alt="{{ translate('topbar_banner') }}"> <!-- For Small device -->
        </a>
        <button class="btn text-white h-100 absolute-top-right set-session" data-key="top-banner" data-value="removed"
            data-toggle="remove-parent" data-parent=".top-banner">
            <i class="la la-close la-2x"></i>
        </button>
    </div>
@endif
<header class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 bg-lapieza">
    <!-- Search Bar -->
    <div class="position-relative logo-bar-area bg-lapieza z-1025">
        <div class="container">
            <div class="d-flex align-items-center">
                <!-- top menu sidebar button -->
                <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                    data-target=".aiz-top-menu-sidebar">
                    <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" viewBox="0 0 16 16">
                        <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                            transform="translate(0 7)" fill="#E63118" />
                        <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                            fill="#E63118" />
                        <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                            transform="translate(0 14)" fill="#E63118" />
                    </svg>
                </button>
                <!-- Header Logo -->
                <div class="col-auto pl-0 pr-3 d-flex align-items-center">
                    <a class="d-block py-10px mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-80px h-md-90px" height="100">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-80px h-md-90px" height="100">
                        @endif
                    </a>
                </div>
                <!-- Search Icon for small device -->
                <div class="d-lg-none ml-auto">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i style="color: black;" class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>
                <!-- Search field -->
                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-lapieza mx-xl-5">
                    <div class="position-relative flex-grow-1 px-3 px-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i style="color: #E63118;"
                                            class="la la-2x la-long-arrow-left"></i>
                                    </button>
                                </div>

                                <div class="search-input-box">
                                    <input type="text"
                                        class="border border-soft-light form-control fs-14 hov-animate-outline-lapieza"
                                        id="search" name="keyword"
                                        @isset($query)
                                               value="{{ $query }}"
                                           @endisset
                                        placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">
                                    <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                        width="20.001" height="20" viewBox="0 0 20.001 20">
                                        <path id="Path_3090" data-name="Path 3090"
                                            d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                            transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                        <path id="Path_3091" data-name="Path 3091"
                                            d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                            transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                    </svg>
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">
                            </div>
                            <div id="search-content" class="text-left">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Search box -->
                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>
                <!-- Compare -->
                <div class="d-none d-lg-block ml-3 mr-0">
                    <div class="" id="compare">
                        @include('frontend.partials.compare')
                    </div>
                </div>
                <!-- Wishlist -->
                <div class="d-none d-lg-block mr-3" style="margin-left: 36px;">
                    <div class="" id="wishlist">
                        @include('frontend.partials.wishlist')
                    </div>
                </div>

                @auth
                    @if (Auth::user()->user_type == 'customer')
                        <livewire:notifications />
                    @endif
                @endauth


                <div class="ml-auto mr-0">
                    @auth
                        <span
                            class="d-none d-xl-flex align-items-center nav-user-info my-20px @if (isAdmin()) ml-5 @endif"
                            id="nav-user-info">
                            <!-- Image -->
                            <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                @if (Auth::user()->avatar_original != null)
                                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" class="img-fit h-100"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>
                            <!-- Name -->
                            @if (Auth::user()->user_type == 'customer')
                                <h4 class="h5 fs-14 fw-700 text-negro ml-2 mb-0">{{ Auth::user()->name }}</h4>
                            @elseif (Auth::user()->user_type == 'seller')
                                <h4 class="h5 fs-14 fw-700 text-negro ml-2 mb-0">{{ Auth::user()->shop->name }}</h4>
                            @else
                                <h4 class="h5 fs-14 fw-700 text-negro ml-2 mb-0">{{ Auth::user()->name }}</h4>
                            @endif
                        
                        </span>
                    @else
                        <!--Login & Registration -->
                        <span class="d-none d-xl-flex align-items-center nav-user-info ml-3">
                            <!-- Image -->
                            {{-- <div class="social-icon-list"> --}}
                            <div>
                                <div class="icon-group ">
                                    {{-- <a class="hide" style="color: #fff; font-weight: bold;">Acceso</a> --}}
                                    <a class="icon-text btn" href="{{ route('user.login') }}">
                                        Acceso
                                    </a>
                                    <span class="icon-bg"></span>
                                </div>
                                <div class="icon-group">
                                    {{-- <a class="hide" style="color: #fff; font-weight: bold;">Registro</a> --}}
                                    <a class="icon-text btn" href="{{ route('register') }}">
                                        Registro
                                    </a>
                                    <span class="icon-bg"></span>
                                </div>
                            </div>
                            {{-- <a href="{{ route('user.login') }}"
                                    class="text-reset opacity-100 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                                <a href="{{ route('register') }}"
                                    class="text-reset opacity-100 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a> --}}
                            {{-- <a href="{{ route('user.registration') }}"
                                class="text-reset opacity-100 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a> --}}
                        </span>
                    @endauth
                </div>
            </div>
        </div>
        <!-- Loged in user Menus -->
        <div class="hover-user-top-menu position-absolute top-65 left-150 right-0 z-3 rounded-15px">
            <div class="container">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            @if (isAdmin())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (isCustomer())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('digital_purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                            viewBox="0 0 16.001 16">
                                            <g id="Group_25262" data-name="Group 25262"
                                                transform="translate(-1388.154 -562.604)">
                                                <path id="Path_2963" data-name="Path 2963"
                                                    d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                    transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                <path id="Path_2964" data-name="Path 2964"
                                                    d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                    transform="translate(1324 486)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                    </a>
                                </li>
                                @if (get_setting('conversation_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('conversations.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_25263" data-name="Group 25263"
                                                    transform="translate(1053.151 256.688)">
                                                    <path id="Path_3012" data-name="Path 3012"
                                                        d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                        transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                    <path id="Path_3013" data-name="Path 3013"
                                                        d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                        transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                    <path id="Path_3014" data-name="Path 3014"
                                                        d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                    <path id="Path_3015" data-name="Path 3015"
                                                        d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                        transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('wallet_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('wallet.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                                height="16" viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                            width="16" height="16" fill="#b5b5bf" />
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102"
                                                    clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('support_ticket.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                            viewBox="0 0 16 16.001">
                                            <g id="Group_25259" data-name="Group 25259"
                                                transform="translate(-316 -1066)">
                                                <path id="Subtraction_184" data-name="Subtraction 184"
                                                    d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                    transform="translate(-16096 180)" fill="#b5b5bf" />
                                                <path id="Union_12" data-name="Union 12"
                                                    d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                    transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503"
                                            transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533" />
                                        </g>

                                    </svg>
                                    <span
                                        class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menu Bar -->
    <div class="d-none d-lg-block position-relative bg-lapieza h-50px">
        <div class="container h-100">
            <div class="d-flex h-100">
                <!-- Categoty Menu Button -->
                <div class="d-none d-xl-block all-category has-transition bg-black-10" id="category-menu-bar"
                    style="border-radius: 10px 10px 0 0;">
                    <div class="px-3 h-100"
                        style="padding-top: 12px;padding-bottom: 12px; width:270px; cursor: pointer; background-color: #003b73; border-radius: 10px 10px 0 0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-700 fs-16 text-white mr-3">Busca tu Repuesto:</span>
                                {{-- <a href="{{ route('categories.all') }}" class="text-reset">
                                    <span
                                        class="d-none d-lg-inline-block text-white hov-opacity-80">({{ translate('See All') }})</span>
                                </a> --}}
                            </div>

                            <i class="las la-angle-down text-white has-transition" id="category-menu-bar-icon"
                                style="font-size: 1.2rem !important"></i>

                        </div>
                    </div>
                </div>
                @if (Auth::user() )
                    <div class="dropdown" style="margin-left: 10px;">
                        <button type="button" class="btn btn-link p-0" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            <span class="size-50px  mx-auto bg-primary d-flex align-items-center justify-content-center mb-3" style="border-radius: 10px 10px 0 0;">
                                <i class="las la-plus la-3x text-white"></i>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="min-width: 300px;">
                            <h6 class="dropdown-header"><strong>Mis Artículos</strong></h6>
                            <div class="dropdown-divider"></div>
                            <div class="px-3" style="max-height: 200px; overflow-y: auto;">
                                <div id="userArticlesTable">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center font-weight-bold" href="{{ route('articles.index') }}">
                                Ir a Mis Artículos
                            </a>
                        </div>
                    </div>
                @endif

                @if (Auth::user() && Auth::user()->user_type == 'seller')
                    @include('frontend.inc.articles_modal')
                @endif

                <!-- Header Menus -->
                <div class="ml-xl-4 w-100 overflow-hidden">
                    <div class="d-flex align-items-right justify-content-right justify-content-right-start h-100">
                        <ul class="list-inline mb-0 pl-0 hor-swipe c-scrollbar-light">
                            @if (get_setting('header_menu_labels') != null)
                                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                    <li class="list-inline-item mr-0 animate-underline-white">
                                        <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                                            class="fs-13 px-3 py-3 d-inline-block fw-700 text-negro header_menu_links hov-bg-black-10
                                            @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif"
                                            style="border-radius: 10px 10px 0px 0px;">
                                            {{ translate($value) }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <!-- Cart -->
                <div class="d-none d-xl-block align-self-stretch ml-5 mr-0 has-transition"
                    style="border-radius: 10px 10px 0px 0px;  background-color: #003b73;" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items" style="width: max-content;">
                        @include('frontend.partials.cart')
                    </div>
                </div>
            </div>
        </div>
        <!-- Categoty Menus -->
        <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 z-3 d-none"
            id="click-category-menu">
            <div class="container">
                <div class="d-flex position-relative">
                    <div class="position-static">
                        @include('frontend.partials.category_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Top Menu Sidebar -->
<div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar"
        data-same=".hide-top-menu-bar"></div>
    <div class="collapse-sidebar c-scrollbar-light text-left">
        <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar">
            <i class="las la-times la-2x text-primary"> <span><a class="fonts-login-menu"
                        style="font-family: 'Rubik';">Bienvenido</a></span>
            </i>
        </button>
        @auth
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                    @if (Auth::user()->avatar_original != null)
                        <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" class="img-fit h-100"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @endif
                </span>
                <!-- Name -->
                <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ Auth::user()->name }}</h4>
            </span>
        @else
            <!--Login & Registration -->
            <span class="d-flex align-items-center nav-user-info pl-4 justify-content-center">
                <!-- Image -->
                <div class="">
                    <div class="icon-group">
                        {{-- <a class="hide" style="color: #fff; font-weight: bold;">Acceso</a> --}}
                        <a class="icon-text" href="{{ route('user.login') }}">
                            Acceso
                        </a>
                        <span class="icon-bg"></span>
                    </div>
                    <div class="icon-group">
                        {{-- <a class="hide" style="color: #fff; font-weight: bold;">Registro</a> --}}
                        <a class="icon-text" href="{{ route('register') }}">
                            Registro
                        </a>
                        <span class="icon-bg"></span>
                    </div>
                </div>
            </span>
        @endauth
        <hr>
        <ul class="mb-0 pl-3 pb-3 header-menu">
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <li class="margin-r">
                        <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                            {{ translate($value) }}
                        </a>
                    </li>
                @endforeach
            @endif
            @auth
                @if (isAdmin())
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('admin.dashboard') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @else
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('dashboard') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['dashboard'], ' active') }}">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @endif
                @if (isCustomer())
                    <li class="mr-0">
                        <a href="{{ route('all-notifications') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['all-notifications'], ' active') }}">
                            {{ translate('Notifications') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('wishlists.index') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            {{ translate('Wishlist') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('compare') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['compare'], ' active') }}">
                            {{ translate('Compare') }}
                        </a>
                    </li>
                @endif
                <hr>
                <li class="mr-0">
                    <a href="{{ route('logout') }}"
                        class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-primary header_menu_links">
                        {{ translate('Logout') }}
                    </a>
                </li>
            @endauth
            <br>
            {{-- Banner Section 1 --}}
            <li>
                @if (get_setting('home_banner1_images') != null)
                    <div class="mb-2 mb-md-3 mt-2 mt-md-3">
                        <div class="container">
                            @php
                                $banner_1_imags = json_decode(get_setting('home_banner1_images'));
                                $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
                            @endphp
                            <div class="w-100">
                                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                                    data-items="{{ count($banner_1_imags) }}"
                                    data-xxl-items="{{ count($banner_1_imags) }}"
                                    data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}"
                                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1"
                                    data-arrows="true" data-dots="false">
                                    @foreach ($banner_1_imags as $key => $value)
                                        <div class="carousel-box carousel-box1 overflow-hidden hov-scale-img  ">
                                            <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}"
                                                class="d-block text-reset overflow-hidden br-banner">
                                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ uploaded_asset($value) }}"
                                                    alt="{{ env('APP_NAME') }} promo"
                                                    class="img-fluid lazyload w-100 has-transition"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Banner Section 1 --}}
            </li>
        </ul>
        <br>
        <br>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">
            </div>
        </div>
    </div>
</div>

{{-- @section('modal')
    @include('frontend.inc.articles_modal')
@endsection --}}
<script src="{{ static_asset('assets/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Variable para controlar si ya se cargaron los artículos
        let articlesLoaded = false;

        // Corregir el evento del dropdown (para Bootstrap 5)
        $('#dropdownMenuButton').on('click', function() {
            if (!articlesLoaded) {
                loadUserArticles();
            }
        });

        function loadUserArticles() {
            $.ajax({
                url: '{{ route('articles.get_articles') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#userArticlesTable .spinner-border').hide();
                    let tableHtml = `
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Modelo</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                    // Verificar si response es un array y tiene elementos
                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(function(article) {
                            tableHtml += `
                            <tr>
                                <td>
                                    <img src="${article.image}" alt="Imagen ${article.modelo}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                </td>
                                <td>
                                    <span class="clickable-model">${article.modelo}</span>
                                     <form action="{{ route('search_category') }}" method="GET" class="stop-propagation">
                                    <input type="hidden" id="search" name="keyword" value="${article.modelo}">
                                    <input type="hidden" id="category" name="category" value="${article.category_id}">
                                    </form>
                                </td>
                            </tr>
                        `;
                        });
                    } else {
                        tableHtml += `
                        <tr>
                            <td colspan="2" class="text-center">No hay artículos disponibles</td>
                        </tr>
                    `;
                    }

                    tableHtml += `
                        </tbody>
                    </table>
                `;

                    $('#userArticlesTable').html(tableHtml);
                    articlesLoaded = true;
                },
                error: function(xhr, status, error) {
                    console.error('Error en la llamada AJAX:', error);
                    console.error('Estado:', status);
                    console.error('Respuesta:', xhr.responseText);
                }
            });
        }

        // Evento para hacer clic en el texto del modelo
        $('#userArticlesTable').on('click', '.clickable-model', function() {

            const form = $(this).next('form');

            // Enviar el formulario
            form.submit();
        });
    });
</script>

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);
            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }
            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function(data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
    {{--    <script src="{{ asset() }}"></script> --}}
@endsection
