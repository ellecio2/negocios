<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Requests\SellerRegistrationRequest;
use App\Http\Resources\V2\Seller\CommissionHistoryResource;
use App\Http\Resources\V2\Seller\ProductCollection;
use App\Http\Resources\V2\Seller\ProductMiniCollection;
use App\Http\Resources\V2\Seller\SellerPaymentResource;
use App\Http\Resources\V2\ShopCollection;
use App\Http\Resources\V2\ShopDetailsCollection;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\CommissionHistory;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Models\BusinessWorkingHours;
use App\Models\BusinessDateNonWorking;
use App\Notifications\AppEmailVerificationNotification;
use App\Utility\SearchUtility;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $shop_query = Shop::query();
        if ($request->name != null && $request->name != "") {
            $shop_query->where("name", 'like', "%{$request->name}%");
            SearchUtility::store($request->name);
        }
        return new ShopCollection($shop_query->whereIn('user_id', verified_sellers_id())->paginate(10));
        //remove this , this is for testing
        //return new ShopCollection($shop_query->paginate(10));
    }

    /**
     * Get business working hours for a specific shop
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getShopBusinessHours(Request $request)
    // {
    //     // Validate shop_id is provided
    //     if (!$request->has('shop_id')) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 400,
    //             'message' => 'Shop ID is required'
    //         ], 400);
    //     }

    //     $shopId = $request->shop_id;

    //     // Check if shop exists
    //     $shop = \App\Models\Shop::find($shopId);
    //     if (!$shop) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 404,
    //             'message' => 'Shop not found'
    //         ], 404);
    //     }

    //     // Get working hours sorted by day of week
    //     $businessWorkingHours = \App\Models\BusinessWorkingHours::where('shop_id', $shopId)
    //         ->get()
    //         ->sortBy(function ($workingHours) {
    //             return array_search($workingHours->dia_semana, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
    //         })
    //         ->values();

    //     // Get non-working dates sorted by date
    //     $businessDateNonWorkings = \App\Models\BusinessDateNonWorking::where('shop_id', $shopId)
    //         ->orderBy('fecha_no_laborable', 'asc')
    //         ->get();

    //     // Format the data for API response
    //     $formattedWorkingHours = $businessWorkingHours->map(function ($hours) {
    //         return [
    //             'day' => $hours->dia_semana,
    //             'day_translated' => $this->translateDayName($hours->dia_semana),
    //             'is_open' => (bool)$hours->laborable, // Asumiendo que 'laborable' es el campo que indica si está abierto
    //             'opening_time' => $hours->hora_inicio, // Cambiado de hora_desde a hora_inicio
    //             'closing_time' => $hours->hora_fin   // Cambiado de hora_hasta a hora_fin
    //         ];
    //     });

    //     $formattedNonWorkingDates = $businessDateNonWorkings->map(function ($date) {
    //         return [
    //             'date' => $date->fecha_no_laborable,
    //             'reason' => $date->motivo
    //         ];
    //     });

    //     // Add shop basic info
    //     $shopInfo = [
    //         'id' => $shop->id,
    //         'name' => $shop->name,
    //         'address' => $shop->address,
    //         'phone' => $shop->phone,
    //         'logo' => $shop->logo ? uploaded_asset($shop->logo) : null
    //     ];

    //     return response()->json([
    //         'success' => true,
    //         'status' => 200,
    //         'data' => [
    //             'shop' => $shopInfo,
    //             'working_hours' => $formattedWorkingHours,
    //             'non_working_dates' => $formattedNonWorkingDates
    //         ]
    //     ]);
    // }

public function getShopBusinessHours(Request $request)
{
    return response()->json([
        'success' => true,
        'status' => 200,
        'message' => 'Business hours endpoint is working!'
    ]);
}


    /**
     * Helper method to translate day names from English to Spanish
     * 
     * @param string $dayName
     * @return string
     */
    private function translateDayName($dayName)
    {
        $translations = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        return $translations[$dayName] ?? $dayName;
    }

    public function store(SellerRegistrationRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);
        if ($user->save()) {
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', str_replace("/", " ", $request->shop_name));
            $shop->save();
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
            } else {
                try {
                    $user->notify(new AppEmailVerificationNotification());
                } catch (\Exception $e) {
                }
            }
            $authController = new AuthController();
            return $authController->loginSuccess($user);
        }
        return $this->failed(translate('Something Wenr Wrong!'));
    }


    public function update(Request $request)
    {
        $shop = Shop::where('user_id', auth()->user()->id)->first();
        $successMessage = 'Shop info updated successfully';
        $failedMessage = 'Shop info updated failed';
        if ($request->has('name') && $request->has('address')) {
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
            $shop->slug = preg_replace('/\s+/', '-', $request->name) . '-' . $shop->id;
            $shop->meta_title = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo = $request->logo;
        }
        if ($request->has('delivery_pickup_longitude') && $request->has('delivery_pickup_latitude')) {
            $shop->delivery_pickup_longitude = $request->delivery_pickup_longitude;
            $shop->delivery_pickup_latitude = $request->delivery_pickup_latitude;
        } elseif (
            $request->has('facebook') ||
            $request->has('google') ||
            $request->has('twitter') ||
            $request->has('youtube') ||
            $request->has('instagram')
        ) {
            $shop->facebook = $request->facebook;
            $shop->instagram = $request->instagram;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        } elseif (
            $request->has('cash_on_delivery_status') ||
            $request->has('bank_payment_status') ||
            $request->has('bank_name') ||
            $request->has('bank_acc_name') ||
            $request->has('bank_acc_no') ||
            $request->has('bank_routing_no')
        ) {
            $shop->cash_on_delivery_status = $request->cash_on_delivery_status;
            $shop->bank_payment_status = $request->bank_payment_status;
            $shop->bank_name = $request->bank_name;
            $shop->bank_acc_name = $request->bank_acc_name;
            $shop->bank_acc_no = $request->bank_acc_no;
            $shop->bank_routing_no = $request->bank_routing_no;
            $successMessage = 'Payment info updated successfully';
        } else {
            $shop->sliders = $request->sliders;
        }
        if ($shop->save()) {
            return $this->success(translate($successMessage));
        }
        return $this->failed(translate($failedMessage));
    }
    public function sales_stat()
    {
        $data = Order::where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('seller_id', '=', auth()->user()->id)
            ->where('delivery_status', '=', 'delivered')
            ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%b-%d') as date"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->get()->toArray();
        //$array_date = [];
        $sales_array = [];
        for ($i = 1; $i < 8; $i++) {
            $new_date = date("M-d", strtotime(($i - 1) . " days ago"));
            //$array_date[] = date("M-d", strtotime($i." days ago"));
            $sales_array[$i]['date'] = $new_date;
            $sales_array[$i]['total'] = 0;
            if (!empty($data)) {
                $key = array_search($new_date, array_column($data, 'date'));
                if (is_numeric($key)) {
                    $sales_array[$i]['total'] = $data[$key]['total'];
                }
            }
        }
        return Response()->json($sales_array);
    }
    public function category_wise_products()
    {
        $categories = Category::with('products')->get();

        $new_array = [];
        foreach ($categories as $category) {
            if (count($category->products->where('user_id', auth()->id())) > 0) {
                $category_name = $category->banner ? $category->getTranslation('name') : $category->topLevelCategory()->getTranslation('name');

                if (array_key_exists($category_name, $new_array)) {
                    $new_array[$category_name]['cnt_product'] += count($category->products->where('user_id', auth()->id()));
                } else {
                    $new_array[$category_name] = [
                        'name' => $category_name,
                        'banner' => $category->banner ? uploaded_asset($category->banner) : uploaded_asset($category->topLevelCategory()->banner),
                        'cnt_product' => count($category->products->where('user_id', auth()->id()))
                    ];
                }
            }
        }
        return Response()->json(array_values($new_array));
    }
    public function top_12_products()
    {
        $products = filter_products(Product::where('user_id', auth()->user()->id)
            ->orderBy('num_of_sale', 'desc'))
            ->limit(12)
            ->get();
        return new ProductCollection($products);
    }
    public function info()
    {
        return new ShopDetailsCollection(auth()->user()->shop);
    }
    public function pacakge()
    {
        $shop = auth()->user()->shop;
        return response()->json([
            'result' => true,
            'id' => $shop->id,
            'package_name' => $shop->seller_package->name,
            'package_img' => uploaded_asset($shop->seller_package->logo)
        ]);
    }
    public function profile()
    {
        $user = auth()->user();
        return response()->json([
            'result' => true,
            'id' => $user->id,
            'type' => $user->user_type,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_original' => uploaded_asset($user->avatar_original),
            'phone' => $user->phone
        ]);
    }
    public function payment_histories()
    {
        $payments = Payment::where('seller_id', auth()->user()->id)->paginate(10);
        return SellerPaymentResource::collection($payments);
    }
    public function collection_histories()
    {
        $commission_history = CommissionHistory::where('seller_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return CommissionHistoryResource::collection($commission_history);
    }
    public function getVerifyForm()
    {
        $forms = BusinessSetting::where('type', 'verification_form')->first();
        return response()->json(json_decode($forms->value));
    }
    public function store_verify_info(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $shop = auth()->user()->shop;
        $shop->verification_info = json_encode($data);
        if ($shop->save()) {
            return $this->success(translate('Your shop verification request has been submitted successfully!'));
        }
        return $this->failed(translate('Something Wenr Wrong!'));
    }
}
