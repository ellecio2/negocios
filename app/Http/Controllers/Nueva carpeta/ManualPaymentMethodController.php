<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualPaymentMethod;
use App\Models\Order;

class ManualPaymentMethodController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_manual_payment_methods'])->only('index');
        $this->middleware(['permission:add_manual_payment_method'])->only('create');
        $this->middleware(['permission:edit_manual_payment_method'])->only('edit');
        $this->middleware(['permission:delete_manual_payment_method'])->only('destroy');   
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manual_payment_methods = ManualPaymentMethod::all();
        return view('manual_payment_methods.index', compact('manual_payment_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manual_payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manual_payment_method = new ManualPaymentMethod;
        $manual_payment_method->type = $request->type;
        $manual_payment_method->photo = $request->photo;
        $manual_payment_method->heading = $request->heading;
        $manual_payment_method->description = $request->description;

        if($request->type == 'bank_payment')
        {
            $banks_informations = array();
            for ($i=0; $i < count($request->bank_name); $i++) {
                $item = array();
                $item['bank_name'] = $request->bank_name[$i];
                $item['account_name'] = $request->account_name[$i];
                $item['account_number'] = $request->account_number[$i];
                $item['routing_number'] = $request->routing_number[$i];
                array_push($banks_informations, $item);
            }

            $manual_payment_method->bank_info = json_encode($banks_informations);
        }

        $manual_payment_method->save();
        flash(translate('Method has been inserted successfully'))->success();
        return redirect()->route('manual_payment_methods.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manual_payment_method = ManualPaymentMethod::findOrFail(decrypt($id));
        return view('manual_payment_methods.edit', compact('manual_payment_method'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $manual_payment_method = ManualPaymentMethod::findOrFail($id);
        $manual_payment_method->type = $request->type;
        $manual_payment_method->heading = $request->heading;
        $manual_payment_method->description = $request->description;

        if($request->type == 'bank_payment')
        {
            $banks_informations = array();
            for ($i=0; $i < count($request->bank_name); $i++) {
                $item = array();
                $item['bank_name'] = $request->bank_name[$i];
                $item['account_name'] = $request->account_name[$i];
                $item['account_number'] = $request->account_number[$i];
                $item['routing_number'] = $request->routing_number[$i];
                array_push($banks_informations, $item);
            }

            $manual_payment_method->bank_info = json_encode($banks_informations);
        }
        $manual_payment_method->photo = $request->photo;
        $manual_payment_method->save();
        flash( translate('Method has been updated successfully'))->success();
        return redirect()->route('manual_payment_methods.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ManualPaymentMethod::destroy($id)){
            flash(translate('Method has been deleted successfully'))->success();
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
        return redirect()->route('manual_payment_methods.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_payment_modal(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order != null){
            return view('frontend.user.payment_modal', compact('order'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit_offline_payment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        if($request->name != null && $request->amount != null && $request->trx_id != null){
            $data['name']   = $request->name;
            $data['amount'] = $request->amount;
            $data['trx_id'] = $request->trx_id;
            $data['photo']  = $request->photo;
        }
        else {
            flash(translate('Please fill all the fields'))->warning();
            return back();
        }

        $order->manual_payment_data = json_encode($data);
        $order->payment_type = $request->payment_option;
        $order->payment_status = 'Submitted';
        $order->manual_payment = 1;

        $order->save();

        flash(translate('Your payment data has been submitted successfully'))->success();
        return redirect()->route('home');
    }

    public function offline_recharge_modal(Request $request)
    {
        return view('frontend.user.wallet.offline_recharge_modal');
    }

    public function offline_customer_package_purchase_modal(Request $request)
    {
        $package_id =  $request->package_id;
        return view('manual_payment_methods.frontend.offline_customer_package_purchase_modal', compact('package_id'));
    }

    public function offline_seller_package_purchase_modal(Request $request)
    {
        $package_id =  $request->package_id;
        return view('manual_payment_methods.frontend.offline_seller_package_purchase_modal', compact('package_id'));
    }
}
