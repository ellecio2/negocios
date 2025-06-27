<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Category;

use App\Models\Product;

use Artisan;


use Cache;

use CoreComponentRepository;



class AdminController extends Controller

{

    /**

     * Show the admin dashboard.

     *

     * @return \Illuminate\Http\Response

     */

    public function admin_dashboard(Request $request)

    {   

        CoreComponentRepository::initializeCache();

        $root_categories = Category::where('level', 0)->get();



        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){

            $num_of_sale_data = null;

            $qty_data = null;

            foreach ($root_categories as $key => $category){

                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);

                $category_ids[] = $category->id;



                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();

                $qty = 0;

                $sale = 0;

                foreach ($products as $key => $product) {

                    $sale += $product->num_of_sale;

                    foreach ($product->stocks as $key => $stock) {

                        $qty += $stock->qty;

                    }

                }

                $qty_data .= $qty.',';

                $num_of_sale_data .= $sale.',';

            }

            $item['num_of_sale_data'] = $num_of_sale_data;

            $item['qty_data'] = $qty_data;



            return $item;

        });



        return view('backend.dashboard', compact('root_categories', 'cached_graph_data'));

    }

    public function loginfront(Request $request) {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        // Intentar autenticar al usuario
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth()->user();
    
            // Verificar el tipo de usuario y recargar la página correspondiente
            if ($user->user_type === 'seller') {
                return response()->json([
                    'state' => true,
                    'redirect_url' => route('seller.dashboard') // Cambia según el tipo de usuario
                ]);
            } elseif (in_array($user->user_type, ['admin', 'staff'])) {
                return response()->json([
                    'state' => true,
                    'redirect_url' => route('admin.dashboard') // Cambia según el tipo de usuario
                ]);
                // return redirect()->back(); // Recargar la página actual para admin o staff
            } else {
                // Cerrar sesión si el usuario no tiene el tipo permitido
                auth()->logout();
                return response()->json(['result' => false, 'message' => 'No tienes permisos para iniciar sesión'], 403);
            }
        } else {
            // Credenciales incorrectas
            return response()->json(['result' => false, 'message' => 'Correo o contraseña incorrectos'], 401);
        }
    }


    function clearCache(Request $request)

    {

        Artisan::call('optimize:clear');

        flash(translate('Cache cleared successfully'))->success();

        return back();

    }

}

