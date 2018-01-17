<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {   
        if(class_exists('Html')) {
            require base_path() . '/resources/macros/admin/form.php';
            require base_path() . '/resources/macros/admin/box.php';
            require base_path() . '/resources/macros/admin/html.php';
            require base_path() . '/resources/macros/admin/modal.php';
        }
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