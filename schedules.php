<?php
	ini_set('serialize_precision', -1);

	define('LARAVEL_START', microtime(true));
	
	require __DIR__.'/vendor/autoload.php';
	
	$app = require_once __DIR__.'/bootstrap/app.php';
	
	$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

	$response = $kernel->handle(
	    $request = Illuminate\Http\Request::capture()
	);
	
	$response->send();
	
	$kernel->terminate($request, $response);	
	
	use App\Http\Controllers\Mensajeria\WorkShopController;
			
	WorkShopController::startWorkshops();
?>