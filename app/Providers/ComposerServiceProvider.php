<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'admin.partials.nav-vertical', 'App\Http\ViewComposers\ProfileComposer'
        );

        view()->composer(
            'admin.layout.admin-main', 'App\Http\ViewComposers\SalesRepAgreementComposer'
        );        

        view()->composer(
            'admin.home.csr', 'App\Http\ViewComposers\CsrHomeTopComposer'
        );     

        view()->composer(
            'admin.home.ba', 'App\Http\ViewComposers\BaHomeTopComposer'
        );      

        view()->creator(
            'admin.messages.show.parts.newlead-buttons', 'App\Http\ViewCreators\NewLeadButtonsCreator'
        );                          
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
