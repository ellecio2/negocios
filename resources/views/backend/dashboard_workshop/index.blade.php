@extends('backend.layouts.app')

@section('content')
    <div class="row gutters-10">
        <div class="col-lg-6">
            <div class="row gutters-10">
                <div class="col-6">
                    <div class="bg-grad-3 text-white rounded-15p mb-4 overflow-hidden">
                        <div role="button">

                            <a href="{{ route('backend.dashboard_workshop.workshop_all.index') }}">
                                <div class="px-3 pt-3">
                                    <div>
                                        <span class="fs-20 text-white">{{ translate('Total') }}</span>
                                        <br>
                                        <span class="text-white">Talleres registrados</span>
                                    </div>
                                    <div class="h3 fw-700 mb-3 text-white">
                                        {{ $users_count }}
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                                    </path>
                                </svg>
                            </a>

                        </div>
                    </div>
                </div>

                {{-- <div class="col-6">
                        <div class="bg-grad-3 text-white rounded-15p mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-12 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Order') }}
                                </div>
                                <div class="h3 fw-700 mb-3">{{ \App\Models\Order::count() }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                                    d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                                </path>
                            </svg>
                        </div>
                    </div> --}}

            </div>
        </div>

        <div class="col-lg-6">
            <div class="row gutters-10">
                <div class="col-6">
                    {{-- <div class="bg-grad-1 text-white rounded-15p mb-4 overflow-hidden">
                                <div class="px-3 pt-3">
                                    <div class="opacity-50">
                                        <span class="fs-12 d-block">{{ translate('Total') }}</span>
                                        {{ translate('Product category') }}
                                    </div>
                                    <div class="h3 fw-700 mb-3">{{ \App\Models\Category::count() }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-grad-4 text-white rounded-15p mb-4 overflow-hidden">
                                <div class="px-3 pt-3">
                                    <div class="opacity-50">
                                        <span class="fs-12 d-block">{{ translate('Total') }}</span>
                                        {{ translate('Product brand') }}
                                    </div>
                                    <div class="h3 fw-700 mb-3">{{ \App\Models\Brand::count() }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                                    </path>
                                </svg>
                            </div>
                        </div> --}}
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card-body">

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
