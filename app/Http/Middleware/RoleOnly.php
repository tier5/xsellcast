<?php namespace App\Http\Middleware;

use Auth;
use Closure;

class RoleOnly
{

    public function handle($request, Closure $next, $roles)
    {
    	$roles = explode('|', $roles);
    	$found = false;

    	foreach($roles as $role)
    	{
    		if ($request->user()->hasRole($role)) {
    			$found = true;
    			break;
    		}
    	}
        
        if(!$found)
        {
         	return response()->view('errors.403');
        }

        return $next($request);
    }	
}