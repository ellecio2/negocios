@extends('seller.layouts.app')

@section('panel_content')

	<div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Reason of Refund Request') }}</h1>
            </div>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                <div class="aiz-user-panel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Reason of Refund Request') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {{ $refund->reason }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
