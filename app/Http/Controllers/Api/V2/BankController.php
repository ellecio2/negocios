<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Bank;

class BankController extends Controller {
    public function index() {
        $banks = Bank::select('bank_name')->get();
        $banks_formatted = [];

        foreach ($banks as $bank){
            array_push($banks_formatted, $bank->bank_name);
        }

        return response()->json([
            'result' => true,
            'message' => 'success',
            'data' => $banks_formatted
        ]);

    }
}
