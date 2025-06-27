<div class="card-header py-3 px-0 border-bottom-0">
    <h5 class="fs-16 fw-700 text-dark mb-0">{{ get_shop_by_user_id($key)->name }} {{ translate('Products') }}</h5>
      {{-- Fecha de entrega del producto: {{ \Carbon\Carbon::parse($seller_product['date'])->isoFormat('D [de] MMMM [de] YYYY', 'Do MMMM YYYY') }} --}}

     <div class="alert alert-warning" role="alert">

        Fecha de entrega del producto: <b>
        @if(\Carbon\Carbon::parse($seller_product['date'])->isSameDay(\Carbon\Carbon::now()))
         Recíbelo hoy mismo &#x1F6D2 {{ \Carbon\Carbon::parse($seller_product['date'])->isoFormat('D [de] MMMM [de] YYYY', 'Do MMMM YYYY') }}
        @else
            {{ \Carbon\Carbon::parse($seller_product['date'])->isoFormat('D [de] MMMM [de] YYYY', 'Do MMMM YYYY') }}
        @endif
        </b>
     </div>
</div>
