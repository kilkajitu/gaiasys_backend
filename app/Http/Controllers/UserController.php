<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\PayloadFactory;

class UserController extends Controller
{
    public function login(Request $request){

      $username = $request->input('username');
      $password = $request->input('password');

      $user = User::where('username',$username)->first();

      if(count($user)!=0 && password_verify($password, $user->password)){

        $factory = JWTFactory::addClaims([
            'id'=>$user->id,
            'username'=>$user->username,
            'role'=>$user->role
          ]);

        $payload = $factory->make();
        $auth_key =  JWTAuth::encode($payload);
        $token    = "Bearer ".JWTAuth::encode($payload);

        return response()->json(['status' => 200,'token' => $token,'role'=>$user->role]);

      }else{

        return response()->json(['status' => 400,'token' => '']);

      }
   }
   public function register(Request $request){

      $username = $request->input('username');
      $password = $request->input('password');

      $user = User::where('username',$username)->first();

      if(count($user)==0){

        $new_user = new User();
        $new_user->name = $request->input('name');
        $new_user->username = $request->input('username');
        $new_user->password = password_hash($password, PASSWORD_BCRYPT);
        $new_user->role = 0;
        $new_user->save();

        return response()->json(['status' => 200,'msg'=>'User Created Successfully.']);

      }else{

        return response()->json(['status' => 400,'msg'=>'UserName Already Exists.']);

      }
   }
   public function userprofile(Request $request){
      try{
            $user_id=$request->get('user_id');
            $Profile=User::join('userrole','userrole.role','=','users.role')->select('users.name','users.username','userrole.name as role')->where('id',$user_id)->first();
            if(!is_null($Profile) && $Profile->count()){
                return response()->json(['status' => 200,'Profile' => $Profile]);
            }else{
                return response()->json(['status' => 200,'Profile' => []]);
            }
        }catch (\Exception $e){
            return response()->json(['status' => 400,'msg' => $e->getMessage()]);
        }
   }

}
