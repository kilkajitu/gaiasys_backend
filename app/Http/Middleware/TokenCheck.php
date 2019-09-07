<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use JWTFactory;
use Closure;

class TokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = str_replace("Bearer ","",$request->header('token'));
        $prefix=explode(" ",$request->header('token'))[0];
        if($prefix=="Bearer"){
            try{
                $apy = JWTAuth::getPayload($token)->toArray();
                if($apy['role']!=0){
                    return response()->json(['status' => 501,'message'=>'Unauthorized']);
                }
            }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['status' => 501,'message'=>'Unauthorized']);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['status' => 501,'message'=>'Unauthorized']);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['status' => 501,'message'=>'Unauthorized']);
            }
            $request->attributes->add(['user_id' => $apy['id']]);
            $request->attributes->add(['role' => $apy['role']]);
            return $next($request);
        }else{
           return response()->json(['status' => 501,'message'=>'Unauthorized']); 
        }
    }
}
