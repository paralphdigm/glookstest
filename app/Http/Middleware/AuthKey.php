<?php

namespace App\Http\Middleware;

use Closure;
use App\ApiToken;
use App\Http\Requests;
class AuthKey
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
        $apps = ApiToken::all();
        $token = $request->header('APP_KEY');
        
        foreach($apps as $app){
            if($token != $app->api_token){
                return response()->json(['message' => 'App key not found'],401);
            }
            return $next($request);
        }
        
    }
}
