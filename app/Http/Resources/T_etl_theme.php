<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class T_etl_theme extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		/*
		return [
		'description'=>$this->description,
		];
		*/
        return parent::toArray($request);
    }
}
