<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    protected $table = 'usermapping';
	public function toolgroup()
	{
	    return $this->belongsTo('App\Models\ToolGroups','toolgroup_id')->select('id','name');
	}
	public function user()
	{
	    return $this->belongsTo('App\Models\User','user_id')->select('id','name','username');
	}
}
