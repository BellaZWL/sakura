<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class T_etl_theme extends Model
{
    protected $table = 't_etl_theme';
	protected $primaryKey='theme_id';
    public $timestamps = false;

    public function t_etl_query()
    {
        return $this->belongsTo(T_etl_query::class);
    }
    
}
