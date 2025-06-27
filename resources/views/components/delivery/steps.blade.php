<!-- Steps -->
<section class="pt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row gutters-5 sm-gutters-10">
                    <div @class([ 'col', 'active' => $step == 1, 'done' => $step > 1])>
                        <div @class([
                                'text-center',
                                'border',
                                'border-bottom-6px',
                                'p-2',
                                'rounded-15px',
                                'opacity-50' => $step < 1,
                                'text-primary' => $step == 1,
                                'text-success' => $step > 1
                                ])>
                            <i @class([
                                    'la-3x',
                                    'mb-2',
                                    'las',
                                    'la-shopping-cart',
                                    'cart-animate' => $step == 1
                                ])
                                @style([
                                    'margin-left: -100px' => $step == 1,
                                    'transition: 2s' => $step == 1
                                ])></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                {{ translate('1. My Cart') }}
                            </h3>
                        </div>
                    </div>
                    <div @class([ 'col', 'active' => $step == 2, 'done' => $step > 2])>
                        <div @class([
                                'text-center',
                                'border',
                                'border-bottom-6px',
                                'p-2',
                                'rounded-15px',
                                'opacity-50' => $step < 2,
                                'text-primary' => $step == 2,
                                'text-success' => $step > 2
                                ])>
                            <i @class([
                                    'la-3x',
                                    'mb-2',
                                    'las',
                                    'la-map',
                                    'cart-animate' => $step == 2
                                ])
                                @style([
                                    'margin-left: -100px' => $step == 2,
                                    'transition: 2s' => $step == 2
                                ])></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                2. Envío
                            </h3>
                        </div>
                    </div>
                    <div @class([ 'col', 'active' => $step == 3, 'done' => $step > 3])>
                        <div @class([
                                'text-center',
                                'border',
                                'border-bottom-6px',
                                'p-2',
                                'rounded-15px',
                                'opacity-50' => $step < 3,
                                'text-primary' => $step == 3,
                                'text-success' => $step > 3
                            ])">
                            <i @class([
                                    'la-3x',
                                    'mb-2',
                                    'las',
                                    'la-truck',
                                    'cart-animate' => $step == 3
                                ])
                               @style([
                                   'margin-left: -50px' => $step == 3,
                                   'transition: 2s' => $step == 3
                               ])></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                {{ translate('3. Delivery') }}
                            </h3>
                        </div>
                    </div>
                    <div @class([ 'col', 'active' => $step == 4, 'done' => $step > 4])>
                        <div @class([
                                'text-center',
                                'border',
                                'border-bottom-6px',
                                'p-2',
                                'rounded-15px',
                                'opacity-50' => $step < 4,
                                'text-primary' => $step == 4,
                                'text-success' => $step > 4
                            ])>
                            <i @class([
                                    'la-3x',
                                    'mb-2',
                                    'las',
                                    'la-credit-card',
                                    'cart-animate' => $step == 4
                                ])
                                @style([
                                    'margin-left: -50px' => $step == 4,
                                    'transition: 2s' => $step == 4
                                ])></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                {{ translate('4. Payment') }}
                            </h3>
                        </div>
                    </div>
                    <div @class([ 'col', 'active' => $step == 5])>
                        @if($step == 5)
                            <div class="text-center border border-bottom-6px p-2 text-primary rounded-15px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32.001"
                                     viewBox="0 0 32 32.001" class="cart-rotate mb-3 mt-1">
                                    <g id="Group_23976" data-name="Group 23976" transform="translate(-282 -404.889)">
                                        <path class="cart-ok has-transition" id="Path_28723" data-name="Path 28723"
                                              d="M313.283,409.469a1,1,0,0,0-1.414,0l-14.85,14.85-5.657-5.657a1,1,0,1,0-1.414,1.414l6.364,6.364a1,1,0,0,0,1.414,0l.707-.707,14.85-14.849A1,1,0,0,0,313.283,409.469Z"
                                              fill="#ffffff"/>
                                        <g id="LWPOLYLINE">
                                            <path id="Path_28724" data-name="Path 28724"
                                                  d="M313.372,416.451,311.72,418.1a14,14,0,1,1-5.556-8.586l1.431-1.431a16,16,0,1,0,5.777,8.365Z"
                                                  fill="#003b73"/>
                                        </g>
                                    </g>
                                </svg>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        @else
                            <div class="text-center border border-bottom-6px p-2 rounded-15px">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">
                                    {{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
