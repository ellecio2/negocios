<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">

    {{-- <div class="d-flex">

        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3 ml-0" data-toggle="aiz-mobile-nav" >

            <button class="aiz-mobile-toggler">

                <span></span>

            </button>

        </div>

    </div> --}}

    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">

        <div class="d-flex justify-content-around align-items-center align-items-stretch">

            <div class="d-flex justify-content-around align-items-center align-items-stretch">

                {{-- <div class="aiz-topbar-item">

                    <div class="d-flex align-items-center">

                        <a class="btn btn-icon btn-circle btn-light" href="" target="_blank" title="{{ translate('Browse Website') }}">

                <i class="las la-globe"></i>

                </a>

            </div>

        </div> --}}

    </div>

    {{-- @if (addon_is_activated('pos_system'))

                <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">

                    <div class="aiz-topbar-item">

                        <div class="d-flex align-items-center">

                            <a class="btn btn-icon btn-circle btn-light" href="{{ route('poin-of-sales.seller_index') }}" target="_blank" title="{{ translate('POS') }}">

    <i class="las la-print"></i>

    </a>

</div>

</div>

</div>

@endif --}}

</div>

<div class="d-flex justify-content-around align-items-center align-items-stretch">



    <div class="aiz-topbar-item ml-2">

        <div class="align-items-stretch d-flex dropdown">

            <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">

                <span class="btn btn-icon p-0 d-flex justify-content-center align-items-center">

                    <span class="d-flex align-items-center position-relative">

                        <i class="las la-bell fs-24"></i>



                        @if(Auth::user()->unreadNotifications->count() > 0)



                        <span class="badge-sm badge-circle badge-danger position-absolute absolute-top-right"> {{ auth()->user()->unreadNotifications->count()}} </span>

                        @endif

                    </span>

                </span>

            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">

                <div class="p-3 bg-light border-bottom">

                    <h6 class="mb-0">{{ translate('Notifications') }}</h6>

                </div>

                <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">

                    <ul class="list-group list-group-flush">

                        @forelse(Auth::user()->unreadNotifications->take(20) as $notification)

                        {{-- list-group-item d-flex justify-content-between align-items- py-3 --}}

                        <li class="list-group-item d-flex justify-content-between lh-condensed">

                            {{-- <div class="media text-inherit">

                                            <div class="media-body"> --}}

                            @if($notification->type == 'App\Notifications\ServiceRequestNotification')

                            <a href="{{ route('workshop.mark_a_notification', [$notification->id, $notification->data['id']]) }}" class="text-secondary fs-12">



                                <div>



                                    <h6 class="my-0">{{$notification->data['title']}}</h6>

                                    <small class="text-muted">Cliente: {{$notification->data['name']}}</small> <br>

                                    {{-- <small class="text-muted">

                                                                    Orden de compra: {{ $notification->data['order_code'] }}

                                    </small> --}}

                                    <small class="text-muted">

                                        Fecha: {{ date("F j Y, g:i a", strtotime($notification->created_at)) }}

                                    </small>

                                </div>



                            </a>

                            <span class="text-muted">

                                <span style="display: inline-block; width: 52px; height: 52px; background-color: #{{$notification->data['color']}};" class="border rounded-circle">

                                    <div>



                                        <i class="{{$notification->data['icon']}}" style="padding: 5px; color: white; font-size: 40px;"></i>

                                    </div>

                                </span>

                            </span>

                            {{-- <a style="padding: 10px;" href="{{ route('workshop.mark_a_notification', [$notification->id, $notification->data['id']]) }}">

                            <p class="mb-1 text-truncate-2">

                                <span style="display: inline-block; width: 32px; height: 32px; background-color: #{{$notification->data['color']}};" class="border rounded-circle">



                                    <i class="{{$notification->data['icon']}} fs-30" style="color: white"></i>

                                </span>







                                {{$notification->data['title']}}: {{$notification->data['name']}}









                            </p>

                            <small class="text-muted">

                                Fecha de solicitud: {{ date("F j Y, g:i a", strtotime($notification->created_at)) }}

                            </small>

                            @if(count(Auth::user()->unreadNotifications->take(20)) > 1)

                            <hr>

                            @endif

                            </a> --}}



                            @elseif ($notification->type == 'App\Notifications\AcceptServiceWorkshopProposalNotification')



                            <a href="{{ route('workshop.mark_a_accept_taller_notification', [$notification->id, $notification->data['id']]) }}" class="text-secondary fs-12">

                                <div>

                                    <h6 class="my-0">Acepto Propuesta</h6>

                                    <small class="text-muted">Cliente: {{ $notification->data['name'] }}</small> <br>

                                    <small class="text-muted">

                                        Fecha: {{ date("F j Y, g:i a", strtotime($notification->data['created_at'])) }}

                                    </small>

                                </div>

                            </a>

                            <span class="text-muted">

                                <span style="display: inline-block; width: 52px; height: 52px; background-color: #008B37;" class="border rounded-circle">

                                    <div>



                                        <i class="las la-handshake" style="padding: 5px; color: white; font-size: 40px;"></i>





                                    </div>

                                </span>

                            </span>



                            @endif



                            {{-- </div>

                                        </div> --}}

                        </li>

                        @empty

                        <li class="list-group-item">

                            <div class="py-4 text-center fs-16">

                                {{ translate('No notification found') }}

                            </div>

                        </li>

                        @endforelse

                    </ul>

                </div>

                {{-- <div class="text-center border-top">

                            <a href="{{ route('seller.all-notification') }}" class="text-reset d-block py-2">

                {{translate('View All Notifications')}}

                </a>

            </div> --}}

        </div>

    </div>

</div>



{{-- language --}}

@php

if(Session::has('locale')){

$locale = Session::get('locale', Config::get('app.locale'));

}

else{

$locale = env('DEFAULT_LANGUAGE');

}

@endphp

<div class="aiz-topbar-item ml-2">

    <div class="align-items-stretch d-flex dropdown " id="lang-change">

        <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">

            <span class="btn btn-icon">

                <img src="{{ static_asset('assets/img/flags/'.$locale.'.png') }}" height="11">

            </span>

        </a>

        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xs">



            @foreach (\App\Models\Language::where('status', 1)->get() as $key => $language)

            <li>

                <a href="javascript:void(0)" data-flag="{{ $language->code }}" class="dropdown-item @if($locale == $language->code) active @endif">

                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" class="mr-2">

                    <span class="language">{{ $language->name }}</span>

                </a>

            </li>

            @endforeach

        </ul>

    </div>

</div>



<div class="aiz-topbar-item ml-2">

    <div class="align-items-stretch d-flex dropdown">

        <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">

            <span class="d-flex align-items-center">

                <span class="avatar avatar-sm mr-md-2">

                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">

                </span>

                <span class="d-none d-md-block">

                    <span class="d-block fw-500">{{Auth::user()->name}}</span>









                    @if (Auth::user()->add_user_type === null)



                    <span class="d-block small opacity-60">{{Auth::user()->user_type}}</span>



                    @else



                    <span class="d-block small opacity-60">{{Auth::user()->add_user_type}}</span>



                    @endif









                </span>

            </span>

        </a>

        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">

            {{-- <a href="{{ route('workshop.profile.index') }}" class="dropdown-item">

            <i class="las la-user-circle"></i>

            <span>{{ translate('Profile') }}</span>

            </a> --}}



            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                @csrf

            </form>



            <a href="{{ route('logout')}}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                <i class="las la-sign-out-alt"></i>

                <span>{{translate('Logout')}}</span>

            </a>

        </div>

    </div>

</div>



<!-- .aiz-topbar-item -->

</div>

</div>

</div><!-- .aiz-topbar -->