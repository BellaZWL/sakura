<?php

use Illuminate\Http\Request;
use App\Models\T_etl_theme;
use App\Http\Resources\T_etl_theme as theme;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test',function(){
	return 'hello word';
});


Route::get('t_etl_theme',function(){
	
	//$theme=T_etl_theme::find($theme_name);
	
	return new theme(T_etl_theme::all());
});

/*
Route::get('t_etl_theme',function(){
	return $request->description();
});*/