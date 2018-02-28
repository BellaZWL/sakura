<?php

use Illuminate\Http\Request;
use App\Models\T_etl_theme;
use App\Models\T_etl_db;
use App\Http\Resources\T_etl_theme as theme;
use \Curl\Curl;
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
	
	header("Content-type: application/json");
        return T_etl_theme::all()->pluck('theme_name');

});

Route::get('t_etl_db',function(){

        header("Content-type: application/json");
        return T_etl_DB::all()->pluck('DB');

});

Route::get('t_etl_db/dbtype', function(Request $request)
    {     
          #$db='chaoge';
          $db=$request->get('q');          

          return T_etl_DB::all()->where('DB','=',$db)->pluck('Dbtype','DBid'); //->where('DB','='i#,$db);
});

Route::post('t_etl_query/get_sql',function(Request $request)
    {
        $DBname=$request->get('DBname');
        $Dbtype=$request->get('Dbtype');
        $Tname=$request->get('Tname');
        $IncreaseColumn=$request->get('IncreaseColumn');
        $url = "http://127.0.0.1:5000/api/get_sql";
        //$post_str="DBname=$DBname&Dbtype=$Dbtype&Tname=$Tname";        
        $post_str = array(
             "DBname" => $DBname,
             "Dbtype" => $Dbtype,
             "Tname"  => $Tname,
             "IncreaseColumn" => $IncreaseColumn
        );
        return  response()->json(request_by_curl($url,$post_str));
});

Route::post('t_etl_query/do_test',function(Request $request)
{
       $DBname=$request->get('DBname');
       $Dbtype=$request->get('Dbtype');
       $Tname=$request->get('Tname');
       $SQL=$request->get('sql_text');
       $url = "http://127.0.0.1:5000/api/do_test";     
       $post_str = array(
             "DBname" => $DBname,
             "Dbtype" => $Dbtype,
             "Tname"  => $Tname,
             "SQL" => $SQL
        );
       return  response()->json(request_by_curl($url,$post_str));
});

Route::post('t_etl_query/do_create',function(Request $request)
{      
       $DBname=$request->get('DBname');
       $Dbtype=$request->get('Dbtype');
       $Tname=$request->get('Tname');
       $SQL=$request->get('sql_text');
       $Theme_name=$request->get('theme_name');
       $modified=$request->get('modified');
       $is_on=$request->get('is_on');
       $is_increase=$request->get('is_increase');
       $IncreaseColumn=$request->get('IncreaseColumn');
       $url = "http://127.0.0.1:5000/api/do_create";
       $post_str = array(
             "DBname" => $DBname,
             "Dbtype" => $Dbtype,
             "Tname"  => $Tname,
             "SQL" => $SQL,
             "theme_name"=>$Theme_name,
             "modified"=>$modified,
             "is_on"=>$is_on,
             "is_increase"=>$is_increase,
             "IncreaseColumn"=>$IncreaseColumn
        );
       return  response()->json(request_by_curl($url,$post_str));
});

Route::post('t_etl_query/test_run',function(Request $request)
{
      $queryid=$request->get('queryid');
      $url = "http://127.0.0.1:5000/api/test_run";
      $post_str = array(
             "queryid" => $queryid
      );
      return  response()->json(request_by_curl($url,$post_str));
});

function request_by_curl($url,$post_data){
        $res = curl_init();
        curl_setopt($res,CURLOPT_URL,$url);
        curl_setopt($res, CURLOPT_POST, 1);
        curl_setopt($res, CURLOPT_POSTFIELDS, $post_data); 
        curl_setopt($res,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($res,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($res,CURLOPT_SSL_VERIFYHOST,0);
        $result = curl_exec($res);
        curl_close($res);
        return $result;           

}



