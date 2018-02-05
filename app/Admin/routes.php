<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
	$router->resource('users',UserController::class);
	//$router->resource('movies',MovieController::class);//movies复数,表名和控制器里都是单数
	$router->resource('t_etl_logs',T_etl_logController::class);
	$router->resource('t_etl_themes',T_etl_themeController::class);
        $router->resource('t_etl_querys',T_etl_queryController::class);
	$router->resource('t_etl_dbs',T_etl_dbController::class);
	$router->resource('v_rows',V_rowController::class);
	$router->resource('v_sizes',V_sizeController::class);

});
