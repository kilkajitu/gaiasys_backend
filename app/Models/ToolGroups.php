<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolGroups extends Model
{
    protected $table = 'toolgroups';
    public function tools()
	{
	    return $this->hasMany('App\Models\Tools','toolgroup_id','id');
	}
	public function usermappings()
	{
	    return $this->hasMany('App\Models\UserMapping','toolgroup_id','id');
	}
}
