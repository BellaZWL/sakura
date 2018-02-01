<?php

namespace App\Models;

//覆盖掉模型的数据获取方法
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class T_etl_log extends Model
{
    protected $table = 't_etl_log';

    public $timestamps = false;
/*
	public function paginate()
	{
		
	$perPage = Request::get('per_page', 10);

    $page = Request::get('page', 1);

    $start = ($page-1)*$perPage;

    // 运行sql获取数据数组
    $sql = 'select etl_theme,etl_db,etl_table,snapshot_date,row_count,size from t_etl_logs
	where snapshot_date BETWEEN CURRENT_DATE-6 and CURRENT_DATE 
	group by etl_theme,etl_db,etl_table,snapshot_date';

    $result = t_etl_log::select($sql);

    //$logs = static::hydrate($result);
	//$logs = static::hydrate();
     $total=0;
    //$paginator = new LengthAwarePaginator($logs, $total, $perPage);
	$paginator = new LengthAwarePaginator($result, $total, $perPage);

    $paginator->setPath(url()->current());

    return $paginator;
}

public static function with($relations)
{
    return new static;
}
*/
}
