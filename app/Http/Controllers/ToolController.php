<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Models\Tools;
use Illuminate\Support\Facades\Validator;

class ToolController extends Controller
{
    public function index(Request $request){
        $input = $request->all();
        try{
            if($request->isMethod('post')){
                $validator = Validator::make(
                            $input,
                            ['name' => "required",'purchasedate' => "required",'costprice' => "required",'toolgroup_id' => "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $name = trim($input['name']);
                $test = Tools::where('name',$name)->where('toolgroup_id',$input['toolgroup_id'])->first();
                if($test->count()==0){
	                $Tools = new Tools();
	                $Tools->name = $name;
                    $Tools->purchasedate = $input['purchasedate'];
                    $Tools->costprice = $input['costprice'];
                    $Tools->toolgroup_id = $input['toolgroup_id'];
	                $Tools->save();
            	}else{
            		return response()->json(['status' => 400,'msg' =>'Name Already Exists']);
            	}
            }else if($request->isMethod('put')){
                $validator = Validator::make(
                            $input,
                            ['id'=> "required",'name' => "required",'purchasedate' => "required",'costprice' => "required",'toolgroup_id' => "required"]
                        );
                if ($validator->fails()) {
                    return response()->json(['status' => 401,'msg' => $validator->messages()->getMessages()]);
                }
                $name = trim($input['name']);
                $test = Tools::where('name',$name)->where('toolgroup_id',$input['toolgroup_id'])->first();
                if($test->count()==0){
                    $Tools = Tools::where('id',$input['id'])->first();
                    $Tools->name = $name;
                    $Tools->purchasedate = $input['purchasedate'];
                    $Tools->costprice = $input['costprice'];
                    $Tools->toolgroup_id = $input['toolgroup_id'];
                    $Tools->save();
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
                $delete = Tools::where('id',$input['id'])->delete();
            }
            $id=$input['id']??0;
            if($request->isMethod('get') && $id != 0){
                $Tools=Tools::with('toolgroupn')->where('id',$id)->get();
            }else{
                $Tools=Tools::with('toolgroupn')->get();
            }
            if(!is_null($Tools) && $Tools->count()){
                return response()->json(['status' => 200,'Tools' => $Tools]);
            }else{
                return response()->json(['status' => 200,'Tools' => []]);
            }
        }catch (\Exception $e){
        	return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
    }
}
?>