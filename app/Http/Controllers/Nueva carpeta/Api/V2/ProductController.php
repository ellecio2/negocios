<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\FlashDealCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Models\BusinessSetting;
use App\Models\Color;
use App\Models\FlashDeal;
use App\Models\Product;
use App\Models\Shop;
use App\Utility\CategoryUtility;
use App\Utility\SearchUtility;
use Cache;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = null;

        if ($this->hasCategoryId($request) && $this->hasBrandId($request)) {
            $products = Product::latest()
                ->with(['category', 'brand', 'stocks'])
                ->where('category_id', $request->input('category_id'))
                ->where('brand_id', $request->input('brand_id'))
                ->select([
                    'id',
                    'name',
                    'thumbnail_img',
                    'photos',
                    'unit_price',
                    'purchase_price',
                    'variant_product',
                    'description',
                    'attributes',
                    'discount',
                    'discount_type',
                    'discount_start_date',
                    'discount_end_date',
                    'rating',
                    'num_of_sale',
                    'slug',
                    'category_id',
                    'brand_id',
                    'current_stock',
                    'unit',
                    'min_qty',
                    'low_stock_quantity',
                    'created_at'
                ])
                ->paginate(10);
        } elseif ($this->hasCategoryId($request)) {
            $products = Product::latest()
                ->with(['category', 'brand', 'stocks'])
                ->where('category_id', $request->input('category_id'))
                ->select([
                    'id',
                    'name',
                    'thumbnail_img',
                    'photos',
                    'unit_price',
                    'purchase_price',
                    'variant_product',
                    'description',
                    'attributes',
                    'discount',
                    'discount_type',
                    'discount_start_date',
                    'discount_end_date',
                    'rating',
                    'num_of_sale',
                    'slug',
                    'category_id',
                    'brand_id',
                    'current_stock',
                    'unit',
                    'min_qty',
                    'low_stock_quantity',
                    'created_at'
                ])
                ->paginate(10);
        } elseif ($this->hasBrandId($request)) {
            $products = Product::latest()
                ->with(['category', 'brand', 'stocks'])
                ->where('brand_id', $request->input('brand_id'))
                ->select([
                    'id',
                    'name',
                    'thumbnail_img',
                    'photos',
                    'unit_price',
                    'purchase_price',
                    'variant_product',
                    'description',
                    'attributes',
                    'discount',
                    'discount_type',
                    'discount_start_date',
                    'discount_end_date',
                    'rating',
                    'num_of_sale',
                    'slug',
                    'category_id',
                    'brand_id',
                    'current_stock',
                    'unit',
                    'min_qty',
                    'low_stock_quantity',
                    'created_at'
                ])
                ->paginate(10);
        } else {
            $products = Product::latest()
                ->with(['category', 'brand', 'stocks'])
                ->select([
                    'id',
                    'name',
                    'thumbnail_img',
                    'photos',
                    'unit_price',
                    'purchase_price',
                    'variant_product',
                    'description',
                    'attributes',
                    'discount',
                    'discount_type',
                    'discount_start_date',
                    'discount_end_date',
                    'rating',
                    'num_of_sale',
                    'slug',
                    'category_id',
                    'brand_id',
                    'current_stock',
                    'unit',
                    'min_qty',
                    'low_stock_quantity',
                    'created_at'
                ])
                ->paginate(10);
        }

        return response()->json([
            'success' => true,
            'data' => $products->map(function ($product) {
                $discountPrice = 0;
                $calculatedPrice = $product->unit_price;

                // Calculate discount
                if ($product->discount_type == 'percent') {
                    $discountPrice = $product->unit_price * ($product->discount / 100);
                    $calculatedPrice = $product->unit_price - $discountPrice;
                } elseif ($product->discount_type == 'amount') {
                    $discountPrice = $product->discount;
                    $calculatedPrice = $product->unit_price - $discountPrice;
                }

                // Get stock information
                $stock = $product->stocks->first();
                $stockQuantity = $stock ? $stock->qty : $product->current_stock;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => isset($product->code) ? $product->code : '', // Mantener campo code
                    'thumbnail_image' => uploaded_asset($product->thumbnail_img),
                    'photos' => array_map(function ($photo) {
                        return uploaded_asset($photo);
                    }, explode(',', $product->photos)),
                    'base_price' => (float) $product->unit_price,
                    'base_price_formatted' => home_base_price($product),
                    'discounted_price' => (float) $calculatedPrice,
                    'discounted_price_formatted' => home_discounted_base_price($product),
                    'discount' => $product->discount,
                    'discount_type' => $product->discount_type,
                    'discount_percentage' => $product->discount_type == 'percent' ? $product->discount : 0,
                    'has_discount' => $product->discount > 0,
                    // Mantener la nomenclatura original para compatibilidad
                    'stroked_price' => home_base_price($product),
                    'main_price' => home_discounted_base_price($product),
                    'rating' => (float) $product->rating,
                    'rating_count' => 0,
                    'sales' => (int) $product->num_of_sale,
                    'is_wholesale' => false, // Mantener campo original
                    'stock' => (int) $stockQuantity,
                    'stock_id' => $stock ? $stock->id : null, // Mantener campo original
                    'slug' => $product->slug,
                    'category' => [
                        'id' => $product->category_id,
                        'name' => $product->category ? $product->category->name : '',
                        'slug' => $product->category ? $product->category->slug : ''
                    ],
                    'brand' => [
                        'id' => $product->brand_id,
                        'name' => $product->brand ? $product->brand->name : '',
                        'logo' => $product->brand ? uploaded_asset($product->brand->logo) : null
                    ],
                    'stock_details' => [
                        'quantity' => (int) $stockQuantity,
                        'unit' => $product->unit,
                        'min_qty' => (int) $product->min_qty,
                        'low_stock_quantity' => (int) $product->low_stock_quantity,
                        'in_stock' => $stockQuantity > 0
                    ],
                    'is_variant' => (bool) $product->variant_product,
                    'description' => $product->description,
                    'attributes' => json_decode($product->attributes),
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'links' => [
                        'details' => route('product', $product->slug)
                    ],
                ];
            }),
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $products->currentPage(),
                'from' => $products->firstItem(),
                'last_page' => $products->lastPage(),
                'links' => $products->linkCollection()->toArray(),
                'path' => $products->path(),
                'per_page' => $products->perPage(),
                'to' => $products->lastItem(),
                'total' => $products->total()
            ],
            'status' => 200
        ]);
    }
    // public function index(Request $request)
    // {

    //     $products = null;

    //     if ($this->hasCategoryId($request) && $this->hasBrandId($request)) {
    //         $products = Product::latest()
    //             ->where('category_id', $request->input('category_id'))
    //             ->where('brand_id', $request->input('brand_id'))
    //             ->paginate(10);
    //     } elseif ($this->hasCategoryId($request)) {
    //         $products = Product::latest()
    //             ->where('category_id', $request->input('category_id'))
    //             ->paginate(10);
    //     } elseif ($this->hasBrandId($request)) {
    //         $products = Product::latest()
    //             ->where('brand_id', $request->input('brand_id'))
    //             ->paginate(10);
    //     } else {
    //         $products = Product::latest()->paginate(10);
    //     }

    //     return new ProductMiniCollection($products);
    // }

    private function hasCategoryId($request)
    {
        return $request->has('category_id') && !empty($request->input('category_id'));
    }


    private function hasBrandId($request)
    {
        return $request->has('brand_id') && !empty($request->input('brand_id'));
    }

    public function show($id)
    {
        return new ProductDetailCollection(Product::where('id', $id)->with('stocks')->get());
    }

    public function getPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;
        $vendor_commission = BusinessSetting::where('type', 'vendor_commission')->first()->value;
        $quantity = 1;
        if ($request->has('quantity') && $request->quantity != null) {
            $quantity = $request->quantity;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }
        $product_stock = $product->stocks->first();
        $price = $product->unit_price;
        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $quantity)->where('max_qty', '>=', $quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }
        $stock_qty = $product_stock->qty;
        $stock_txt = $product_stock->qty;
        $max_limit = $product_stock->qty;
        if ($stock_qty >= 1 && $product->min_qty <= $stock_qty) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }
        //Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($stock_qty >= 1 && $product->min_qty < $stock_qty) {
                $stock_txt = translate('In Stock');
            } else {
                $stock_txt = translate('Out Of Stock');
            }
        }

        $price -= $price * ($vendor_commission / 100);

        //discount calculation
        $discount_applicable = false;
        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        // taxes
        $price += $price * (config('app.itbis') / 100);
        return response()->json(
            [
                'result' => true,
                'data' => [
                    'price' => single_price($price * $quantity),
                    'stock' => $stock_qty,
                    'stock_txt' => $stock_txt,
                    'digital' => $product->digital,
                    'variant' => $str,
                    'variation' => $str,
                    'max_limit' => $max_limit,
                    'in_stock' => $in_stock,
                    'image' => $product_stock->image == null ? "" : uploaded_asset($product_stock->image)
                ]
            ]
        );
    }

    public function seller($id, Request $request)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::where('added_by', 'seller')->where('user_id', $shop->user_id);
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection($products->latest()->paginate(10));
    }

    public function category($id, Request $request)
    {
        $category_ids = CategoryUtility::children_ids($id);
        $category_ids[] = $id;
        $products = Product::whereIn('category_id', $category_ids)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function brand($id, Request $request)
    {
        $products = Product::where('brand_id', $id)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function todaysDeal()
{
    // return Cache::remember('app.todays_deal', 86400, function () {
    $products = Product::where('todays_deal', 1)->physical();
    $filteredProducts = filter_products($products)->limit(20)->latest()->get();
    
    // Obtener la colección estándar
    $productCollection = new ProductMiniCollection($filteredProducts);
    
    // Modificar la respuesta para incluir el slug
    $data = $productCollection->toResponse(request())->getData();
    
    // Agregar el slug a cada producto
    foreach ($data->data as $key => $product) {
        $currentProduct = $filteredProducts[$key];
        $data->data[$key]->slug = $currentProduct->slug;
    }
    
    return response()->json($data);
    // });
}

    public function flashDeal()
    {
        return Cache::remember('app.flash_deals', 86400, function () {
            $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
            return new FlashDealCollection($flash_deals);
        });
    }

    // public function featured()
    // {
    //     $products = Product::where('featured', 1)->physical();
    //     return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    // }

    public function featured()
    {
        $products = Product::where('featured', 1)
            ->physical()
            ->with(['category', 'brand', 'stocks']) // Add necessary relationships
            ->select([
                'id',
                'name',
                'thumbnail_img',
                'photos',
                'unit_price',
                'purchase_price',
                'variant_product',
                'description',
                'attributes',
                'discount',
                'discount_type',
                'discount_start_date',
                'discount_end_date',
                'rating',
                // 'rating_count', // Esta columna no existe, la removemos
                'num_of_sale',
                'slug',
                'category_id',
                'brand_id',
                'current_stock',
                'unit',
                'min_qty',
                'low_stock_quantity',
                'created_at'
            ])
            ->latest()
            ->paginate(24);
    
        return response()->json([
            'success' => true,
            'data' => $products->map(function ($product) {
                $discountPrice = 0;
                $calculatedPrice = $product->unit_price;
                
                // Calculate discount
                if ($product->discount_type == 'percent') {
                    $discountPrice = $product->unit_price * ($product->discount / 100);
                    $calculatedPrice = $product->unit_price - $discountPrice;
                } elseif ($product->discount_type == 'amount') {
                    $discountPrice = $product->discount;
                    $calculatedPrice = $product->unit_price - $discountPrice;
                }
                
                // Get stock information
                $stock = $product->stocks->first();
                $stockQuantity = $stock ? $stock->qty : $product->current_stock;
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'thumbnail_image' => uploaded_asset($product->thumbnail_img),
                    'photos' => array_map(function($photo) { 
                        return uploaded_asset($photo); 
                    }, explode(',', $product->photos)),
                    'base_price' => (double) $product->unit_price,
                    'base_price_formatted' => home_base_price($product),
                    'discounted_price' => (double) $calculatedPrice,
                    'discounted_price_formatted' => home_discounted_base_price($product),
                    'discount' => $product->discount,
                    'discount_type' => $product->discount_type,
                    'discount_percentage' => $product->discount_type == 'percent' ? $product->discount : 0,
                    'has_discount' => $product->discount > 0,
                    'rating' => (double) $product->rating,
                    'rating_count' => 0, // Ya que no existe la columna, ponemos un valor por defecto
                    'sales' => (int) $product->num_of_sale,
                    'slug' => $product->slug,
                    'category' => [
                        'id' => $product->category_id,
                        'name' => $product->category ? $product->category->name : '',
                        'slug' => $product->category ? $product->category->slug : ''
                    ],
                    'brand' => [
                        'id' => $product->brand_id,
                        'name' => $product->brand ? $product->brand->name : '',
                        'logo' => $product->brand ? uploaded_asset($product->brand->logo) : null
                    ],
                    'stock' => [
                        'quantity' => (int) $stockQuantity,
                        'unit' => $product->unit,
                        'min_qty' => (int) $product->min_qty,
                        'low_stock_quantity' => (int) $product->low_stock_quantity,
                        'in_stock' => $stockQuantity > 0
                    ],
                    'is_variant' => (bool) $product->variant_product,
                    'description' => $product->description,
                    'attributes' => json_decode($product->attributes),
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'links' => [
                        'details' => route('product', $product->slug)
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
    }
    public function digital()
    {
        $products = Product::digital();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function bestSeller()
{
    // return Cache::remember('app.best_selling_products', 86400, function () {
    $products = Product::orderBy('num_of_sale', 'desc')->physical();
    $filteredProducts = filter_products($products)->limit(20)->get();
    
    // Obtener la colección estándar
    $productCollection = new ProductMiniCollection($filteredProducts);
    
    // Modificar la respuesta para incluir el slug
    $data = $productCollection->toResponse(request())->getData();
    
    // Agregar el slug a cada producto
    foreach ($data->data as $key => $product) {
        $currentProduct = $filteredProducts[$key];
        $data->data[$key]->slug = $currentProduct->slug;
    }
    
    return response()->json($data);
    // });
}

    public function related($id)
    {
        // return Cache::remember("app.related_products-$id", 86400, function () use ($id) {
        $product = Product::find($id);
        $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->physical();
        return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        // });
    }

   public function bySlug($slug)
   {
       return Cache::remember("app.related_products-$slug", 86400, function () use ($slug) {
           $product = Product::where('slug', $slug)->first();

           if (!$product) {
               return new ProductDetailCollection(collect([]));
           }

           $products = Product::where('slug', $product->slug)
               ->where('slug', '!=', $product->id)
               ->physical();

           return new ProductDetailCollection(
               filter_products($products)->limit(1)->get()
           );
       });
   }

    public function topFromSeller($id)
    {
        // return Cache::remember("app.top_from_this_seller_products-$id", 86400, function () use ($id) {
        $product = Product::find($id);
        $products = Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->physical();
        return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        // });
    }

    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];
        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }
        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }
        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;
        $products = Product::query();
        $products->with(['category']); // Carga anticipada de la relación categoría
        $products->where('published', 1)->physical();
        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }
            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }
            $products->whereIn('category_id', $category_ids);
        }
        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
            SearchUtility::store($name);
            $case1 = $name . '%';
            $case2 = '%' . $name . '%';
            $products->orderByRaw("CASE
            WHEN name LIKE '$case1' THEN 1
            WHEN name LIKE '$case2' THEN 2
            ELSE 3
            END");
        }
        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }
        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }
        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;
            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;
            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;
            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;
            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        // Obtener los productos filtrados con paginación
        $filteredProducts = filter_products($products)->paginate(10);

        // Obtener la colección estándar
        $productCollection = new ProductMiniCollection($filteredProducts);

        // Modificar la respuesta para incluir la categoría
        $data = $productCollection->toResponse(request())->getData();

        // Agregar la categoría y el slug a cada producto
        foreach ($data->data as $key => $product) {
            $currentProduct = $filteredProducts[$key];
            $data->data[$key]->category = [
                'id' => $currentProduct->category_id,
                'name' => $currentProduct->category ? $currentProduct->category->name : ''
            ];

            // Añadir el campo slug
            $data->data[$key]->slug = $currentProduct->slug;
        }
        
        return response()->json($data);
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;
        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }
        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);
        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }
        return $this->calc($product, $str, $request, $tax);
        /*
        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $stockQuantity = $product_stock->qty;
        //discount calculation
        $discount_applicable = false;
        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }
        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'price' => (float)convert_price($price),
            'price_string' => format_price(convert_price($price)),
            'stock' => intval($stockQuantity),
            'image' => $product_stock->image == null ? "" : uploaded_asset($product_stock->image)
        ]);*/
    }
    // public function home()
    // {
    //     return new ProductCollection(Product::inRandomOrder()->physical()->take(50)->get());
    // }
}
