<form class="" action="" method="post" enctype="multipart/form-data">

    @csrf

    <div class="modal-body gry-bg px-3 pt-3 mx-auto c-scrollbar-light">

        <div class="card-header border-bottom-0">

            <h5 class="mb-0 fs-20 fw-700 text-dark text-center text-md-left">Registra aqui tus artículos 1</h5>

        </div>


        <div id="manual_payment_data">

            <!-- Payment description -->

            <div class="card rounded-0 shadow-none border mb-3 p-3 d-none">

                <div id="manual_payment_description">

                </div>

            </div>
        <!-- Type -->
            <div class="card rounded-0 shadow-none border mb-3 p-3">
                <div class="row mt-3">

                    <div class="col-md-3">

                        <label>Selecciona el tipo de Artículo:<span class="text-danger">*</span></label>

                    </div>

                    <div class="col-md-9">


                        <select class="form-control aiz-selectpicker rounded-15" data-placeholder="{{ translate('Select a Category')}}" id="categories" name="category_id" data-live-search="true" required">

                            <option value="Seleccione el tipo:" selected>Seleccione el tipo:</option>
                            @foreach ($categories as $key => $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>

                            @endforeach
                        </select>


                    </div>

                </div>

                <!-- Brand -->

                <div class="row mt-3">

                    <div class="col-md-3">

                        <label>Marca<span class="text-danger">*</span></label>

                    </div>

                    <div class="col-md-9">

                        <input type="number" lang="en" class="form-control mb-3 rounded-15px" min="0" step="0.01" name="amount" placeholder="Marca" required>

                    </div>

                </div>
                <div class="row mt-3">

                    <div class="col-md-3">

                        <label>Modelo <span class="text-danger">*</span></label>

                    </div>

                    <div class="col-md-9">

                        <input type="number" lang="en" class="form-control mb-3 rounded-15px" min="0" step="0.01" name="amount" placeholder="Modelo" required>

                    </div>

                </div>
                <div class="row mt-3">

                    <div class="col-md-3">

                        <label>Chásis | SN <span class="text-danger">*</span></label>

                    </div>

                    <div class="col-md-9">

                        <input type="number" lang="en" class="form-control mb-3 rounded-15px" min="0" step="0.01" name="amount" placeholder="Chásis | SN"  required>

                    </div>

                </div>

                <!-- Transaction ID -->



                <!-- Payment screenshot -->



            </div>

            <!-- Confirm Button -->

            <div class="form-group text-right">

                <button type="submit" class="btn btn-sm btn-primary rounded-25px w-150px transition-3d-hover">{{translate('Confirm')}}</button>

            </div>

        </div>

    </div>

</form>



@foreach(get_all_manual_payment_methods() as $method)

<div id="manual_payment_info_{{ $method->id }}" class="d-none">

    <div>@php echo $method->description @endphp</div>

    @if ($method->bank_info != null)

    <ul>

        @foreach (json_decode($method->bank_info) as $key => $info)

        <li>{{ translate('Bank Name') }} - {{ $info->bank_name }}, {{ translate('Account Name') }} - {{ $info->account_name }}, {{ translate('Account Number') }} - {{ $info->account_number}}, {{ translate('Routing Number') }} - {{ $info->routing_number }}</li>

        @endforeach

    </ul>

    @endif

</div>

@endforeach



<script type="text/javascript">
    $(document).ready(function() {

        toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));

    });



    function toggleManualPaymentData(id) {

        $('#manual_payment_description').parent().removeClass('d-none');

        $('#manual_payment_description').html($('#manual_payment_info_' + id).html());

    }
</script>
