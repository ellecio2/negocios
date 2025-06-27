<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\ProductRequest;
use App\Models\AttributeBrands;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Wishlist;
use App\Notifications\ShopProductNotification;
use App\Services\ProductFlashDealService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use Artisan;
use Combinations;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\PersonalAccessToken;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;

    protected $token;

    public function __construct(
        ProductService          $productService,
        ProductTaxService       $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService     $productStockService
    )
    {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->token = getAccessToken();
    }

    public function index(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->where('auction_product', 0)->where('wholesale_product', 0)->orderBy('created_at', 'desc');
        $shop = auth()->user()->shop;
        $packageInvalidDate = $shop->updated_at;

        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);
        return view('seller.products.index', compact('products', 'search', 'packageInvalidDate'));
    }

    public function edit(Request $request, $id)
    {
        $token = $request->bearerToken();

        if ($token) {
            // buscas el token de sanctum en la base de datos
            $tokenModel = PersonalAccessToken::findToken($token);
            if ($tokenModel) {
                // obtienes el usuario correspondiente al token
                $user = $tokenModel->tokenable;
                // autenticas al usuario
                Auth::guard('web')->login($user);
            }
        }

        $product = Product::findOrFail($id);
        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $tags = json_decode($product->tags, true);
        $tagsAsString = $tags ? implode(', ', $tags) : '';
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $brands = Brand::latest()->get();
        //return view('seller.products.edit', compact('product', 'categories', 'tags', 'lang'));
        //return view('seller.products.create', compact('categories', 'brands'));
        return view('seller.products.edit', compact('product', 'categories', 'tagsAsString', 'lang', 'brands'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->productService->update($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]), $product);

        //Product Stock
        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        $request->merge(['product_id' => $product->id]);

        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id'
        ]), $product);

        //VAT & Tax
        $product->taxes()->where('tax_id', 4)->update([
            'tax' => $request->seller_commission
        ]);

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang',
                'product_id'
            ]),
            $request->only([
                'name',
                'unit',
                'description'
            ])
        );
        //flash(translate('Product has been updated successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        //return back();
        $response = array('state' => true, 'msg' => 'Producto Actualizado Exitosamente');
        return response()->json($response);
    }

    public function store(ProductRequest $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return redirect()->route('seller.products');
            }
        }
        $product = $this->productService->store($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]));

        $request->merge(['product_id' => $product->id]);


        //Comissions
        ProductTax::create([
            'product_id' => $product->id,
            'tax_id' => 4,
            'tax' => $request->seller_commission,
        ]);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id'
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang',
            'name',
            'unit',
            'description',
            'product_id'
        ]));

        if (get_setting('product_approve_by_admin') == 0) {
            $users = User::findMany([auth()->user()->id, User::where('user_type', 'admin')->first()->id]);
            Notification::send($users, new ShopProductNotification('physical', $product));
        }

        //flash(translate('Producto Creado Exitosamente'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');


        $response = array('state' => true, 'msg' => 'Producto Creado Exitosamente');
        return response()->json($response);
        //return redirect()->route('seller.products');
    }

    public function create(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            // buscas el token de sanctum en la base de datos
            $tokenModel = PersonalAccessToken::findToken($token);
            if ($tokenModel) {
                // obtienes el usuario correspondiente al token
                $user = $tokenModel->tokenable;
                // autenticas al usuario
                Auth::guard('web')->login($user);
            }
        }

        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        $brands = Brand::latest()->get();
        return view('seller.products.create', compact('categories', 'brands'));
    }

    public function add_brands(Request $request)
    {
        $all_brands_value = AttributeBrands::where('parent_id', $request->parent_id)->get();
        $html = '';
        $html .= '<option value="">Seleccionar Marca</option>';
        foreach ($all_brands_value as $row) {
            $html .= '<option value="' . $row->parent_id . '">' . $row->name . '</option>';
        }
        echo json_encode($html);
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if (addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (!seller_package_validity_check()) {
                return 2;
            }
        }
        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->seller_featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function duplicate($id)
    {
        $product = Product::find($id);
        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }
        //Product
        $product_new = $this->productService->product_duplicate_store($product);
        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);
        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);
        flash(translate('Product has been duplicated successfully'))->success();
        return redirect()->route('seller.products');
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }
        return 1;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }
        $product->product_translations()->delete();
        $product->stocks()->delete();
        $product->taxes()->delete();
        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Wishlist::where('product_id', $id)->delete();
            flash(translate('Product has been deleted successfully'))->success();
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function oem($oem)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/buy/browse/v1/item_summary/search?q=$oem&limit=1");

            if ($response->successful()) {
                $data = $response->json();
                $title = $data['itemSummaries'][0]['title'];
                //$title_clear = $this->limpiarTexto($title);
                $translationResponse = $this->translateTitle($title);
                if ($translationResponse['success']) {
                    $translatedTitle = $translationResponse['translations']['translations'][0]['translatedText'];
                    $data['itemSummaries'][0]['title'] = $translatedTitle;

                    return response()->json([
                        'success' => true,
                        'data' => $data['itemSummaries'] ?? [],
                    ]);
                }


                /*return response()->json([
                    'success' => false,
                    'message' => 'Error al traducir el título',
                    'error' => 'No se encontraron datos'
                ], 200);*/
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos',
                'error' => $response->json()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'El producto no se encontró. Por favor, verifique su código OEM o genere uno nuevo utilizando el código proporcionado',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    private function translateTitle($title)
    {
        // Limpiar el título de caracteres especiales y etiquetas HTML
        $title = strip_tags($title); // Elimina etiquetas HTML
        $title = preg_replace('/[^\p{L}\p{N}\s]/u', '', $title); // Elimina caracteres especiales, permitiendo letras y números
        $title = preg_replace('/\s+/', ' ', $title); // Reemplaza múltiples espacios por uno solo
        $title = trim($title); // Elimina espacios en blanco al principio y al final

        // Verificar si el título está vacío después de la limpieza
        if (empty($title)) {
            return [
                'success' => false,
                'message' => 'El título está vacío después de la limpieza.',
            ];
        }

        // Realizar la solicitud a la API
        $response = Http::withToken($this->token)
            ->post('https://api.ebay.com/commerce/translation/v1_beta/translate', [
                'from' => 'en',
                'to' => 'es',
                'text' => [$title],
                'translationContext' => 'ITEM_TITLE'
            ]);

        if ($response->successful()) {
            return ['success' => true, 'translations' => $response->json()];
        } else {
            return [
                'success' => false,
                'message' => 'Error en la traducción',
                'error' => $response->json()
            ];
        }
    }

    /*private function translateTitle($title)
    {
        $response = Http::withToken($this->token)
            ->post('https://api.ebay.com/commerce/translation/v1_beta/translate', [
                'from' => 'en',
                'to' => 'es',
                'text' => [$title],
                'translationContext' => 'ITEM_TITLE'
            ]);
        if ($response->successful()) {
            return ['success' => true, 'translations' => $response->json()];
        } else {
            return [
                'success' => false,
                'message' => 'Error en la traducción',
                'error' => $response->json()
            ];
        }
    }*/

    private function limpiarTexto($texto)
    {
        return preg_replace('/[^a-zA-Z0-9\s]/u', '', $texto);
    }
}
