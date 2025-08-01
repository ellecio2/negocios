<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Search;

use App\Models\Product;

use App\Models\Category;

use App\Models\Brand;

use App\Models\Color;

use App\Models\Shop;

use App\Models\Attribute;

use App\Models\AttributeCategory;

use App\Utility\CategoryUtility;



class SearchController extends Controller

{

    public function index(Request $request, $category_id = null, $brand_id = null)

    {

        $query = $request->keyword;

        $sort_by = $request->sort_by;

        $min_price = $request->min_price;

        $max_price = $request->max_price;

        $seller_id = $request->seller_id;

        $attributes = Attribute::all();

        $selected_attribute_values = array();

        $colors = Color::all();

        $selected_color = null;



        $conditions = [];



        if ($brand_id != null) {

            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);

        } elseif ($request->brand != null) {

            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;

            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);

        }



        // if ($seller_id != null) {

        //     $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);

        // }



        $products = Product::where($conditions);



        if ($category_id != null) {

            $category_ids = CategoryUtility::children_ids($category_id);

            $category_ids[] = $category_id;



            $products->whereIn('category_id', $category_ids);



            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();

            $attributes = Attribute::whereIn('id', $attribute_ids)->get();

        } else {

            // if ($query != null) {

            //     foreach (explode(' ', trim($query)) as $word) {

            //         $ids = Category::where('name', 'like', '%'.$word.'%')->pluck('id')->toArray();

            //         if (count($ids) > 0) {

            //             foreach ($ids as $id) {

            //                 $category_ids[] = $id;

            //                 array_merge($category_ids, CategoryUtility::children_ids($id));

            //             }

            //         }

            //     }

            //     $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();

            //     $attributes = Attribute::whereIn('id', $attribute_ids)->get();

            // }

        }



        if ($min_price != null && $max_price != null) {

            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);

        }



        if ($query != null) {

            $searchController = new SearchController;

            $searchController->store($request);



            $products->where(function ($q) use ($query) {

                foreach (explode(' ', trim($query)) as $word) {

                    $q->where('name', 'like', '%' . $word . '%')

                        ->orWhere('tags', 'like', '%' . $word . '%')

                        ->orWhereHas('product_translations', function ($q) use ($word) {

                            $q->where('name', 'like', '%' . $word . '%');

                        })

                        ->orWhereHas('stocks', function ($q) use ($word) {

                            $q->where('sku', 'like', '%' . $word . '%');

                        });

                }

            });



            $case1 = $query . '%';

            $case2 = '%' . $query . '%';



            $products->orderByRaw("CASE 

                WHEN name LIKE '$case1' THEN 1 

                WHEN name LIKE '$case2' THEN 2 

                ELSE 3 

                END");

        }



        switch ($sort_by) {

            case 'newest':

                $products->orderBy('created_at', 'desc');

                break;

            case 'oldest':

                $products->orderBy('created_at', 'asc');

                break;

            case 'price-asc':

                $products->orderBy('unit_price', 'asc');

                break;

            case 'price-desc':

                $products->orderBy('unit_price', 'desc');

                break;

            default:

                $products->orderBy('id', 'desc');

                break;

        }



        if ($request->has('color')) {

            $str = '"' . $request->color . '"';

            $products->where('colors', 'like', '%' . $str . '%');

            $selected_color = $request->color;

        }



        if ($request->has('selected_attribute_values')) {

            $selected_attribute_values = $request->selected_attribute_values;

            $products->where(function ($query) use ($selected_attribute_values) {

                foreach ($selected_attribute_values as $key => $value) {

                    $str = '"' . $value . '"';



                    $query->orWhere('choice_options', 'like', '%' . $str . '%');

                }

            });

        }



        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());



        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));

    }

// listar los productos buscato por los articulos
public function indexcategory(Request $request, $category_id = null, $brand_id = null)

    {

        $query = $request->keyword;

        $sort_by = $request->sort_by;

        $min_price = $request->min_price;

        $max_price = $request->max_price;

        $attributes = Attribute::all();

        $selected_attribute_values = array();



        $conditions = [];



        if ($brand_id != null) {

            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);

        } elseif ($request->brand != null) {

            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;

            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);

        }



        $products = Product::where($conditions);



        if ($category_id != null) {

            $category_ids = CategoryUtility::children_ids($category_id);

            $category_ids[] = $category_id;



            $products->whereIn('category_id', $category_ids);



            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();

            $attributes = Attribute::whereIn('id', $attribute_ids)->get();

        } else {

          
        }



        if ($min_price != null && $max_price != null) {

            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);

        }



        if ($query != null) {

            $searchController = new SearchController;

            $searchController->store($request);



            $products->where(function ($q) use ($query) {

                foreach (explode(' ', trim($query)) as $word) {

                    $q->where('name', 'like', '%' . $word . '%')

                        ->orWhere('tags', 'like', '%' . $word . '%')

                        ->orWhereHas('product_translations', function ($q) use ($word) {

                            $q->where('name', 'like', '%' . $word . '%');

                        })

                        ->orWhereHas('stocks', function ($q) use ($word) {

                            $q->where('sku', 'like', '%' . $word . '%');

                        });

                }

            });



            $case1 = $query . '%';

            $case2 = '%' . $query . '%';



            $products->orderByRaw("CASE 

                WHEN name LIKE '$case1' THEN 1 

                WHEN name LIKE '$case2' THEN 2 

                ELSE 3 

                END");

        }



        switch ($sort_by) {

            case 'newest':

                $products->orderBy('created_at', 'desc');

                break;

            case 'oldest':

                $products->orderBy('created_at', 'asc');

                break;

            case 'price-asc':

                $products->orderBy('unit_price', 'asc');

                break;

            case 'price-desc':

                $products->orderBy('unit_price', 'desc');

                break;

            default:

                $products->orderBy('id', 'desc');

                break;

        }



        if ($request->has('color')) {

            $str = '"' . $request->color . '"';

            $products->where('colors', 'like', '%' . $str . '%');

            $selected_color = $request->color;

        }



        if ($request->has('selected_attribute_values')) {

            $selected_attribute_values = $request->selected_attribute_values;

            $products->where(function ($query) use ($selected_attribute_values) {

                foreach ($selected_attribute_values as $key => $value) {

                    $str = '"' . $value . '"';



                    $query->orWhere('choice_options', 'like', '%' . $str . '%');

                }

            });

        }



        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        // Buscar categorías que contengan el término de búsqueda
        $cat=$request->category;
        // $cat='motores';
 
    $keyword=$cat;
    // Obtener los productos que pertenecen a las categorías encontradas
    $keywords = explode(' ', $keyword);
    $keywords = array_filter($keywords, function($word) {
        return strlen($word) >= 4;
    });

    // Construir la consulta
    $keywords = explode(' ', $keyword);
    $keywords = array_filter($keywords, function($word) {
        return strlen($word) >= 4;
    });

    // Buscar categorías que coincidan con las palabras clave
    $productsfiter = Category::where(function ($query) use ($keywords) {
        foreach ($keywords as $keyword) {
            $query->orWhere('name', 'like', '%' . $keyword . '%')
                  ->orWhere('slug', 'like', '%' . $keyword . '%')
                  ->orWhere('meta_title', 'like', '%' . $keyword . '%');
        }
    })->get();

    // Obtener los productos asociados a las categorías encontradas
    $productIds = $productsfiter->flatMap(function ($category) {
        return $category->products->pluck('id');
    })->unique()->toArray();

    $productos = Product::whereIn('id', $productIds)->take(6)->get();

    

        return view('frontend.product_listing_category', compact('products', 'query', 'category_id', 'brand_id', 'sort_by', 'min_price', 'max_price', 'attributes', 'selected_attribute_values','productos'));

    }

    public function listing(Request $request)

    {

        return $this->index($request);

    }



    public function listingByCategory(Request $request, $category_slug)

    {

        $category = Category::where('slug', $category_slug)->first();

        if ($category != null) {

            return $this->index($request, $category->id);

        }

        abort(404);

    }



    public function listingByBrand(Request $request, $brand_slug)

    {

        $brand = Brand::where('slug', $brand_slug)->first();

        if ($brand != null) {

            return $this->index($request, null, $brand->id);

        }

        abort(404);

    }



    //Suggestional Search

    public function ajax_search(Request $request)

    {

        $keywords = array();

        $query = $request->search;

        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();

        foreach ($products as $key => $product) {

            foreach (explode(',', $product->tags) as $key => $tag) {

                if (stripos($tag, $query) !== false) {

                    if (sizeof($keywords) > 5) {

                        break;

                    } else {

                        if (!in_array(strtolower($tag), $keywords)) {

                            array_push($keywords, strtolower($tag));

                        }

                    }

                }

            }

        }



        $products_query = filter_products(Product::query());



        $products_query = $products_query->where('published', 1)

            ->where(function ($q) use ($query) {

                foreach (explode(' ', trim($query)) as $word) {

                    $q->where('name', 'like', '%' . $word . '%')

                        ->orWhere('tags', 'like', '%' . $word . '%')

                        ->orWhereHas('product_translations', function ($q) use ($word) {

                            $q->where('name', 'like', '%' . $word . '%');

                        })

                        ->orWhereHas('stocks', function ($q) use ($word) {

                            $q->where('sku', 'like', '%' . $word . '%');

                        });

                }

            });

        $case1 = $query . '%';

        $case2 = '%' . $query . '%';



        $products_query->orderByRaw("CASE 

                WHEN name LIKE '$case1' THEN 1 

                WHEN name LIKE '$case2' THEN 2 

                ELSE 3 

                END");

        $products = $products_query->limit(3)->get();



        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);



        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $query . '%')->get()->take(3);



        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {

            return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));

        }

        return '0';

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        $search = Search::where('query', $request->keyword)->first();

        if ($search != null) {

            $search->count = $search->count + 1;

            $search->save();

        } else {

            $search = new Search;

            $search->query = $request->keyword;

            $search->save();

        }

    }

}

