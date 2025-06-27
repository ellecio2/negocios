@extends('frontend.layouts.app')
@section('styles')
    <style>
        #preloader-delivery {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            height: 200px;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 5;
        }

        #loader-delivery {
            display: block;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            border: 3px solid transparent;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin-left: 3%;
        }

        #loader-delivery:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #E63108;
            -webkit-animation: spin 3s linear infinite;
            animation: spin 3s linear infinite;
        }

        #loader-delivery:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #000;
            -webkit-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
        }

        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.5s;
        }

        .fade-enter, .fade-leave-to {
            opacity: 0;
        }

        .btn-tooltip{
            font-size: 0.7rem;
            color: white;
            margin: 0;
            padding: 1px;
            width: 25px;
            height: 25px;
            border-style: none;
            border-radius: 50px;
            font-weight: bold;
            background-color: #a71d2a;
        }

        .box-title.custom{
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-transform: uppercase;
            width: 100%;
            font-weight: bold;
            color: white;
            align-content: center;
        }

        .rowe{
            align-items: center;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
@section('content')
    {{-- Steps Section --}}
    @include('components.delivery.steps', ['step' => 3])
    <!-- Delivery Info -->
    <section class="py-4 gry-bg">
        <div class="container">
            <div class="rowe">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <div class="border bg-white p-4 mb-4 rounded-15px">
                        <div class="hold-transition light-skin sidebar-mini theme-warning fixed">
                            <!-- Contenedor padre -->
                            <div class="post">
                                <div class="activitytimeline" id="app"></div>
                            </div>
                            <div class="row align-items-center">
                                <!-- Return to shop -->
                                <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                                    <a href="{{ route('checkout.shipping_info') }}" class="btn btn-link fs-14 fw-700 px-0 mt-15px">
                                        <i class="las la-arrow-left fs-16"></i>
                                        {{ translate('Regresar a direcciones') }}
                                    </a>
                                </div>
                                <!-- Continue to Delivery Info -->
                                <form action="{{ route('checkout.store_delivery_info') }}" class="col-md-6" method="POST">
                                    @csrf
                                    <div class="text-center text-md-right">
                                        <button type="submit"  class="btn btn-primary fs-14 fw-700 rounded-25px px-4" id="submit-button">
                                            {{ translate('Continue to Payment') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Contenedor padre -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        const url = "{{ config('app.url') }}";
    </script>
    <script>
        window.Laravel = {
            isTest: @json(config('app.pedidosya_mode') == 'development')
        };
    </script>
    <script src="{{ asset('./public/assets/js/client/checkout/assets/vendors.min.js') }}"></script>
    <script src="{{ asset('./public/assets/js/client/checkout/assets/template.js') }}"></script>
    <script src="{{ asset('./public/assets/js/client/checkout/assets/timeline.js') }}"></script>
    <script src="{{ asset('./public/assets/js/client/checkout/assets/feather-icons/feather.min.js') }}"></script>

    <script src="{{ asset('./public/assets/js/utilities/delivery/pedidosYaFunctions.js') }}" type="text/javascript"></script>
    <script src="{{ asset('./public/assets/js/views/delivery-info.js') }}" type="text/javascript"></script>
    <script src="{{ asset('./public/assets/js/client/checkout/deliveryRender.js') }}" type="module"></script>
@endsection
