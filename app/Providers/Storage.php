<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Storage extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Storage\Offer\OfferRepository::class, \App\Storage\Offer\OfferRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Brand\BrandRepository::class, \App\Storage\Brand\BrandRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Dealer\DealerRepository::class, \App\Storage\Dealer\DealerRepositoryEloquent::class);
        $this->app->bind(\App\Storage\User\UserRepository::class, \App\Storage\User\UserRepositoryEloquent::class);
        $this->app->bind(\App\Storage\SalesRep\SalesRepRepository::class, \App\Storage\SalesRep\SalesRepRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Role\RoleRepository::class, \App\Storage\Role\RoleRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Permission\PermissionRepository::class, \App\Storage\Permission\PermissionRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Customer\CustomerRepository::class, \App\Storage\Customer\CustomerRepositoryEloquent::class);
        $this->app->bind(\App\Storage\UserActivations\UserActivationsRepository::class, \App\Storage\UserActivations\UserActivationsRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Media\MediaRepository::class, \App\Storage\Media\MediaRepositoryEloquent::class);
        $this->app->bind(\App\Storage\OfferTag\OfferTagRepository::class, \App\Storage\OfferTag\OfferTagRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Messenger\MessageRepository::class, \App\Storage\Messenger\MessageRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Messenger\ThreadRepository::class, \App\Storage\Messenger\ThreadRepositoryEloquent::class);
        //$this->app->bind(\App\Storage\UserActionUserActionRepository::class, \App\Storage\UserActionUserActionRepositoryEloquent::class);
        $this->app->bind(\App\Storage\UserAction\UserActionRepository::class, \App\Storage\UserAction\UserActionRepositoryEloquent::class);
        $this->app->bind(\App\Storage\DealersCategory\DealersCategoryRepository::class, \App\Storage\DealersCategory\DealersCategoryRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Category\CategoryRepository::class, \App\Storage\Category\CategoryRepositoryEloquent::class);
    //    $this->app->bind(\App\Storage\CsrCsrRepository::class, \App\Storage\CsrCsrRepositoryEloquent::class);
        $this->app->bind(\App\Storage\Csr\CsrRepository::class, \App\Storage\Csr\CsrRepositoryEloquent::class);
        $this->app->bind(\App\Storage\NotificationSetting\NotificationSettingRepository::class, \App\Storage\NotificationSetting\NotificationSettingRepositoryEloquent::class);
        //:end-bindings:
    }
}
