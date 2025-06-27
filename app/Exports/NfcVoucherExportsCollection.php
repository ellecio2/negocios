<?php

namespace App\Exports;

use App\Models\NfcVoucher;
use Maatwebsite\Excel\Concerns\FromCollection;

class NfcVoucherExportsCollection implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return NfcVoucher::all();
    }
}
