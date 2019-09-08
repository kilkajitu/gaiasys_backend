<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Models\ToolGroups;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ToolGroupController extends Controller
{
    public function index(Request $request){
        $input = $request->all();
        try{
            if($request->isMethod('post')){
                $validator = Validator::make(
                            $input,
                            ['name' => "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $name = trim($input['name']);
                $test = ToolGroups::where('name',$name)->first();
                if(is_null($test) || $test->count()==0){
	                $ToolGroups = new ToolGroups();
	                $ToolGroups->name = $name;
	                $ToolGroups->save();
            	}else{
            		return response()->json(['status' => 400,'msg' =>'Name Already Exists']);
            	}
            }else if($request->isMethod('put')){
                $validator = Validator::make(
                            $input,
                            ['id'=> "required",'name' => "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $name = trim($input['name']);
                $test = ToolGroups::where('name',$name)->first();
                if(is_null($test) || $test->count()==0){
                    $ToolGroups = ToolGroups::where('id',$input['id'])->first();
                    $ToolGroups->name = $name;
                    $ToolGroups->save();
                }else{
                    return response()->json(['status' => 400,'msg' =>'Name Already Exists']);
                }
            }else if($request->isMethod('delete')){
                $validator = Validator::make(
                            $input,
                            ['id'=> "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $delete = ToolGroups::where('id',$input['id'])->delete();
            }
            $id=$input['id']??0;
            if($request->isMethod('get') && $id != 0){
                $ToolGroups=ToolGroups::with('tools')->where('id',$id)->get();
            }else{
                $ToolGroups=ToolGroups::with('tools')->get();
            }
            if(!is_null($ToolGroups) && $ToolGroups->count()){
                return response()->json(['status' => 200,'ToolGroups' => $ToolGroups]);
            }else{
                return response()->json(['status' => 200,'ToolGroups' => []]);
            }
        }catch (\Exception $e){
        	return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
    }
    public function ToolGroupGet(Request $request){
        try{
            $ToolGroups=ToolGroups::select('id','name')->get();
            if(!is_null($ToolGroups) && $ToolGroups->count()){
                return response()->json(['status' => 200,'ToolGroups' => $ToolGroups]);
            }else{
                return response()->json(['status' => 200,'ToolGroups' => []]);
            }
        }catch (\Exception $e){
            return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
    }
    public function ToolGroupUser(Request $request){
        try{
            $ToolGroups=ToolGroups::select('id','name')->get();
            $Users = User::select('id','name')->where('role',0)->get();
            if(!is_null($ToolGroups) && $ToolGroups->count()){
                if(!is_null($Users) && $Users->count()){
                    return response()->json(['status' => 200,'ToolGroups' => $ToolGroups,'Users' => $Users]);
                }else{
                    return response()->json(['status' => 200,'ToolGroups' => $ToolGroups,'Users' => []]);
                }
            }else{
                if(!is_null($Users) && $Users->count()){
                    return response()->json(['status' => 200,'ToolGroups' => [],'Users' => $Users]);
                }else{
                    return response()->json(['status' => 200,'ToolGroups' => [],'Users' => []]);
                }
            }
        }catch (\Exception $e){
            return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
    }
}
?>