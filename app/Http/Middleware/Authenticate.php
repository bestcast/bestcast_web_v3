<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use URL;

class Authenticate extends Middleware
{

    /*
    
    commented this code api/logout have error

    protected function redirectTo(Request $request): ?string
    {
        // if (Auth::guest()) {
        //     return response()->json(['message' => 'you shall not pass']);
        // }

       // return response()->json(['message' => 'you shall not pass']);

        // if (! $request->expectsJson()) {
        //     return route('auth.login');
        // }

        if (! $request->expectsJson()) {
            $current_url=URL::current();
            // if(Route::getCurrentRoute()->getActionName()=="App\Http\Controllers\SubscribeController@index"){
            //     $subscription=explode("account/subscribe/",$current_url);
            //     if(!empty($subscription[1])){
            //         return route('register').'?subscription='.$subscription[1];
            //     }
            // }
            return route('login');
        }
    }
    */

}
