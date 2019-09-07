<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Models\UserMapping;
use App\Models\ToolGroups;
use Illuminate\Support\Facades\Validator;

class UserMappingController extends Controller
{
    public function index(Request $request){
        $input = $request->all();
        try{
            if($request->isMethod('post')){
                $validator = Validator::make(
                            $input,
                            ['toolgroup_id' => "required",'user_id' => "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $test = UserMapping::where('toolgroup_id',$input['toolgroup_id'])->where('user_id',$input['user_id'])->first();
                if(count($test)==0){
	                $UserMapping = new UserMapping();
                    $UserMapping->toolgroup_id = $input['toolgroup_id'];
                    $UserMapping->user_id = $input['user_id'];
	                $UserMapping->save();
            	}else{
            		return response()->json(['status' => 400,'msg' =>'Mapping Already Exists']);
            	}
            }else if($request->isMethod('delete')){
                $validator = Validator::make(
                            $input,
                            ['id'=> "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $delete = UserMapping::where('id',$input['id'])->delete();
            }
            $id=$input['id']??0;
            if($request->isMethod('get') && $id != 0){
                $UserMapping=UserMapping::with('toolgroup')->with('user')->where('id',$id)->get();
            }else{
                $UserMapping=UserMapping::with('toolgroup')->with('user')->get();
            }
            if(!is_null($UserMapping) && $UserMapping->count()){
                return response()->json(['status' => 200,'UserMapping' => $UserMapping]);
            }else{
                return response()->json(['status' => 200,'UserMapping' => []]);
            }
        }catch (\Exception $e){
        	return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
    }
    public function user_tools(Request $request){
        try{
            $user_id=$request->get('user_id');
            $UserMapping=ToolGroups::whereHas('usermappings',function ($query) use ($user_id) {
                        $query->where('user_id',$user_id);
                    },'>=',1)->with('tools')->get();
            if(!is_null($UserMapping) && $UserMapping->count()){
                return response()->json(['status' => 200,'UserMapping' => $UserMapping]);
            }else{
                return response()->json(['status' => 200,'UserMapping' => []]);
            }
        }catch (\Exception $e){
            return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
    }
}
?>