<table id="nfc_vouchers_table" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>NFC Tipo</th>
            <th>NFC Siguiente</th>
            <th>NFC Vencimiento</th>
            <th>NFC Cantidad</th>
            <th>NFC Próximo</th>
            <th>NFC Estado</th>
            <th>NFC Usado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nfc_vouchers as $nfc_voucher)
            <tr>
                <td>{{ $nfc_voucher->id }}</td>
                <td>{{ $nfc_voucher->nfc_type }}</td>
                <td>{{ $nfc_voucher->nfc_following }}</td>
                <td>{{ $nfc_voucher->nfc_expiration }}</td>
                <td>{{ $nfc_voucher->nfc_amount }}</td>
                <td>{{ $nfc_voucher->nfc_next }}</td>
                <td>
                    @if($nfc_voucher->nfc_select == 'active')
                        Activo
                    @elseif($nfc_voucher->nfc_select == 'deactivated')
                        Desactivado
                    @endif
                </td> 
                <td>
                    @if($nfc_voucher->nfc_used == 'not_use')
                        No usado
                    @elseif($nfc_voucher->nfc_used == 'in_use')
                        En uso
                    @endif
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

