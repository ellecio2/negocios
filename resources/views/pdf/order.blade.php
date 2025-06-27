@php use App\Models\BusinessSetting; @endphp
    <!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }

        .total-container {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
        }

        p {
            margin: 0;
            padding: 1px 0;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            padding: 10px 0;
        }

    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ public_path('uploads/all/spZ7LN6c3Kj3l5kkTI0FBzgQOmJrDgUZcsh64mNY.png') }}" height="50">
    </div>

    <table>
        <tbody>
            <tr>
                <td><b>Vendedor:</b> {{ $seller->name }}</td>
                <td><b>Fecha:</b> {{ $fecha instanceof \Carbon\Carbon ? $fecha->format('Y-m-d') : $fecha }}</td>
            </tr>
            <tr>
                <td><b>RNC Vendedor:</b> {{ $seller->rnc }}</td>
                <td><b>Generado Por:</b> {{ $seller->name }}</td>
            </tr>
        </tbody>
    </table>

    <h1 style="text-align: center;">Reporte de Ventas</h1>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data) && count($data) > 0)
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['date'] }}</td>
                        <td>{{ number_format($item['price'], 2, '.', ',') }} RD$</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" style="text-align: center;">No hay datos disponibles</td>
                </tr>
            @endif
        </tbody>
    </table>

    @php
        $vendor_commission = \App\Models\BusinessSetting::where('type', 'vendor_commission')->first()->value ?? 0;
        $admin_commission = $total * ($vendor_commission / 100);
    @endphp

    <div class="total-container">
    <p><b>Total Venta: </b> {{ single_price($total) }}</p>
    <p><b>Descuento: </b> {{ single_price($descuento) }}</p>
    <p><b>Envío: </b> {{ single_price($shipping_cost) }}</p>
    <p><b>Comisión la Pieza: </b> {{ single_price($admin_commission) }}</p>
    <p><b>Itbis: </b> {{ single_price($tax) }}</p>
    <p><b>Total
            Ganancias: </b> {{ single_price($total - $admin_commission) }}
    </p>
</div>

    <div class="footer">
        <p>________________________________________________________________</p>
        <p><b>Generado y revisado por: </b>{{ $seller->name }}</p>
    </div>
</body>
</html>