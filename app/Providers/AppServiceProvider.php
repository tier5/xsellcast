<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Storage\SalesRep\SalesRep;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Add default user meta to BA after created.
         */
        SalesRep::created(function($ba){
            $isAgreed = $ba->user->getMeta('salesrep_agreement');
            if(!$isAgreed)
            {
                $ba->user->setMeta('salesrep_agreement', false);
                $ba->user->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
