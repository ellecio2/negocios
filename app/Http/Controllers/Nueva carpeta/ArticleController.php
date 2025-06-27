<?php

namespace App\Http\Controllers;


use App\Models\Articles;
use App\Models\Brand;
use App\Models\BrandDetail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Year;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller

{
    protected $token;

    public function __construct()
    {
        $this->middleware(['permission:view_all_offline_articles_recharges'])->only('offline_recharge_request');
        $this->token = getAccessToken();
    }

    /**
     * Obtiene todas las marcas disponibles para un año específico
     */

    public function fetchMakes($year)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values", [
                    'compatibility_property' => 'Make',
                    'category_id' => '6030',
                    'filter' => "Year:$year"
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ordenar las marcas alfabéticamente
                if (isset($data['compatibilityPropertyValues'])) {
                    usort($data['compatibilityPropertyValues'], function ($a, $b) {
                        return strcmp($a['value'], $b['value']);
                    });

                    // Filtrar marcas vacías o nulas
                    $makes = array_filter($data['compatibilityPropertyValues'], function ($make) {
                        return !empty($make['value']);
                    });
                }

                return response()->json([
                    'success' => true,
                    'makes' => $makes ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las marcas',
                'error' => $response->json()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la petición',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function fetchMakesByCategory($category)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values", [
                    'compatibility_property' => 'Make',
                    'category_id' => $this->getCategoryId($category)
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ordenar las marcas alfabéticamente
                if (isset($data['compatibilityPropertyValues'])) {
                    usort($data['compatibilityPropertyValues'], function ($a, $b) {
                        return strcmp($a['value'], $b['value']);
                    });

                    // Filtrar marcas vacías o nulas
                    $makes = array_filter($data['compatibilityPropertyValues'], function ($make) {
                        return !empty($make['value']);
                    });
                }

                return response()->json([
                    'success' => true,
                    'makes' => $makes ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las marcas',
                'error' => $response->json()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la petición',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene el ID de la categoría basado en el nombre de la categoría
     */
    //    listamos todas las categorias
    // filepath: /c:/laragon/www/desarrollo/app/Http/Controllers/ArticleController.php




    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $articles = Articles::where('user_id', Auth::user()->id)->paginate(5);
        return view('frontend.user.articles.index', compact('articles'));
    }

    public function fetchCategories()
    {
        try {
            /** @var array<int, array<string, string>> */
            $categories = Cache::remember('ebay_categories', 86400, function () {
                $baseUrl = "https://api.ebay.com/commerce/taxonomy/v1";

                // Get Motors category structure (6000)
                $response = Http::withToken($this->token)
                    ->get("$baseUrl/category_tree/0/get_category_subtree", [
                        'category_id' => '6000' // Motors main category
                    ]);

                if ($response->successful()) {
                    Log::info('eBay Motors Categories Response:', $response->json());

                    $categories = [];
                    // Look specifically for vehicle categories
                    $this->extractVehicleCategories($response->json()['categorySubtree'], $categories);

                    return $categories;
                }

                Log::error('Failed to fetch eBay categories', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            });

            return response()->json([
                'success' => true,
                'categories' => array_values($categories)
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching categories:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function extractVehicleCategories($node, &$categories)
    {
        if (isset($node['category'])) {
            $categoryId = $node['category']['categoryId'];
            $categoryName = $node['category']['categoryName'];

            // Check if category has vehicle-specific aspects
            if ($this->isVehicleCategory($categoryId)) {
                $categories[] = [
                    'categoryId' => $categoryId,
                    'categoryName' => $categoryName
                ];
                Log::info("Added vehicle category: $categoryName ($categoryId)");
            }
        }

        if (isset($node['childCategoryTreeNodes'])) {
            foreach ($node['childCategoryTreeNodes'] as $child) {
                $this->extractVehicleCategories($child, $categories);
            }
        }
    }

    private function isVehicleCategory($categoryId)
    {
        try {
            $baseUrl = "https://api.ebay.com/commerce/taxonomy/v1";
            $response = Http::withToken($this->token)
                ->get("$baseUrl/category_tree/0/get_item_aspects_for_category", [
                    'category_id' => $categoryId
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $aspects = $data['aspects'] ?? [];

                // Look for vehicle-specific aspects
                $requiredAspects = ['make', 'model', 'year'];
                $foundAspects = [];

                foreach ($aspects as $aspect) {
                    $aspectName = strtolower($aspect['localizedAspectName']);
                    if (in_array($aspectName, $requiredAspects)) {
                        $foundAspects[] = $aspectName;
                    }
                }

                Log::info("Category $categoryId aspects:", [
                    'required' => $requiredAspects,
                    'found' => $foundAspects
                ]);

                // Category must have all required aspects
                return count(array_intersect($requiredAspects, $foundAspects)) === count($requiredAspects);
            }

            return false;
        } catch (Exception $e) {
            Log::error("Error checking category $categoryId aspects:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Obtiene todos los modelos disponibles para una marca y año específicos
     */
    public function fetchModelss($make, $year)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values", [
                    'compatibility_property' => 'Model',
                    'category_id' => '33559',
                    'filter' => "Year:$year,Make:$make"
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ordenar los modelos alfabéticamente
                if (isset($data['compatibilityPropertyValues'])) {
                    usort($data['compatibilityPropertyValues'], function ($a, $b) {
                        return strcmp($a['value'], $b['value']);
                    });
                }

                return response()->json([
                    'success' => true,
                    'models' => $data['compatibilityPropertyValues'] ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los modelos',
                'error' => $response->json()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la petición',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene todos los trims disponibles para un modelo, marca y año específicos
     */
    public function fetchTrims($make, $year, $model)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values", [
                    'compatibility_property' => 'Trim',
                    'category_id' => '6030',
                    'filter' => "Year:$year,Make:$make,Model:$model"
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ordenar los trims alfabéticamente
                if (isset($data['compatibilityPropertyValues'])) {
                    usort($data['compatibilityPropertyValues'], function ($a, $b) {
                        return strcmp($a['value'], $b['value']);
                    });

                    // Filtrar trims vacíos o nulos
                    $trims = array_filter($data['compatibilityPropertyValues'], function ($trim) {
                        return !empty($trim['value']);
                    });
                }

                return response()->json([
                    'success' => true,
                    'trims' => $trims ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los trims',
                'error' => $response->json()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la petición',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene todos los motores disponibles para un modelo, marca y año específicos
     */
    public function fetchEngines($make, $year, $model)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values", [
                    'compatibility_property' => 'Engine',
                    'category_id' => '6030',
                    'filter' => "Year:$year,Make:$make,Model:$model"
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ordenar los motores alfabéticamente
                if (isset($data['compatibilityPropertyValues'])) {
                    usort($data['compatibilityPropertyValues'], function ($a, $b) {
                        return strcmp($a['value'], $b['value']);
                    });

                    // Filtrar motores vacíos o nulos
                    $engines = array_filter($data['compatibilityPropertyValues'], function ($engine) {
                        return !empty($engine['value']);
                    });
                }

                return response()->json([
                    'success' => true,
                    'engines' => $engines ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los motores',
                'error' => $response->json()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la petición',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene la imagen del vehículo basado en los parámetros especificados
     * Incluye tanto vehículos nuevos como usados
     */
    public function getImage($make, $year, $model)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("https://api.ebay.com/buy/browse/v1/item_summary/search", [
                    'q' => "$year $make $model",
                    'category_ids' => ['6001', '6000'], // 6001: Nuevos, 6000: Usados
                    'limit' => 50, // Aumentamos el límite para tener más opciones
                    'fieldgroups' => 'EXTENDED',
                    'filter' => 'conditions:{NEW|USED}'
                ]);
            //dd($response->successful(), $response->json()['itemSummaries']);
            if ($response->successful() && isset($response->json()['itemSummaries'])) {
                $items = $response->json()['itemSummaries'];

                // Filtrar elementos que no sean de la categoría "Toys & Hobbies"
                $items = array_filter($items, function ($item) {
                    return strtolower($item['title']) !== 'toys & hobbies';
                });

                // Buscar la mejor coincidencia basada en el título
                $bestMatch = null;
                foreach ($items as $item) {
                    $title = strtolower($item['title']);
                    $searchTerms = [
                        strtolower($year),
                        strtolower($make),
                        strtolower($model)
                    ];

                    // Verificar si todos los términos de búsqueda están en el título
                    $matchesAll = true;
                    foreach ($searchTerms as $term) {
                        if (strpos($title, $term) === false) {
                            $matchesAll = false;
                            break;
                        }
                    }

                    if ($matchesAll) {
                        $bestMatch = $item;
                        break;
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => $bestMatch ? [
                        'title' => $bestMatch['title'],
                        'image' => $bestMatch['image']['imageUrl'] ?? null,
                        'thumbnails' => $bestMatch['thumbnailImages'] ?? [],
                        'additionalImages' => $bestMatch['additionalImages'] ?? [],
                        'itemId' => $bestMatch['itemId'] ?? null
                    ] : null
                ]);
            }

            return response()->json([
                'success' => false,
                'data' => 'Error al obtener la imagen',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la imagen',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function getArticles()
    {
        try {
            $articles = Articles::select('modelo', 'image','category_id')->where('user_id', Auth::user()->id)->get();
            return response()->json($articles);
        } catch (\Exception $e) {
            \Log::error('Error al obtener artículos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los artículos'], 500);
        }

    }

    public function getSubCategoriesByCategory($subcategoryId)
    {
        $brands = Product::where('category_id', $subcategoryId)->get();
        return response()->json($brands);
    }

    public function load_articles()
    {
        $articles = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('articles', 'articles.product_id', '=', 'products.id')
            ->leftJoin('brand_details', 'brand_details.id', '=', 'articles.model_id')
            ->leftJoin('years', 'years.id', '=', 'articles.year')
            ->where('articles.user_id', Auth::user()->id)
            ->select(
                'articles.*',
                'categories.name as category_name',
                'categories.icon as category_icon',
                'products.name as product_name',
                DB::raw('IFNULL(brand_details.model, "N/A") as model_name'),
                DB::raw('IFNULL(years.year, "N/A") as year_name')
            )
            ->get();


        foreach ($articles as $article) {
            $article->category_icon_url = uploaded_asset($article->category_icon);
        }

        return response()->json(['articles' => $articles]);
    }

    public function select()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('frontend.user.articles.index', compact('categories'));
    }

    public function load_articles2()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $categories = Category::limit(10)->get();
        $brands = Brand::all();
        $year = Year::all();
        /*$articles = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('articles', 'articles.product_id', '=', 'products.id')
            ->join('brand_details', 'brand_details.id', '=', 'articles.model_id')
            ->join('years', 'years.id', '=', 'articles.year')
            ->where('articles.user_id', Auth::user()->id)
            ->select('articles.*', 'categories.name as category_name', 'products.name as product_name', 'brand_details.model as model_name', 'years.year as year_name')
            ->paginate(10);*/
        $articles = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('articles', 'articles.product_id', '=', 'products.id')
            ->leftJoin('brand_details', 'brand_details.id', '=', 'articles.model_id')
            ->leftJoin('years', 'years.id', '=', 'articles.year')
            ->where('articles.user_id', Auth::user()->id)
            ->select(
                'articles.*',
                'categories.name as category_name',
                'products.name as product_name',
                DB::raw('IFNULL(brand_details.model, "N/A") as model_name'),
                DB::raw('IFNULL(years.year, "N/A") as year_name')
            )
            ->paginate(10);

        //dd($articles->toSql(), $articles->getBindings());
        return response()->json([
            'articles' => $articles,
            'year' => $year,
            'brand' => $brands,
            'category' => $categories
        ]);

        /*return view('frontend.user.articles.index', compact('articles', 'categories', 'year', 'brands'));*/
    }

    public function addArticlesModal(Request $request)
    {

        $data = Articles::all();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            // Validaciones básicas
            $request->validate([
                'year' => 'nullable|integer',
                'product_id' => 'required|string',
                'model' => 'required',
                'image' => 'required|string',
                'chasis_serial' => 'nullable|string',
                'category_id' => 'required|string', // Se asegura de que la categoría esté presente
                'modelo' => 'required|string',
            ]);

            // Categorías específicas que requieren 'year' y 'chasis_serial'
            $vehicleCategories = ['6000', '6001', '6024', '6038', '6028'];

            // Validar campos obligatorios si la categoría pertenece a vehicleCategories
            if (in_array($request->category_id, $vehicleCategories)) {
                $request->validate([
                    'year' => 'required|integer',
                    'chasis_serial' => 'required|string',
                ]);
            }

            // Agregar el usuario autenticado al request
            $request['user_id'] = Auth::user()->id;

            // Crear el artículo
            Articles::create($request->all());

            return [
                'status' => true,
                'message' => 'Artículo creado exitosamente',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error al crear el artículo: ' . $e->getMessage(),
            ];
        }
    }


    public function getModelsBy($brandId)
    {
        $brands = BrandDetail::join('products', 'brand_details.product_id', '=', 'products.id')
            ->where('brand_details.product_id', $brandId)
            ->select('brand_details.*', 'products.name as product_name')
            ->get();
        $years = Year::all();

        return response()->json([
            'brands' => $brands,
            'years' => $years
        ]);

        //return response()->json($brands);
    }

    public function delete_article($id)
    {
        $article = Articles::find($id);

        if (!$article) {
            return response()->json([
                'status' => 'error',
                'message' => translate('Artículo no encontrado'),
            ], 404); // Respuesta 404 si no encuentra el artículo
        }

        $article->delete();

        return response()->json([
            'status' => 'success',
            'message' => translate('Artículo eliminado correctamente'),
        ]);
    }

    public function get_articles(Request $request)
    {

        $request->validate([
            'article_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $category_id = $request->input('article_id');
        $user_id = $request->input('user_id');
        $product = Product::query()
            ->join('articles', 'products.id', '=', 'articles.product_id')
            ->where('products.category_id', $category_id)
            ->where('articles.user_id', $user_id)
            ->get();

        if ($product->isEmpty()) {
            return response()->json(['message' => 'No articles found.'], 404);
        }
        $html = view('frontend.partials.articles_box_2', ['products' => $product])->render();
        return response()->json(['html' => $html]);
    }

    public function getMarcaByBrand($brand_id)
    {
        $model = Product::where('category_id', $brand_id)
            ->get();

        return response()->json($model);
    }

    public function getMazdaModels()
    {
        $token = $this->token;
        $categoryId = '33559';

        $url = "https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values";

        try {
            $params = [
                'category_id' => $categoryId,
                'compatibility_property' => 'Make',
                'filter' => ''
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($url, $params);

            if ($response->successful()) {
                $data = $response->json();
                $mazdaOnly = array_filter($data['compatibilityProperties'] ?? [], function ($item) {
                    return $item['value'] === 'Mazda';
                });

                return response()->json(['mazdaModels' => array_values($mazdaOnly)]);
            }

            return response()->json(['error' => 'Error al obtener los modelos'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMazdaYears($model)
    {
        $token = $this->token;
        $categoryId = '33559';

        $url = "https://api.ebay.com/commerce/taxonomy/v1/category_tree/100/get_compatibility_property_values";

        try {
            $params = [
                'category_id' => $categoryId,
                'compatibility_property' => 'Year',
                'filter' => "Make:Mazda,Model:{$model}"
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($url, $params);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al obtener los años'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchModels(Request $request)
    {
        try {
            $brandName = $request->get('brand_name');
            $categoryId = $request->get('category_id');
            $categoryName = $request->get('category');

            if (!$brandName || (!$categoryId && !$categoryName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Se requiere marca y (ID o nombre de categoría)'
                ], 400);
            }

            $cacheKey = "ebay_models_{$categoryId}_{$brandName}";

            /** @var array<int, array<string, mixed>> $models */
            $models = Cache::remember(
                $cacheKey,
                3600,
                function () use ($brandName, $categoryId, $categoryName): array {
                    $baseUrl = "https://api.ebay.com/buy/browse/v1";

                    $queryParams = [
                        'limit' => 100,
                        'fieldgroups' => 'FULL'
                    ];

                    if ($categoryId) {
                        $queryParams['category_ids'] = $categoryId;
                        $queryParams['q'] = $brandName;
                    } else {
                        $queryParams['q'] = "$brandName $categoryName";
                    }

                    $response = Http::withToken($this->token)
                        ->withHeaders([
                            'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_US',
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json'
                        ])
                        ->get("{$baseUrl}/item_summary/search", $queryParams);

                    if (!$response->successful()) {
                        return [];
                    }

                    $data = $response->json();
                    return $this->extractModelsFromItems(
                        $data['itemSummaries'] ?? [],
                        $brandName,
                        $categoryName ?? ''
                    );
                }
            );

            return response()->json([
                'success' => true,
                'category_id' => $categoryId,
                'category_name' => $categoryName,
                'brand' => $brandName,
                'models' => $models,
                'total' => count($models)
            ]);
        } catch (Exception $e) {
            Log::error('Error en fetchModels', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener modelos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function extractModelsFromItems(array $items, string $brandName, string $category): array
{
    /** @var array<string, array<string, mixed>> $uniqueModels */
    $uniqueModels = [];

    foreach ($items as $item) {
        $title = $item['title'];
        $shortDescription = $item['shortDescription'] ?? '';
        $itemId = $item['itemId'] ?? '';

        $imageUrl = isset($item['image']['imageUrl']) ? $item['image']['imageUrl'] :
                   (isset($item['thumbnailImages'][0]['imageUrl']) ? $item['thumbnailImages'][0]['imageUrl'] : null);

        $modelCandidate = str_ireplace($brandName, '', $title);
        $modelCandidate = trim(preg_replace('/\s+/', ' ', $modelCandidate));

        if (preg_match('/([A-Za-z0-9]+(?:\s*[-\/]\s*[A-Za-z0-9]+)*)/i', $modelCandidate, $matches)) {
            /** @var string $modelName */
            $modelName = trim($matches[1]);

            if (strlen($modelName) > 2 && !isset($uniqueModels[$modelName])) {
                $uniqueModels[$modelName] = [
                    'modelId' => md5($modelName),
                    'modelName' => $modelName,
                    'fullTitle' => $title,
                    'description' => $shortDescription,
                    'itemId' => $itemId,
                    'price' => $item['price'] ?? null,
                    'condition' => $item['condition'] ?? null,
                    'imageUrl' => $imageUrl,
                    'count' => 1,
                    'samples' => [$title]
                ];
            } elseif (isset($uniqueModels[$modelName])) {
                $uniqueModels[$modelName]['count']++;
                /** @var array<int, string> $samples */
                $samples = $uniqueModels[$modelName]['samples'];
                if (count($samples) < 3) {
                    $uniqueModels[$modelName]['samples'][] = $title;
                }
                if (!$uniqueModels[$modelName]['imageUrl'] && $imageUrl) {
                    $uniqueModels[$modelName]['imageUrl'] = $imageUrl;
                }
            }
        }
    }

    return array_values($uniqueModels);
}



    // obtenemos las categorias
    public function fetchMainCategoriess()
    {
        try {
            // URL correcta de la API de Taxonomía de eBay
            $baseUrl = "https://api.ebay.com/commerce/taxonomy/v1/category_tree/0/get_category_subtree";

            // Llamada a la API para obtener las categorías
            $response = Http::withToken($this->token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer " . $this->token // Asegúrate de que el token sea correcto
                ])
                ->get($baseUrl, [
                    'category_id' => '6000' // '6000' es el ID de la categoría raíz para eBay
                ]);

            // Verificamos si la solicitud fue exitosa
            if ($response->successful()) {
                $data = $response->json();

                // Comprobamos si se ha recibido el árbol de categorías correctamente
                if (isset($data['categorySubtree'])) {
                    $categories = $this->extractCategories($data['categorySubtree']);
                    return response()->json([
                        'success' => true,
                        'categories' => $categories
                    ]);
                } else {
                    // En caso de que no se reciban las categorías correctamente
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontraron categorías'
                    ], 500);
                }
            } else {
                // Si la respuesta no es exitosa, logueamos el error
                Log::error('Error en respuesta de eBay', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener categorías de eBay'
                ], 500);
            }
        } catch (Exception $e) {
            // Si ocurre una excepción, registramos el error
            Log::error('Error en fetchMainCategories', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener categorías principales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function extractCategories(array $categorySubtree): array
    {
        $categories = [];

        // Revisamos si hay nodos de categorías principales
        if (isset($categorySubtree['childCategoryTreeNodes'])) {
            foreach ($categorySubtree['childCategoryTreeNodes'] as $childNode) {
                $categories[] = [
                    'categoryId' => $childNode['category']['categoryId'],
                    'categoryName' => $childNode['category']['categoryName']
                ];
            }
        }

        return $categories;
    }

    //obtenemos las marcas
    public function fetchBrands(Request $request)
    {
        try {
            $category = $request->get('category', 'autos');
            Log::info('Iniciando búsqueda de marcas', ['category' => $category]);

            /** @var array<string, mixed> */
            $brands = Cache::remember("ebay_brands_{$category}", 86400, function () use ($category) {
                $baseUrl = "https://api.ebay.com/buy/browse/v1";

                $response = Http::withToken($this->token)
                    ->withHeaders([
                        'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_ES',
                        'X-EBAY-C-ENDUSERCTX' => 'contextualLocation=country=US',
                        'Content-Type' => 'application/json'
                    ])
                    ->get("{$baseUrl}/item_summary/search", [
                        'q' => $category,
                        'category_ids' => '6000',  // Motors
                        'limit' => 200,
                        'filter' => [
                            'conditions:{NEW|USED}',
                            'categories:{6000}'
                        ],
                        'aspect_filter' => [
                            'categoryId:6000',
                            'Vehicle Type:Car & Truck'
                        ]
                    ]);

                Log::info('Respuesta de búsqueda', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->json()
                ]);

                if ($response->successful()) {
                    $items = $response->json()['itemSummaries'] ?? [];
                    return $this->extractBrandsFromItems($items);
                }

                Log::error('Error en API', [
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);

                return [];
            });

            return response()->json([
                'success' => true,
                'category' => $category,
                'brands' => array_values($brands),
                'total' => count($brands),
                'page' => $request->get('page', 1),
                'perPage' => 20
            ]);
        } catch (Exception $e) {
            Log::error('Error en fetchBrands', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener marcas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function extractBrandsFromItemSs(array $items): array
    {
        $uniqueBrands = [];

        foreach ($items as $item) {
            // Extraer marca del título
            if (isset($item['title'])) {
                $words = explode(' ', $item['title']);
                $potentialBrand = trim($words[0]);

                if (!empty($potentialBrand)) {
                    $uniqueBrands[$potentialBrand] = [
                        'brandId' => md5($potentialBrand),
                        'brandName' => $potentialBrand
                    ];
                }
            }

            // Buscar en aspectos
            if (isset($item['aspects'])) {
                foreach ($item['aspects'] as $aspect) {
                    if (in_array(strtolower($aspect['name']), ['brand', 'make', 'manufacturer'])) {
                        $brandName = trim($aspect['value'][0]);
                        $uniqueBrands[$brandName] = [
                            'brandId' => md5($brandName),
                            'brandName' => $brandName
                        ];
                    }
                }
            }
        }

        Log::info('Marcas extraídas', [
            'count' => count($uniqueBrands),
            'brands' => array_values($uniqueBrands)
        ]);

        return $uniqueBrands;
    }

    private    $ebayMainCategories = [
        '6000' => ['en' => 'eBay Motors', 'es' => 'Motores y Vehículos'],
        '6001' => ['en' => 'Cars & Trucks', 'es' => 'Autos y Camionetas'],
        '6024' => ['en' => 'Motorcycles', 'es' => 'Motocicletas'],
        '6038' => ['en' => 'Boats & Watercraft', 'es' => 'Botes y Moto acuática'],
        '6028' => ['en' => 'Auto Parts & Accessories', 'es' => 'Auto Partes y Accesorios'],
        '15032' => ['en' => 'Cell Phones & Accessories', 'es' => 'Teléfonos Móviles y Accesorios'],
        '58058' => ['en' => 'Computers/Tablets & Networking', 'es' => 'Computadoras/Tablets y Redes'],
        '1249' => ['en' => 'Video Games & Consoles', 'es' => 'Videojuegos y Consolas'],
        '625' => ['en' => 'Cameras & Photo', 'es' => 'Cámaras y Fotografía'],
        '293' => ['en' => 'Consumer Electronics', 'es' => 'Electrónica de Consumo'],
        '32852' => ['en' => 'TV, Audio & Surveillance', 'es' => 'TV, Audio y Vigilancia'],
        '11450' => ['en' => 'Men\'s Clothing', 'es' => 'Ropa para Hombre'],
        '15724' => ['en' => 'Women\'s Clothing', 'es' => 'Ropa para Mujer'],
        '11484' => ['en' => 'Fashion Accessories', 'es' => 'Accesorios de Moda'],
        '11554' => ['en' => 'Jewelry & Watches', 'es' => 'Joyería y Relojes'],
        '3034' => ['en' => 'Shoes', 'es' => 'Calzado'],
        '11700' => ['en' => 'Home & Garden', 'es' => 'Hogar y Jardín'],
        '159907' => ['en' => 'Furniture', 'es' => 'Muebles'],
        '20710' => ['en' => 'Major Appliances', 'es' => 'Electrodomésticos'],
        '159912' => ['en' => 'Tools', 'es' => 'Herramientas'],
        '11071' => ['en' => 'Home Improvement', 'es' => 'Mejoras del Hogar'],
        '550' => ['en' => 'Art', 'es' => 'Arte'],
        '20081' => ['en' => 'Antiques', 'es' => 'Antigüedades'],
        '11116' => ['en' => 'Coins & Paper Money', 'es' => 'Monedas y Billetes'],
        '260' => ['en' => 'Stamps', 'es' => 'Sellos'],
        '64482' => ['en' => 'Sports Memorabilia', 'es' => 'Memorabilia Deportiva'],
        '888' => ['en' => 'Sporting Goods', 'es' => 'Artículos Deportivos'],
        '1513' => ['en' => 'Golf Equipment', 'es' => 'Equipamiento de Golf'],
        '7294' => ['en' => 'Cycling', 'es' => 'Ciclismo'],
        '15273' => ['en' => 'Fitness Equipment', 'es' => 'Equipamiento Fitness'],
        '267' => ['en' => 'Books', 'es' => 'Libros'],
        '11232' => ['en' => 'Movies & TV', 'es' => 'Películas y Series'],
        '11233' => ['en' => 'Music', 'es' => 'Música'],
        '619' => ['en' => 'Musical Instruments', 'es' => 'Instrumentos Musicales'],
        '12576' => ['en' => 'Business & Industrial', 'es' => 'Negocios e Industria'],
        '26395' => ['en' => 'Health & Beauty', 'es' => 'Salud y Belleza'],
        '220' => ['en' => 'Toys & Hobbies', 'es' => 'Juguetes y Pasatiempos'],
        '237' => ['en' => 'Dolls & Bears', 'es' => 'Muñecas y Peluches'],
        '1281' => ['en' => 'Baby', 'es' => 'Bebés']
    ];
    private $categoryBrandsMap = [
        // Motores y Vehículos
        '6000' => ['Ford', 'Chevrolet', 'Toyota', 'Honda', 'BMW', 'Mercedes-Benz', 'Audi', 'Volkswagen'],
        '6001' => ['Toyota', 'Ford', 'Honda', 'Chevrolet', 'Nissan', 'BMW', 'Mercedes-Benz', 'Audi', 'Hyundai', 'Kia'],
        '6024' => ['Harley-Davidson', 'Honda', 'Yamaha', 'Kawasaki', 'Suzuki', 'BMW', 'Ducati', 'KTM', 'Triumph'],
        '6038' => ['Yamaha', 'Sea-Doo', 'Mercury', 'Honda Marine', 'Kawasaki', 'Boston Whaler', 'Bayliner'],
        '6028' => ['Bosch', 'NGK', 'Denso', 'Monroe', 'ACDelco', 'Magna', 'Continental', 'Bridgestone'],

        // Electrónica
        '15032' => ['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'OnePlus', 'Sony', 'LG', 'Motorola', 'Google', 'OPPO'],
        '58058' => ['HP', 'Dell', 'Lenovo', 'Apple', 'Asus', 'Acer', 'Microsoft', 'MSI', 'Samsung', 'Toshiba'],
        '1249' => ['Sony', 'Microsoft', 'Nintendo', 'Razer', 'Logitech', 'SteelSeries', 'HyperX', 'Corsair'],
        '625' => ['Canon', 'Nikon', 'Sony', 'Fujifilm', 'Panasonic', 'Olympus', 'Leica', 'GoPro', 'DJI'],
        '293' => ['Samsung', 'LG', 'Sony', 'Apple', 'Bose', 'JBL', 'Philips', 'Panasonic', 'Sharp'],
        '32852' => ['Samsung', 'LG', 'Sony', 'TCL', 'Vizio', 'Bose', 'Sonos', 'Denon', 'Yamaha'],

        // Moda
        '11450' => ['Nike', 'Adidas', 'Under Armour', 'Ralph Lauren', 'Tommy Hilfiger', 'Levi\'s', 'Calvin Klein'],
        '15724' => ['Zara', 'H&M', 'Nike', 'Adidas', 'Michael Kors', 'Calvin Klein', 'Coach', 'Victoria\'s Secret'],
        '11484' => ['Michael Kors', 'Coach', 'Kate Spade', 'Gucci', 'Louis Vuitton', 'Prada', 'Ray-Ban'],
        '11554' => ['Rolex', 'Omega', 'Seiko', 'Casio', 'Fossil', 'Michael Kors', 'Cartier', 'Tag Heuer'],
        '3034' => ['Nike', 'Adidas', 'Puma', 'New Balance', 'Reebok', 'Converse', 'Vans', 'ASICS'],

        // Hogar y Jardín
        '11700' => ['IKEA', 'Home Depot', 'Wayfair', 'Ashley Furniture', 'Craftsman', 'Black & Decker'],
        '159907' => ['IKEA', 'Ashley Furniture', 'La-Z-Boy', 'Pottery Barn', 'Ethan Allen', 'Serta'],
        '20710' => ['Samsung', 'LG', 'Whirlpool', 'GE', 'Maytag', 'Kenmore', 'Bosch', 'KitchenAid'],
        '159912' => ['DeWalt', 'Milwaukee', 'Makita', 'Bosch', 'Black & Decker', 'Craftsman', 'Ryobi'],
        '11071' => ['Home Depot', 'Lowe\'s', 'Moen', 'Delta', 'Kohler', 'American Standard', 'Schlage'],

        // Arte y Coleccionables
        '550' => ['Sotheby\'s', 'Christie\'s', 'Artnet', 'Saatchi Art', 'Heritage Auctions'],
        '20081' => ['Sotheby\'s', 'Christie\'s', 'Heritage Auctions', 'Bonhams', 'Phillips'],
        '11116' => ['NGC', 'PCGS', 'American Eagle', 'Royal Mint', 'Canadian Mint', 'Perth Mint'],
        '260' => ['Stanley Gibbons', 'Scott', 'USPS', 'Royal Mail', 'Canada Post'],
        '64482' => ['Fanatics', 'Upper Deck', 'Topps', 'Panini', 'PSA', 'BGS'],

        // Deportes
        '888' => ['Nike', 'Adidas', 'Under Armour', 'Puma', 'Reebok', 'Wilson', 'Callaway', 'Titleist'],
        '1513' => ['Titleist', 'Callaway', 'TaylorMade', 'PING', 'Cobra', 'Cleveland', 'Mizuno'],
        '7294' => ['Trek', 'Specialized', 'Giant', 'Cannondale', 'Scott', 'Schwinn', 'Santa Cruz'],
        '15273' => ['NordicTrack', 'Bowflex', 'Peloton', 'Life Fitness', 'Precor', 'Nautilus', 'ProForm'],

        // Entretenimiento
        '267' => ['Penguin Random House', 'HarperCollins', 'Simon & Schuster', 'Macmillan', 'Hachette'],
        '11232' => ['Disney', 'Warner Bros', 'Universal', 'Sony Pictures', 'Paramount', '20th Century'],
        '11233' => ['Sony Music', 'Universal Music', 'Warner Music', 'EMI', 'Capitol Records'],
        '619' => ['Yamaha', 'Fender', 'Gibson', 'Roland', 'Casio', 'Pearl', 'Shure', 'Audio-Technica'],

        // Otros
        '12576' => ['3M', 'Honeywell', 'Caterpillar', 'John Deere', 'Siemens', 'General Electric'],
        '26395' => ['L\'Oreal', 'Estee Lauder', 'Procter & Gamble', 'Johnson & Johnson', 'Unilever'],
        '220' => ['LEGO', 'Hasbro', 'Mattel', 'Fisher-Price', 'Playmobil', 'Hot Wheels'],
        '237' => ['Barbie', 'American Girl', 'Steiff', 'Build-A-Bear', 'Madame Alexander'],
        '1281' => ['Pampers', 'Huggies', 'Fisher-Price', 'Graco', 'Chicco', 'Johnson & Johnson']
    ];
    // categorias principales
    public function fetchMainCategories()
    {
        log::info('Iniciando búsqueda de categorías principales');
        try {
            $categories = [];

            $ebayMainCategories = [
                '6000' => ['en' => 'eBay Motors', 'es' => 'Motores y Vehículos'],
                '6001' => ['en' => 'Cars & Trucks', 'es' => 'Autos y Camionetas'],
                '6024' => ['en' => 'Motorcycles', 'es' => 'Motocicletas'],
                '6038' => ['en' => 'Boats & Watercraft', 'es' => 'Náutica'],
                '6028' => ['en' => 'Auto Parts & Accessories', 'es' => 'Partes y Accesorios'],
                '15032' => ['en' => 'Cell Phones & Accessories', 'es' => 'Teléfonos Móviles y Accesorios'],
                '58058' => ['en' => 'Computers/Tablets & Networking', 'es' => 'Computadoras y Tablets'],
                '1249' => ['en' => 'Video Games & Consoles', 'es' => 'Videojuegos y Consolas'],
                '625' => ['en' => 'Cameras & Photo', 'es' => 'Cámaras y Fotografía'],
                '293' => ['en' => 'Consumer Electronics', 'es' => 'Electrónica de Consumo'],
                '32852' => ['en' => 'TV, Audio & Surveillance', 'es' => 'TV, Audio y Vigilancia'],
                '11450' => ['en' => 'Men\'s Clothing', 'es' => 'Ropa para Hombre'],
                '15724' => ['en' => 'Women\'s Clothing', 'es' => 'Ropa para Mujer'],
                '11484' => ['en' => 'Fashion Accessories', 'es' => 'Accesorios de Moda'],
                '11554' => ['en' => 'Jewelry & Watches', 'es' => 'Joyería y Relojes'],
                '3034' => ['en' => 'Shoes', 'es' => 'Calzado'],
                '11700' => ['en' => 'Home & Garden', 'es' => 'Hogar y Jardín'],
                '159907' => ['en' => 'Furniture', 'es' => 'Muebles'],
                '20710' => ['en' => 'Major Appliances', 'es' => 'Electrodomésticos'],
                '159912' => ['en' => 'Tools', 'es' => 'Herramientas'],
                '11071' => ['en' => 'Home Improvement', 'es' => 'Mejoras del Hogar'],
                '550' => ['en' => 'Art', 'es' => 'Arte'],
                '20081' => ['en' => 'Antiques', 'es' => 'Antigüedades'],
                '11116' => ['en' => 'Coins & Paper Money', 'es' => 'Monedas y Billetes'],
                '260' => ['en' => 'Stamps', 'es' => 'Sellos'],
                '64482' => ['en' => 'Sports Memorabilia', 'es' => 'Memorabilia Deportiva'],
                '888' => ['en' => 'Sporting Goods', 'es' => 'Artículos Deportivos'],
                '1513' => ['en' => 'Golf Equipment', 'es' => 'Equipamiento de Golf'],
                '7294' => ['en' => 'Cycling', 'es' => 'Ciclismo'],
                '15273' => ['en' => 'Fitness Equipment', 'es' => 'Equipamiento Fitness'],
                '267' => ['en' => 'Books', 'es' => 'Libros'],
                '11232' => ['en' => 'Movies & TV', 'es' => 'Películas y Series'],
                '11233' => ['en' => 'Music', 'es' => 'Música'],
                '619' => ['en' => 'Musical Instruments', 'es' => 'Instrumentos Musicales'],
                '12576' => ['en' => 'Business & Industrial', 'es' => 'Negocios e Industria'],
                '26395' => ['en' => 'Health & Beauty', 'es' => 'Salud y Belleza'],
                '220' => ['en' => 'Toys & Hobbies', 'es' => 'Juguetes y Pasatiempos'],
                '237' => ['en' => 'Dolls & Bears', 'es' => 'Muñecas y Peluches'],
                '1281' => ['en' => 'Baby', 'es' => 'Bebés']
            ];

            foreach ($ebayMainCategories as $id => $names) {
                $categories[] = [
                    'categoryId' => $id,
                    'categoryName' => $names['es']
                ];
            }

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            Log::error('Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function fetchBrandsByCategory(Request $request)
    {
        try {
            $categoryId = $request->get('category_id');
            $categoryName = $request->get('category_name');

            // Validar que al menos uno de los dos parámetros esté presente
            if (!$categoryId && !$categoryName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Se requiere ID de categoría o nombre de categoría'
                ], 400);
            }

            // Si tenemos el nombre pero no el ID, buscar el ID por nombre
            if (!$categoryId && $categoryName) {
                foreach ($this->ebayMainCategories as $id => $names) {
                    if (strtolower($names['es']) === strtolower(trim($categoryName))) {
                        $categoryId = $id;
                        break;
                    }
                }
            }

            // Si no se encontró el ID, retornar error
            if (!$categoryId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada'
                ], 404);
            }

            // Buscar marcas usando el ID
            $brands = Cache::remember("category_brands_{$categoryId}", 3600, function () use ($categoryId) {
                return $this->searchBrandsInEbay($categoryId);
            });

            return response()->json([
                'success' => true,
                'category_id' => $categoryId,
                'brands' => $brands
            ]);
        } catch (Exception $e) {
            Log::error('Error en fetchBrandsByCategory:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error procesando la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }






    private $desiredCategories = [
        // Vehicles
        'eBay Motors',
        'Cars & Trucks',
        'Motorcycles',
        'Boats & Watercraft',
        'Auto Parts & Accessories',
        'Other Vehicles',

        // Electronics
        'Cell Phones & Accessories',
        'Computers/Tablets & Networking',
        'Video Games & Consoles',
        'Cameras & Photography',
        'TV, Audio & Surveillance',
        'Consumer Electronics',
        'Drones & RC Aircraft',

        // Home & Garden
        'Furniture',
        'Major Appliances',
        'Yard, Garden & Outdoor',
        'Home Décor',
        'Tools & Workshop Equipment',
        'Home Improvement',
        'Lamps & Lighting',
        'Kitchen, Dining & Bar',

        // Fashion
        "Men's Clothing",
        "Women's Clothing",
        'Shoes & Footwear',
        'Fashion Accessories',
        'Bags & Wallets',
        'Jewelry & Watches',
        'Athletic Clothing',
        "Children's Clothing",

        // Sports
        'Cycling',
        'Fitness, Running & Yoga',
        'Team Sports',
        'Camping & Hiking',
        'Fishing',
        'Golf',
        'Winter Sports',
        'Skateboarding & Longboarding',

        // Collectibles
        'Antiques',
        'Art',
        'Coins & Paper Money',
        'Stamps',
        'Trading Cards',
        'Comics & Manga',
        'Action Figures',

        // Entertainment
        'Books & Magazines',
        'Movies & TV',
        'Music & CDs',
        'Musical Instruments & Gear',
        'DJ Equipment & Karaoke',

        // Professional Technology
        'Pro Audio Equipment',
        'Pro Video Equipment',
        '3D Printers & Supplies',
        'Enterprise Networking',
        'Software',

        // Health & Beauty
        'Personal Care',
        'Fragrances',
        'Makeup',
        'Hair Care',
        'Beauty Equipment',
        'Vitamins & Supplements',

        // Business & Industrial
        'Heavy Equipment',
        'Office Supplies',
        'Retail & Services',
        'Agriculture & Farming',
        'Construction',

        // Pets
        'Dog Supplies',
        'Cat Supplies',
        'Fish & Aquariums',
        'Bird Supplies',
        'Small Animal Supplies',

        // Baby
        'Baby Essentials',
        'Toys & Games',
        'Baby Clothing',
        'Baby Safety',
        'Strollers & Car Seats',

        // Others
        'Crafts & Hobbies',
        'Adult Only',
        'Party Supplies',
        'Seasonal Items'
    ];

    private $categoryTranslations = [
        'eBay Motors' => 'eBay Motors',
        'Cars & Trucks' => 'Autos y Camionetas',
        'Motorcycles' => 'Motocicletas',
        'Boats & Watercraft' => 'Náutica',
        'Auto Parts & Accessories' => 'Partes y Accesorios',
        'Other Vehicles' => 'Otros Vehículos',
        'Cell Phones & Accessories' => 'Teléfonos Móviles y Accesorios',
        'Computers/Tablets & Networking' => 'Computadoras y Tablets',
        'Video Games & Consoles' => 'Videojuegos y Consolas',
        'Cameras & Photography' => 'Cámaras y Fotografía',
        'TV, Audio & Surveillance' => 'TV, Audio y Vigilancia',
        'Consumer Electronics' => 'Electrónica de Consumo',
        'Drones & RC Aircraft' => 'Drones y Accesorios',
        'Furniture' => 'Muebles',
        'Major Appliances' => 'Electrodomésticos',
        'Yard, Garden & Outdoor' => 'Jardinería',
        'Home Décor' => 'Decoración del Hogar',
        'Tools & Workshop Equipment' => 'Herramientas',
        'Home Improvement' => 'Mejoras del Hogar',
        'Lamps & Lighting' => 'Iluminación',
        'Kitchen, Dining & Bar' => 'Cocina y Comedor',
        "Men's Clothing" => 'Ropa para Hombre',
        "Women's Clothing" => 'Ropa para Mujer',
        'Shoes & Footwear' => 'Calzado',
        'Fashion Accessories' => 'Accesorios de Moda',
        'Bags & Wallets' => 'Bolsos y Carteras',
        'Jewelry & Watches' => 'Joyería y Relojes',
        'Athletic Clothing' => 'Ropa Deportiva',
        "Children's Clothing" => 'Ropa para Niños',
        'Cycling' => 'Ciclismo',
        'Fitness, Running & Yoga' => 'Fitness y Running',
        'Team Sports' => 'Deportes de Equipo',
        'Camping & Hiking' => 'Camping y Outdoor',
        'Fishing' => 'Pesca',
        'Golf' => 'Golf',
        'Winter Sports' => 'Esquí y Snowboard',
        'Skateboarding & Longboarding' => 'Patinaje y Skateboard',
        'Antiques' => 'Antigüedades',
        'Art' => 'Arte',
        'Coins & Paper Money' => 'Monedas y Billetes',
        'Stamps' => 'Sellos',
        'Trading Cards' => 'Tarjetas Coleccionables',
        'Comics & Manga' => 'Comics y Manga',
        'Action Figures' => 'Figuras de Acción',
        'Books & Magazines' => 'Libros y Revistas',
        'Movies & TV' => 'Películas y Series',
        'Music & CDs' => 'Música y CD',
        'Musical Instruments & Gear' => 'Instrumentos Musicales',
        'DJ Equipment & Karaoke' => 'Karaoke y DJ',
        'Pro Audio Equipment' => 'Equipos de Audio Pro',
        'Pro Video Equipment' => 'Video Profesional',
        '3D Printers & Supplies' => 'Impresión 3D',
        'Enterprise Networking' => 'Servidores y Redes',
        'Software' => 'Software',
        'Personal Care' => 'Cuidado Personal',
        'Fragrances' => 'Perfumes y Fragancias',
        'Makeup' => 'Maquillaje',
        'Hair Care' => 'Cuidado del Cabello',
        'Beauty Equipment' => 'Equipos de Belleza',
        'Vitamins & Supplements' => 'Suplementos y Vitaminas',
        'Heavy Equipment' => 'Equipos Industriales',
        'Office Supplies' => 'Material de Oficina',
        'Retail & Services' => 'Comercio y Retail',
        'Agriculture & Farming' => 'Agricultura',
        'Construction' => 'Construcción',
        'Dog Supplies' => 'Productos para Perros',
        'Cat Supplies' => 'Productos para Gatos',
        'Fish & Aquariums' => 'Acuarios y Peces',
        'Bird Supplies' => 'Aves y Pájaros',
        'Small Animal Supplies' => 'Productos para Roedores',
        'Baby Essentials' => 'Artículos para Bebés',
        'Toys & Games' => 'Juguetes y Juegos',
        'Baby Clothing' => 'Ropa de Bebé',
        'Baby Safety' => 'Seguridad para Bebés',
        'Strollers & Car Seats' => 'Carriolas y Transporte',
        'Crafts & Hobbies' => 'Arte y Manualidades',
        'Adult Only' => 'Productos Adultos',
        'Party Supplies' => 'Artículos de Fiesta',
        'Seasonal Items' => 'Productos Temporada'
    ];


    public function getCategories(): JsonResponse
    {
        try {
            /** @var array<int, array<string, mixed>> */
            $categories = Cache::remember('ebay_categories', 86400, function (): array {
                $baseUrl = "https://api.ebay.com/commerce/taxonomy/v1";

                $response = Http::withToken($this->token)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])
                    ->get("{$baseUrl}/category_tree/0", [
                        'marketplace_id' => 'EBAY_US'
                    ]);

                if (!$response->successful()) {
                    Log::error('Error getting categories', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return [];
                }

                $data = $response->json();
                $rootCategories = [];

                if (isset($data['rootCategoryNode']['childCategoryTreeNodes'])) {
                    // Log all available categories from eBay
                    Log::info('Available eBay categories:', [
                        'categories' => array_map(function ($node) {
                            return $node['category']['categoryName'] ?? 'unnamed';
                        }, $data['rootCategoryNode']['childCategoryTreeNodes'])
                    ]);

                    foreach ($data['rootCategoryNode']['childCategoryTreeNodes'] as $node) {
                        if (isset($node['category'])) {
                            $categoryName = $node['category']['categoryName'];

                            // Log category comparison
                            Log::info('Checking category:', [
                                'ebay_category' => $categoryName,
                                'in_desired_list' => in_array($categoryName, $this->desiredCategories),
                                'desired_categories' => $this->desiredCategories
                            ]);

                            if (in_array($categoryName, $this->desiredCategories)) {
                                $rootCategories[] = [
                                    'id' => $node['category']['categoryId'],
                                    'name' => $this->categoryTranslations[$categoryName] ?? $categoryName,
                                    'name_en' => $categoryName,
                                    'level' => 1,
                                    'leaf' => isset($node['leafCategoryTreeNode']),
                                    'parent_id' => null
                                ];
                            }
                        }
                    }
                }

                // Log final matching categories
                Log::info('Matched categories:', [
                    'count' => count($rootCategories),
                    'categories' => $rootCategories
                ]);

                usort($rootCategories, function (array $a, array $b): int {
                    return strcmp($a['name'], $b['name']);
                });

                return $rootCategories;
            });

            return response()->json([
                'success' => true,
                'categories' => $categories,
                'total' => count($categories)
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getCategories', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get brands for a specific category
     * @param string $categoryId
     * @return JsonResponse
     */
    public function getCategoryBrands(string $categoryId): JsonResponse
    {
        try {
            /** @var array<int, array<string, mixed>> $brands */
            $brands = Cache::remember("category_brands_{$categoryId}", 3600, function () use ($categoryId): array {
                $baseUrl = "https://api.ebay.com/buy/browse/v1";

                $response = Http::withToken($this->token)
                    ->withHeaders([
                        'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_ES',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])
                    ->get("{$baseUrl}/item_summary/search", [
                        'category_ids' => $categoryId,
                        'limit' => 200,
                        'filter' => 'conditions:{NEW}',
                        'fieldgroups' => 'ASPECT_REFINEMENTS'
                    ]);

                if (!$response->successful()) {
                    Log::error('Error obteniendo marcas', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return [];
                }

                $data = $response->json();
                $categoryBrands = [];

                if (isset($data['refinement']['aspectDistributions'])) {
                    foreach ($data['refinement']['aspectDistributions'] as $aspect) {
                        if (strtolower($aspect['aspectName']) === 'brand') {
                            foreach ($aspect['aspectValueDistributions'] as $brand) {
                                $categoryBrands[] = [
                                    'name' => $brand['localizedAspectValue'],
                                    'count' => $brand['matchCount']
                                ];
                            }
                            break;
                        }
                    }
                }

                usort($categoryBrands, function (array $a, array $b): int {
                    return ($b['count'] ?? 0) - ($a['count'] ?? 0);
                });

                return array_slice($categoryBrands, 0, 50);
            });

            return response()->json([
                'success' => true,
                'brands' => $brands,
                'total' => is_array($brands) ? count($brands) : 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo marcas', [
                'error' => $e->getMessage(),
                'categoryId' => $categoryId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener marcas',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function fetchBrandsByCategorys(Request $request)
    {
        try {
            $categoryId = $request->get('category_id');
            $categoryName = $request->get('category_name');

            // Intentar primero por ID
            if ($categoryId) {
                $brands = Cache::remember("category_brands_{$categoryId}", 3600, function () use ($categoryId) {
                    return $this->searchBrandsInEbay($categoryId);
                });

                if (!empty($brands)) {
                    return response()->json([
                        'success' => true,
                        'brands' => $brands
                    ]);
                }
            }

            // Si no hay resultados por ID, intentar por nombre
            if ($categoryName) {
                $brands = Cache::remember("category_brands_name_" . md5($categoryName), 3600, function () use ($categoryName) {
                    return $this->searchBrandsByName($categoryName);
                });

                if (!empty($brands)) {
                    return response()->json([
                        'success' => true,
                        'brands' => $brands
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontraron marcas para esta categoría'
            ], 404);
        } catch (Exception $e) {
            Log::error('Error en fetchBrandsByCategory:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error procesando la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function searchBrandsInEbay(string $categoryId): array
    {
        // Verificar si existen marcas predefinidas para esta categoría
        if (!isset($this->categoryBrandsMap[$categoryId])) {
            Log::info('No hay marcas predefinidas para la categoría', ['categoryId' => $categoryId]);
            return [];
        }

        // Obtener las marcas predefinidas y formatearlas
        $brands = [];
        foreach ($this->categoryBrandsMap[$categoryId] as $index => $brandName) {
            $brands[] = [
                'id' => md5($brandName),
                'name' => $brandName,
                'count' => 100 - $index // Dar prioridad por orden en el array
            ];
        }

        Log::info('Marcas encontradas para la categoría', [
            'categoryId' => $categoryId,
            'brandsCount' => count($brands),
            'brands' => $brands
        ]);

        return $brands;
    }

    private function searchBrandsByName(string $categoryName): array
    {
        // Buscar el ID de la categoría por nombre
        foreach ($this->ebayMainCategories as $id => $names) {
            if (strtolower($names['es']) === strtolower($categoryName)) {
                return $this->searchBrandsInEbay($id);
            }
        }

        return [];
    }

}
