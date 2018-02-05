<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class T_etl_query extends Model
{
    protected $table = 't_etl_query';
    protected $primaryKey='query_id';
    public $timestamps = false;
    
    public function t_etl_theme()
    {
        return $this->hasOne(T_etl_theme::class);
    }
    
}
