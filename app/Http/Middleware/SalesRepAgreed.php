<?php namespace App\Http\Middleware;

use Closure;
use Auth;

class SalesRepAgreed
{

    public function handle($request, Closure $next)
    {
        $user       = $request->user();
        $isSalesrep = $user->hasRole('sales-rep');

        if(!$isSalesrep)
        {
            return $next($request);
        }

        $isAgreed    = $user->salesRep->is_agreement;
        $passChanged = $user->salesRep->password_changed;

        if($request->route()->getName() == 'admin.settings.profile' && $passChanged && !$isAgreed)
        {

            return $next($request);
        }

        if(!$passChanged)
        {   
            $request->session()->flash('warning', 'You must first change your password to continue.');     

         	return redirect()->route('admin.settings.change.password');
        }elseif(!$isAgreed)
        {

            return redirect()->route('admin.settings.profile');
        }

        return $next($request);
    }	
}