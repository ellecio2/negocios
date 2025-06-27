<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\ProductStock;
use App\Models\Shop;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Utility\NotificationUtility;
use App\Utility\SmsUtility;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mpdf\Mpdf;

//use Dompdf\Options;

//use PDF;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->payment_status == null) {
            $request->payment_status = 'paid';
        }
        $delivery_status = null;
        $sort_search = null;
        $date_range = null;
        $orders = Order::where('seller_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->distinct();
        if ($request->payment_status != null) {
            if ($request->payment_status != 'all') {
                $orders = $orders->where('payment_status', $request->payment_status);
            }
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $orders = $orders->where('created_at', '>=', "$date_range1[0] 00:00:00");
            $orders = $orders->where('created_at', '<=', "$date_range1[1] 23:59:59");
        }

        $today = Carbon::today()->toDateString();
        $orders_dia = Order::where('seller_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->distinct()
            ->where('created_at', '>=', "$today 00:00:00")
            ->where('created_at', '<=', "$today 23:59:59");

        $today_orders = $orders_dia->get();

        $today = Carbon::today();
        $currentMonth = $today->month;
        $currentYear = $today->year;

        $orders_mes = Order::where('seller_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->distinct()
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear);

        $current_month_orders = $orders_mes->get();

        $orders1 = $orders->get();
        $orders = $orders->paginate(15);

        //dd($orders);
        /*foreach ($orders as $key => $value) {
            $order = Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }*/

        //$orderDetails = OrderDetail::where('seller_id', Auth::user()->id)->get();

        return view('seller.orders.index', compact('orders', 'orders1', 'today_orders', 'current_month_orders', 'payment_status', 'delivery_status', 'sort_search', 'date_range'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = collect();
        if (!is_null($order_shipping_address)) {
            $delivery_boys = User::where('city', $order_shipping_address->city)
                ->where('user_type', 'delivery_boy')
                ->get();
        }
        $order->viewed = 1;
        $order->save();
        return view('seller.orders.show', compact('order', 'delivery_boys'));
    }

    // Update Delivery Status
    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();
        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }
        foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();
            if ($request->status == 'cancelled') {
                $variant = $orderDetail->variation;
                if ($orderDetail->variation == null) {
                    $variant = '';
                }
                $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                    ->where('variant', $variant)
                    ->first();
                if ($product_stock != null) {
                    $product_stock->qty += $orderDetail->quantity;
                    $product_stock->save();
                }
            }
        }
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (Exception $e) {
            }
        }
        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";
            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;
            NotificationUtility::sendFirebaseNotification($request);
        }
        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }
        return 1;
    }

    // Update Payment Status
    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();
        foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }
        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();
        if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
            calculateCommissionAffilationClubPoint($order);
        }
        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";
            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;
            NotificationUtility::sendFirebaseNotification($request);
        }
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (Exception $e) {
            }
        }
        return 1;
    }

    public function generatePDF(Request $request)
{
    try {
        $ordersIds = $request->input('orders');
        $seller = Shop::where('user_id', Auth::user()->id)->first();
        
        if (!$seller) {
            throw new Exception('Vendedor no encontrado');
        }

        $total = 0;
        $total_venta = 0;
        $tax = 0;
        $descuento = 0;
        $shipping_cost = 0;
        $orderDetails = collect();
        $processedOrderDetailIds = collect();
        $processedOrderIdsForShipping = collect();

        // Get orders based on the request type
        if ($ordersIds == 'dia') {
            $today = Carbon::today();
            $orders = Order::where('seller_id', Auth::user()->id)
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $today)
                ->get();
            $fecha = $today;
        } elseif ($ordersIds == 'mes') {
            $today = Carbon::today();
            $orders = Order::where('seller_id', Auth::user()->id)
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $today->year)
                ->whereMonth('created_at', $today->month)
                ->get();
            $fecha = $today;
        } else {
            $orders = Order::whereIn('id', (array)$ordersIds)
                ->where('seller_id', Auth::user()->id)
                ->where('payment_status', 'paid')
                ->get();
            $fecha = $request->input('fecha', now());
        }

        // Process orders
        foreach ($orders as $order) {
            $total_venta += $order->grand_total;

            foreach ($order->orderDetails as $orderDetail) {
                if (!$processedOrderDetailIds->contains($orderDetail->id)) {
                    $total += $orderDetail->price;
                    
                    if (!$processedOrderIdsForShipping->contains($order->id)) {
                        $shipping_cost += $orderDetail->shipping_cost;
                        $processedOrderIdsForShipping->push($order->id);
                    }
                    
                    $tax += $orderDetail->tax;
                    $descuento += $orderDetail->cupon_discount;
                    $orderDetails->push($orderDetail);
                    $processedOrderDetailIds->push($orderDetail->id);
                }
            }
        }

        // Prepare data for PDF
        $data = $orders->map(function ($order) {
            return [
                'code' => $order->code,
                'price' => $order->grand_total,
                'date' => $order->created_at->format('Y-m-d'),
            ];
        });

        // Debug information
        \Log::info('PDF Generation Data', [
            'orders_count' => $orders->count(),
            'data_count' => $data->count(),
            'total' => $total,
            'shipping_cost' => $shipping_cost,
            'tax' => $tax
        ]);

        // Generate PDF
        $mpdf = new Mpdf([
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $html = view('pdf.order', compact(
            'data',
            'total',
            'seller',
            'fecha',
            'descuento',
            'tax',
            'total_venta',
            'shipping_cost'
        ))->render();

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('orders_report.pdf', 'I'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="orders_report.pdf"');

    } catch (Exception $e) {
        \Log::error('PDF Generation Error: ' . $e->getMessage());
        return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
    }
}
}
