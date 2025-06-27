@extends('seller.layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/seller/products/create.css') }}">
@endsection
@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Add Your Product') }}</h1>
            </div>
        </div>
    </div>

    <form class="" method="POST" enctype="multipart/form-data" id="choice_form">
        @csrf
        <div class="row gutters-5 ">
            {{-- Left Panel --}}
            <div class="col-lg-8">
                {{-- Product Information --}}
                @include('seller.products.components.product-information')
            </div>

            {{-- Right Panel --}}
            <div class="col-lg-4">
                {{-- Product Pricing --}}
                @include('seller.products.components.product-price')
                {{-- Product Attributes --}}
                @include('seller.products.components.product-attributes')
                {{-- Flash Deals --}}
                @include('seller.products.components.flash-deals')
            </div>

            {{-- Buttons Section --}}
            <div class="col-12">
                {{-- Buttons --}}
                @include('seller.products.components.form-buttons')
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script type="text/javascript">
        const url = "{{ config('app.url') }}";
    </script>
    <!-- Script functions -->
    <script src="{{ asset('public/assets/js/seller/products/create/functions.js') }}" type="text/javascript"
            defer></script>
@endsection
