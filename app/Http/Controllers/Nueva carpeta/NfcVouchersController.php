<?php


namespace App\Http\Controllers;


use App\Exports\NfcVoucherExportsCollection;
use App\Exports\NfcVoucherExportsView;
use App\Models\NfcVoucher;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class NfcVouchersController extends Controller

{


    public static function updateNfcOrder()
    {
        $ncf_user = User::find(Auth::id());
        $user_ncf = $ncf_user->type_ncf;

        $ncf_voucher = NfcVoucher::where('nfc_type', $user_ncf)->first();
        $total = ($ncf_voucher->nfc_amount - $ncf_voucher->nfc_next);

        if ($total <= 1) {
            $response = array('state' => false, 'message' => 'Se terminaron los concecutivos nfc solicitalos con el administrador');
            return response()->json($response);
        }

        //$ncf_voucher->increment('nfc_following');
        $ncf_voucher->increment('nfc_next');
        $ncf_voucher->user_id = $ncf_user->id;
        $ncf_voucher->nfc_used = 'used';

        $ncf_voucher->save();

        return $ncf_voucher->nfc_next;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()

    {


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create()

    {

        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */

    public function store(Request $request, NfcVoucher $nfc_voucher)
    {
        $validatedData = $request->validate([
            'nfc_type' => 'required',
            'nfc_following' => 'required',
            'nfc_expiration' => 'required|date',
            'nfc_amount' => 'required|numeric',
            'nfc_next' => 'required',
            //'nfc_select' => 'required|in:active,deactivated',
        ]);

        DB::beginTransaction();

        try {
            /*$nfc_voucher->nfc_type = $validatedData['nfc_type'];
            $nfc_voucher->nfc_following = $validatedData['nfc_following'];
            $nfc_voucher->nfc_expiration = $validatedData['nfc_expiration'];
            $nfc_voucher->nfc_amount = $validatedData['nfc_amount'];
            $nfc_voucher->nfc_next = $validatedData['nfc_next'];
            $nfc_voucher->nfc_select = $validatedData['nfc_select'];
            $nfc_voucher->save();*/

            $nfcVoucher = NfcVoucher::where('nfc_type', $validatedData['nfc_type'])->first();
            $uptetedRows = $nfcVoucher->update($validatedData);

            if ($uptetedRows > 0) {
                DB::commit();
                return redirect()->route('pos.configuration.admin')->with('toast_success', '¡Política de empresa creada con éxito!');
            } else {
                return response()->json(['message' => 'No rows updated'], 200);
            }

        } catch (Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param NfcVoucher $nfcVoucher
     * @return false|string
     */

    public function update(Request $request)
    {

        $nfcVoucher = NfcVoucher::where('nfc_type', $request->input('nfc_type'))->first();

        if ($nfcVoucher != null) {

            return response()->json([
                'state' => true,
                'message' => $nfcVoucher
            ]);
        } else {
            return response()->json([
                'state' => false,
                'message' => 'No se encontro el nfc'
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param NfcVoucher $nfcVoucher
     * @return Response
     */

    public function show(NfcVoucher $nfcVoucher)

    {

        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param NfcVoucher $nfcVoucher
     * @return Response
     */

    public function edit(NfcVoucher $nfcVoucher)

    {

        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NfcVoucher $nfcVoucher
     * @return Response
     */

    public function collection()

    {

        return Excel::download(new NfcVoucherExportsCollection, 'Nfc_Voucher.xlsx');

    }

    public function view()

    {

        // return Excel::download(new NfcVoucherExportsView, 'Nfc_Voucher.xlsx');


        $nfc_vouchers = NfcVoucher::latest()->get();// Obtén los datos de la tabla desde tu controlador o modelo


        return Excel::download(new NfcVoucherExportsView($nfc_vouchers), 'nfc_vouchers.xlsx');

    }

}

