<?php

namespace App\Http\Controllers;



use App\Models\User;

use Illuminate\Http\Request;

use App\Models\Articles;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Cache;

use Auth;

use Session;



class ArticlesController extends Controller

{

    public function __construct() {

        // Staff Permission Check

        $this->middleware(['permission:view_all_offline_articles_recharges'])->only('offline_recharge_request');

    }



    public function index()

    {

        $sort_search = null;
        $Ids = [1, 2, 3, 4, 6, 7]; // Aquí puedes definir los IDs que quieras

        $categories = Category::orderBy('order_level', 'desc');
        
        $categories = $categories->whereIn('id', $Ids);
        
        $categories = $categories->paginate(15);
        
        return view('frontend.user.articles.index', compact('categories'));

    }

    public function addArticlesModal()
    {
        $data = Articles::all();
    
        return response()->json($data);
    }


    // public function recharge(Request $request)

    // {

    //     $data['amount'] = $request->amount;

    //     $data['payment_method'] = $request->payment_option;



    //     $request->session()->put('payment_type', 'articles_payment');

    //     $request->session()->put('payment_data', $data);



    //     $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";

    //     if (class_exists($decorator)) {

    //         return (new $decorator)->pay($request);

    //     }

    // }







}

