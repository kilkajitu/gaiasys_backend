<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    protected $table = 'tools';
    public function toolgroupn()
	{
	    return $this->belongsTo('App\Models\ToolGroups','toolgroup_id')->select('id','name');
	}
}
