<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\BrandDetail;
use App\Models\Product;
use App\Models\Subcategorie;
use App\Models\User;

use App\Models\Year;
use DB;
use Illuminate\Http\Request;

use App\Models\Articles;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Cache;
use Auth;
use Illuminate\Http\Response;
use Session;


class ArticleController extends Controller

{

    public function __construct()
    {
        $this->middleware(['permission:view_all_offline_articles_recharges'])->only('offline_recharge_request');
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $categories = Category::limit(10)->get();
        $brands = Brand::all();
        $year = Year::all();
        $articles = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('articles', 'articles.product_id', '=', 'products.id')
            ->join('brand_details', 'brand_details.id', '=', 'articles.model_id')
            ->join('years', 'years.id', '=', 'articles.year')
            ->where('articles.user_id', Auth::user()->id)
            ->select('articles.*', 'categories.name as category_name', 'products.name as product_name', 'brand_details.model as model_name', 'years.year as year_name')
            ->paginate(10);

        /*return response()->json([
            'articles' => $articles,
            'year' => $year,
            'brand' => $brands,
            'category' => $categories
        ]);*/

        //return response()->json(['articles' => $articles]);

        return view('frontend.user.articles.index', compact('articles', 'categories', 'year', 'brands'));
    }


    public function select()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('frontend.user.articles.index', compact('categories'));
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
            $request->validate([
                'category_id' => 'required|integer',
                'product_id' => 'required|integer',
                'model_id' => 'nullable|integer',
                'year' => 'nullable|integer',
                'chasis_serial' => 'nullable|string',
            ]);

            $request['user_id'] = Auth::user()->id;

            Articles::create($request->all());

            return [
                'status' => true,
                'message' => 'Artículo creado exitosamente',
            ];
        } catch (\Exception $e) {
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
}

